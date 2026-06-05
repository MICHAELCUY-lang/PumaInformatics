<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartnerCategory;
use App\Http\Requests\Admin\StorePartnerCategoryRequest;

class PartnerCategoryController extends Controller
{
    public function index()
    {
        $categories = PartnerCategory::with('parent')->orderBy('order')->get();
        return view('admin.partner-categories.index', compact('categories'));
    }

    public function store(StorePartnerCategoryRequest $request)
    {
        PartnerCategory::create($request->validated());
        return redirect()->route('admin.partner-categories.index')->with('success', 'Category created.');
    }

    public function edit(PartnerCategory $partnerCategory)
    {
        $parentOptions = PartnerCategory::where('id', '!=', $partnerCategory->id)->orderBy('order')->get();
        return view('admin.partner-categories.index', [
            'categories' => PartnerCategory::with('parent')->orderBy('order')->get(),
            'editing' => $partnerCategory,
            'parentOptions' => $parentOptions,
        ]);
    }

    public function update(StorePartnerCategoryRequest $request, PartnerCategory $partnerCategory)
    {
        $partnerCategory->update($request->validated());
        return redirect()->route('admin.partner-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(PartnerCategory $partnerCategory)
    {
        if ($partnerCategory->children()->count() > 0) {
            return redirect()->route('admin.partner-categories.index')
                ->with('error', 'Cannot delete a category with sub-categories.');
        }

        if ($partnerCategory->partners()->count() > 0) {
            return redirect()->route('admin.partner-categories.index')
                ->with('error', 'Cannot delete a category with assigned partners.');
        }

        $partnerCategory->delete();
        return redirect()->route('admin.partner-categories.index')->with('success', 'Category removed.');
    }
}
