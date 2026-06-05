<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Technology;
use App\Http\Requests\Admin\StoreTechnologyRequest;

class TechnologyController extends Controller
{
    public function index()
    {
        $technologies = Technology::orderBy('name')->get();
        return view('admin.technologies.index', compact('technologies'));
    }

    public function store(StoreTechnologyRequest $request)
    {
        Technology::create($request->validated());
        return redirect()->route('admin.technologies.index')->with('success', 'Technology created.');
    }

    public function edit(Technology $technology)
    {
        return view('admin.technologies.index', [
            'technologies' => Technology::orderBy('name')->get(),
            'editing' => $technology,
        ]);
    }

    public function update(StoreTechnologyRequest $request, Technology $technology)
    {
        $technology->update($request->validated());
        return redirect()->route('admin.technologies.index')->with('success', 'Technology updated.');
    }

    public function destroy(Technology $technology)
    {
        if ($technology->projects()->count() > 0) {
            return redirect()->route('admin.technologies.index')
                ->with('error', 'Cannot delete a technology used by projects.');
        }

        $technology->delete();
        return redirect()->route('admin.technologies.index')->with('success', 'Technology removed.');
    }
}
