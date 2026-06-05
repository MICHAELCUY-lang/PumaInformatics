<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AspirationCategory;
use App\Http\Requests\Admin\StoreAspirationCategoryRequest;

class AspirationCategoryController extends Controller
{
    public function index()
    {
        $categories = AspirationCategory::orderBy('order')->get();
        return view('admin.aspiration-categories.index', compact('categories'));
    }

    public function store(StoreAspirationCategoryRequest $request)
    {
        AspirationCategory::create($request->validated());
        return redirect()->route('admin.aspiration-categories.index')->with('success', 'Category created.');
    }

    public function edit(AspirationCategory $aspirationCategory)
    {
        return view('admin.aspiration-categories.index', [
            'categories' => AspirationCategory::orderBy('order')->get(),
            'editing' => $aspirationCategory,
        ]);
    }

    public function update(StoreAspirationCategoryRequest $request, AspirationCategory $aspirationCategory)
    {
        $aspirationCategory->update($request->validated());
        return redirect()->route('admin.aspiration-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(AspirationCategory $aspirationCategory)
    {
        if ($aspirationCategory->aspirations()->count() > 0) {
            return redirect()->route('admin.aspiration-categories.index')
                ->with('error', 'Cannot delete a category with assigned aspirations.');
        }

        $aspirationCategory->delete();
        return redirect()->route('admin.aspiration-categories.index')->with('success', 'Category removed.');
    }
}
