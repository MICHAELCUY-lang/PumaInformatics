<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Turn cabinets into self-contained generations.
 *
 * Departments used to be global, shared by every cabinet, which made it
 * impossible to present each generation with the structure it actually had:
 * the Kaustav term ran Internal Relations and External Relations as separate
 * divisions and had a Technopreneur division, none of which exist in the
 * current term. A shared list would either misrepresent the archive or add
 * phantom departments to the current cabinet.
 *
 * Slugs become unique per cabinet rather than globally, because names
 * legitimately repeat across generations — "Research and Technology" exists in
 * both terms and would otherwise collide.
 *
 * Events gain a cabinet so the archive can show what each generation ran.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cabinets', function (Blueprint $table) {
            // Which generation of the organisation/website this term belongs to.
            // Nullable: older rows predate the concept and fall back to term_year.
            $table->unsignedTinyInteger('generation')->nullable()->after('term_year');
            $table->string('tagline')->nullable()->after('generation');
        });

        Schema::table('cabinet_departments', function (Blueprint $table) {
            $table->foreignId('cabinet_id')
                ->nullable()
                ->after('id')
                ->constrained('cabinets')
                ->cascadeOnDelete();
        });

        // Existing departments were created for whichever cabinet is active now.
        $activeCabinetId = DB::table('cabinets')->where('is_active', true)->value('id')
            ?? DB::table('cabinets')->orderByDesc('id')->value('id');

        if ($activeCabinetId) {
            DB::table('cabinet_departments')->whereNull('cabinet_id')->update(['cabinet_id' => $activeCabinetId]);
        }

        Schema::table('cabinet_departments', function (Blueprint $table) {
            $table->dropUnique('cabinet_departments_slug_unique');
            $table->unique(['cabinet_id', 'slug'], 'cabinet_departments_cabinet_slug_unique');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('cabinet_id')
                ->nullable()
                ->after('category_id')
                ->constrained('cabinets')
                ->nullOnDelete();
        });

        // Existing events belong to the current term.
        if ($activeCabinetId) {
            DB::table('events')->whereNull('cabinet_id')->update(['cabinet_id' => $activeCabinetId]);
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cabinet_id');
        });

        Schema::table('cabinet_departments', function (Blueprint $table) {
            $table->dropUnique('cabinet_departments_cabinet_slug_unique');
            $table->dropConstrainedForeignId('cabinet_id');
            $table->unique('slug', 'cabinet_departments_slug_unique');
        });

        Schema::table('cabinets', function (Blueprint $table) {
            $table->dropColumn(['generation', 'tagline']);
        });
    }
};
