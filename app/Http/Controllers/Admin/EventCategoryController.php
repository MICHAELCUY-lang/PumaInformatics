<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Http\Requests\Admin\StoreEventCategoryRequest;

class EventCategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategory::with('parent')->orderBy('order')->get();
        return view('admin.event-categories.index', compact('categories'));
    }

    public function store(StoreEventCategoryRequest $request)
    {
        EventCategory::create($request->validated());
        return redirect()->route('admin.event-categories.index')->with('success', 'Category created.');
    }

    public function edit(EventCategory $eventCategory)
    {
        $categories = EventCategory::where('id', '!=', $eventCategory->id)->orderBy('order')->get();
        return view('admin.event-categories.index', [
            'categories' => EventCategory::with('parent')->orderBy('order')->get(),
            'editing' => $eventCategory,
            'parentOptions' => $categories,
        ]);
    }

    public function update(StoreEventCategoryRequest $request, EventCategory $eventCategory)
    {
        $eventCategory->update($request->validated());
        return redirect()->route('admin.event-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(EventCategory $eventCategory)
    {
        if ($eventCategory->children()->count() > 0) {
            return redirect()->route('admin.event-categories.index')
                ->with('error', 'Cannot delete a category with sub-categories.');
        }

        $eventCategory->delete();
        return redirect()->route('admin.event-categories.index')->with('success', 'Category removed.');
    }
}
