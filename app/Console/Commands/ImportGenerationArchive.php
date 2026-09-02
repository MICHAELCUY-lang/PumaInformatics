<?php

namespace App\Console\Commands;

use App\Models\Cabinet;
use App\Models\CabinetDepartment;
use App\Models\CabinetMember;
use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Imports a past generation's roster and programme from a JSON manifest plus a
 * directory of photos, so historical cabinets can be browsed alongside the
 * current one.
 *
 * The Gen-2 site kept its real content hardcoded in Vue components rather than
 * in its database (the accompanying SQL dump held one placeholder event and no
 * member photos), so the manifest is generated from those components rather
 * than from a database export.
 *
 * Idempotent: re-running updates in place instead of duplicating.
 */
class ImportGenerationArchive extends Command
{
    protected $signature = 'archive:import
                            {--source= : Directory holding manifest.json and photos/}
                            {--dry-run : Report what would change without writing}';

    protected $description = 'Import a past cabinet generation (departments, members, events) from a manifest';

    public function handle(): int
    {
        $source = rtrim((string) $this->option('source'), '/');
        $dryRun = (bool) $this->option('dry-run');

        if (! $source || ! is_dir($source)) {
            $this->error('Pass --source pointing at the directory that holds manifest.json');

            return self::FAILURE;
        }

        $manifestPath = $source.'/manifest.json';
        if (! is_file($manifestPath)) {
            $this->error("manifest.json not found in {$source}");

            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        $photoDir = $source.'/photos';

        if ($dryRun) {
            $this->warn('DRY RUN — nothing will be written.');
        }

        // Managed by hand rather than DB::transaction() so a dry run can roll
        // back deliberately; calling rollBack() from inside the closure would
        // leave the transaction manager in an inconsistent state.
        DB::beginTransaction();

        try {
            $cabinet = $this->upsertCabinet($data['cabinet'], $photoDir, $dryRun);
            $departments = $this->upsertDepartments($cabinet, $data['divisions'] ?? [], $dryRun);
            $members = $this->upsertMembers($cabinet, $departments, $data['members'] ?? [], $photoDir, $dryRun);
            $events = $this->upsertEvents($cabinet, $data['events'] ?? [], $dryRun);
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Import failed, nothing was written: '.$e->getMessage());

            return self::FAILURE;
        }

        $dryRun ? DB::rollBack() : DB::commit();

        $result = compact('cabinet', 'departments', 'members', 'events');

        $this->newLine();
        $this->info(sprintf(
            '%s: %d departments, %d members, %d events.',
            $result['cabinet']->name,
            count($result['departments']),
            $result['members'],
            $result['events'],
        ));

        return self::SUCCESS;
    }

    private function upsertCabinet(array $spec, string $photoDir, bool $dryRun): Cabinet
    {
        // Match on term first: the live site may already carry this cabinet
        // under a slightly different name, and creating a second one would
        // split the archive in two.
        $cabinet = Cabinet::where('term_year', $spec['term_year'])->first()
            ?? Cabinet::where('slug', Str::slug($spec['name']))->first()
            ?? new Cabinet;

        $cabinet->fill([
            'name' => $cabinet->exists ? $cabinet->name : $spec['name'],
            'term_year' => $spec['term_year'],
            'generation' => $spec['generation'] ?? null,
            'tagline' => $spec['tagline'] ?? null,
        ]);

        if (! $cabinet->exists) {
            $cabinet->is_active = false;
        }

        if (! $dryRun) {
            $cabinet->save();
        }

        $this->line(sprintf('  cabinet  %s (%s)', $cabinet->name, $cabinet->term_year));

        $logo = $spec['logo'] ?? null;
        if ($logo && is_file($photoDir.'/'.$logo)) {
            if (! $dryRun && ! $cabinet->getFirstMedia('logo')) {
                $cabinet->addMedia($photoDir.'/'.$logo)->preservingOriginal()->toMediaCollection('logo');
                $this->line('  logo     '.$logo);
            }
        } elseif ($logo) {
            $this->warn("  logo file missing: {$logo}");
        }

        return $cabinet;
    }

    /** @return array<string, CabinetDepartment> keyed by division code */
    private function upsertDepartments(Cabinet $cabinet, array $divisions, bool $dryRun): array
    {
        $map = [];

        foreach ($divisions as $division) {
            $slug = Str::slug($division['name']);

            $department = CabinetDepartment::firstOrNew([
                'cabinet_id' => $cabinet->id,
                'slug' => $slug,
            ]);

            $department->fill([
                'cabinet_id' => $cabinet->id,
                'name' => $division['name'],
                'slug' => $slug,
                'order' => $division['order'] ?? 0,
                'is_active' => true,
            ]);

            if (! $dryRun) {
                $department->save();
            }

            $map[$division['code']] = $department;
        }

        $this->line('  departments '.implode(', ', array_keys($map)));

        return $map;
    }

    private function upsertMembers(Cabinet $cabinet, array $departments, array $members, string $photoDir, bool $dryRun): int
    {
        $count = 0;
        $missingPhotos = [];

        foreach ($members as $spec) {
            $department = $departments[$spec['division']] ?? null;

            $member = CabinetMember::where('cabinet_id', $cabinet->id)
                ->where('name', $spec['name'])
                ->first() ?? new CabinetMember;

            if (! $member->exists) {
                $member->slug = $this->uniqueMemberSlug($spec['name'], $cabinet);
            }

            $member->fill([
                'cabinet_id' => $cabinet->id,
                'department_id' => $department?->id,
                'name' => $spec['name'],
                'role_title' => $spec['role_title'] ?: 'Member',
                'role_hierarchy_level' => $spec['rank'] ?? 50,
                'term_year' => $cabinet->term_year,
                'is_active' => true,
                'social_links' => $spec['social_links'] ?? null,
            ]);

            if (! $dryRun) {
                $member->save();
            }

            $photo = $spec['photo'] ?? null;
            if ($photo && is_file($photoDir.'/'.$photo)) {
                if (! $dryRun && ! $member->getFirstMedia('portrait')) {
                    $member->addMedia($photoDir.'/'.$photo)
                        ->preservingOriginal()
                        ->toMediaCollection('portrait');
                }
            } elseif ($photo) {
                $missingPhotos[] = $photo;
            }

            $count++;
        }

        if ($missingPhotos) {
            $this->warn('  photos missing: '.count($missingPhotos).' ('.implode(', ', array_slice($missingPhotos, 0, 5)).')');
        }

        return $count;
    }

    /**
     * Member slugs are globally unique, but people serve across several terms —
     * ten of the Gen-2 names already exist under the current cabinet. Suffix
     * with the term so both records survive.
     */
    private function uniqueMemberSlug(string $name, Cabinet $cabinet): string
    {
        $base = Str::slug($name);

        if (! CabinetMember::where('slug', $base)->exists()) {
            return $base;
        }

        $scoped = $base.'-'.Str::slug($cabinet->name);
        $candidate = $scoped;
        $i = 2;

        while (CabinetMember::where('slug', $candidate)->exists()) {
            $candidate = $scoped.'-'.$i++;
        }

        return $candidate;
    }

    private function upsertEvents(Cabinet $cabinet, array $events, bool $dryRun): int
    {
        $count = 0;

        foreach ($events as $spec) {
            if (empty($spec['start_date'])) {
                $this->warn("  event skipped, unparseable date: {$spec['title']}");

                continue;
            }

            $slug = Str::slug($spec['title'].' '.substr($spec['start_date'], 0, 7));

            $event = Event::where('cabinet_id', $cabinet->id)
                ->where('title', $spec['title'])
                ->first() ?? new Event(['slug' => $slug]);

            $event->fill([
                'cabinet_id' => $cabinet->id,
                'title' => $spec['title'],
                'description' => $spec['description'] ?? null,
                'excerpt' => Str::limit((string) ($spec['description'] ?? ''), 200) ?: null,
                'start_date' => $spec['start_date'],
                'status' => 'published',
            ]);

            if (! $dryRun) {
                $event->save();
            }

            $count++;
        }

        return $count;
    }
}
