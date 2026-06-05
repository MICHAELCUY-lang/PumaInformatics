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
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'term_year' => ['required', 'string', 'max:50', 'unique:cabinets,term_year'],
            'is_active' => ['boolean'],
        ]);

        // If this cabinet is set to active, deactivate all others
        if (!empty($validated['is_active'])) {
            Cabinet::where('is_active', true)->update(['is_active' => false]);
        }

        Cabinet::create($validated);

        return redirect()->route('admin.cabinets.index')->with('success', 'Cabinet period created.');
    }

    public function edit(Cabinet $cabinet)
    {
        return view('admin.cabinets.edit', compact('cabinet'));
    }

    public function update(Request $request, Cabinet $cabinet)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'term_year' => ['required', 'string', 'max:50', 'unique:cabinets,term_year,' . $cabinet->id],
            'is_active' => ['boolean'],
        ]);

        // If this cabinet is being activated, deactivate all others
        if (!empty($validated['is_active']) && !$cabinet->is_active) {
            Cabinet::where('is_active', true)->update(['is_active' => false]);
        }

        $cabinet->update($validated);

        return redirect()->route('admin.cabinets.index')->with('success', 'Cabinet period updated.');
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
