<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NavigationService;
use App\Http\Requests\Admin\StoreNavigationRequest;
use App\Http\Requests\Admin\UpdateNavigationRequest;
use App\DTOs\NavigationData;
use App\Models\Navigation;

class NavigationController extends Controller
{
    protected NavigationService $service;

    public function __construct(NavigationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $navigations = $this->service->getTree();
        return view('admin.navigations.index', compact('navigations'));
    }

    public function store(StoreNavigationRequest $request)
    {
        $data = NavigationData::fromArray($request->validated());
        $this->service->createNavigation($data);

        return redirect()->route('admin.navigations.index')->with('success', 'Navigation created successfully.');
    }

    public function create()
    {
        return redirect()->route('admin.navigations.index');
    }

    public function edit(Navigation $navigation)
    {
        $navigations = $this->service->getTree();
        return view('admin.navigations.index', compact('navigations', 'navigation'));
    }

    public function update(UpdateNavigationRequest $request, Navigation $navigation)
    {
        $data = NavigationData::fromArray($request->validated());
        $this->service->updateNavigation($navigation->id, $data);

        return redirect()->route('admin.navigations.index')->with('success', 'Navigation updated successfully.');
    }

    public function destroy(Navigation $navigation)
    {
        // Don't delete if it has children
        if ($navigation->children()->count() > 0) {
            return redirect()->route('admin.navigations.index')
                ->with('error', 'Cannot delete a menu item with sub-items. Remove children first.');
        }

        $this->service->deleteNavigation($navigation->id);

        return redirect()->route('admin.navigations.index')->with('success', 'Navigation item removed.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'exists:navigations,id'],
            'items.*.order' => ['required', 'integer'],
            'items.*.parent_id' => ['nullable', 'exists:navigations,id'],
        ]);

        $this->service->reorder($request->items);
        return response()->json(['message' => 'Order updated successfully.']);
    }
}

