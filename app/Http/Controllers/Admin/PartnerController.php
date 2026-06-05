<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PartnerService;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\DTOs\PartnerData;
use App\Models\Partner;
use App\Models\PartnerCategory;
use App\Http\Requests\Admin\UpdatePartnerRequest;

class PartnerController extends Controller
{
    protected PartnerService $service;

    public function __construct(PartnerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $partners = $this->service->paginatePartners();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $categories = PartnerCategory::orderBy('order')->get();
        return view('admin.partners.create', compact('categories'));
    }

    public function store(StorePartnerRequest $request)
    {
        $data = PartnerData::fromArray($request->validated());
        $partner = $this->service->createPartner($data);

        if ($request->hasFile('logo')) {
            $partner->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully.');
    }

    public function edit(Partner $partner)
    {
        $categories = PartnerCategory::orderBy('order')->get();
        return view('admin.partners.edit', compact('partner', 'categories'));
    }

    public function update(UpdatePartnerRequest $request, Partner $partner)
    {
        $data = PartnerData::fromArray($request->validated());
        $this->service->updatePartner($partner->id, $data);

        if ($request->hasFile('logo')) {
            $partner->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    public function destroy(Partner $partner)
    {
        $this->service->deletePartner($partner->id);
        return redirect()->route('admin.partners.index')->with('success', 'Partner removed.');
    }
}
