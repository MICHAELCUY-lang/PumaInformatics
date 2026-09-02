<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cabinet;

class CabinetController extends Controller
{
    public function index()
    {
        $cabinets = Cabinet::withCount('members')->orderBy('term_year', 'desc')->get();
        return view('admin.cabinets.index', compact('cabinets'));
    }

    public function create()
    {
        return view('admin.cabinets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // If this cabinet is set to active, deactivate all others
        if (!empty($validated['is_active'])) {
            Cabinet::where('is_active', true)->update(['is_active' => false]);
        }

        $cabinet = Cabinet::create(collect($validated)->except('logo')->all());
        $this->syncLogo($request, $cabinet);

        return redirect()->route('admin.cabinets.index')->with('success', 'Cabinet period created.');
    }

    public function edit(Cabinet $cabinet)
    {
        return view('admin.cabinets.edit', compact('cabinet'));
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $validated = $request->validate($this->rules($cabinet));

        // If this cabinet is being activated, deactivate all others
        if (!empty($validated['is_active']) && !$cabinet->is_active) {
            Cabinet::where('is_active', true)->update(['is_active' => false]);
        }

        $cabinet->update(collect($validated)->except('logo')->all());
        $this->syncLogo($request, $cabinet);

        return redirect()->route('admin.cabinets.index')->with('success', 'Cabinet period updated.');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(?Cabinet $cabinet = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'term_year' => [
                'required', 'string', 'max:50',
                'unique:cabinets,term_year'.($cabinet ? ','.$cabinet->id : ''),
            ],
            // Which generation of the organisation this term belongs to; drives
            // the ordering of the lineage strip on the public site.
            'generation' => ['nullable', 'integer', 'min:1', 'max:99'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:2048'],
            'is_active' => ['boolean'],
        ];
    }

    private function syncLogo(Request $request, Cabinet $cabinet): void
    {
        if (! $request->hasFile('logo')) {
            return;
        }

        // singleFile() on the collection replaces rather than accumulates.
        $cabinet->addMediaFromRequest('logo')->toMediaCollection('logo');
    }

    public function destroy(Cabinet $cabinet)
    {
        if ($cabinet->members()->count() > 0) {
            return redirect()->route('admin.cabinets.index')
                ->with('error', 'Cannot delete a cabinet period with assigned members. Reassign members first.');
        }

        $cabinet->delete();
        return redirect()->route('admin.cabinets.index')->with('success', 'Cabinet period removed.');
    }
}
