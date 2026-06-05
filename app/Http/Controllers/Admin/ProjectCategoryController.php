<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectCategory;
use App\Http\Requests\Admin\StoreProjectCategoryRequest;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::orderBy('order')->get();
        return view('admin.project-categories.index', compact('categories'));
    }

    public function store(StoreProjectCategoryRequest $request)
    {
        ProjectCategory::create($request->validated());
        return redirect()->route('admin.project-categories.index')->with('success', 'Category created.');
    }

    public function edit(ProjectCategory $projectCategory)
    {
        return view('admin.project-categories.index', [
            'categories' => ProjectCategory::orderBy('order')->get(),
            'editing' => $projectCategory,
        ]);
    }

    public function update(StoreProjectCategoryRequest $request, ProjectCategory $projectCategory)
    {
        $projectCategory->update($request->validated());
        return redirect()->route('admin.project-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(ProjectCategory $projectCategory)
    {
        if ($projectCategory->projects()->count() > 0) {
            return redirect()->route('admin.project-categories.index')
                ->with('error', 'Cannot delete a category with assigned projects.');
        }

        $projectCategory->delete();
        return redirect()->route('admin.project-categories.index')->with('success', 'Category removed.');
    }
}
