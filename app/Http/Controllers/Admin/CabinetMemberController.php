<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CabinetMemberService;
use App\Http\Requests\Admin\StoreCabinetMemberRequest;
use App\DTOs\CabinetMemberData;
use App\Models\CabinetMember;
use App\Models\CabinetDepartment;
use App\Models\Cabinet;
use App\Http\Requests\Admin\UpdateCabinetMemberRequest;

class CabinetMemberController extends Controller
{
    protected CabinetMemberService $service;

    public function __construct(CabinetMemberService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $members = $this->service->paginateMembers();
        return view('admin.cabinet-members.index', compact('members'));
    }

    public function create()
    {
        $departments = CabinetDepartment::orderBy('name')->get();
        $cabinets = Cabinet::orderBy('term_year')->get();
        return view('admin.cabinet-members.create', compact('departments', 'cabinets'));
    }

    public function store(StoreCabinetMemberRequest $request)
    {
        $data = CabinetMemberData::fromArray($request->validated());
        $member = $this->service->createMember($data);

        if ($request->hasFile('portrait')) {
            $member->addMediaFromRequest('portrait')->toMediaCollection('portrait');
        }

        return redirect()->route('admin.cabinet-members.index')->with('success', 'Member created.');
    }

    public function edit(CabinetMember $cabinetMember)
    {
        $departments = CabinetDepartment::orderBy('name')->get();
        $cabinets = Cabinet::orderBy('term_year')->get();
        return view('admin.cabinet-members.edit', compact('cabinetMember', 'departments', 'cabinets'));
    }

    public function update(UpdateCabinetMemberRequest $request, CabinetMember $cabinetMember)
    {
        $data = CabinetMemberData::fromArray($request->validated());
        $this->service->updateMember($cabinetMember->id, $data);

        if ($request->hasFile('portrait')) {
            $cabinetMember->addMediaFromRequest('portrait')->toMediaCollection('portrait');
        }

        return redirect()->route('admin.cabinet-members.index')->with('success', 'Member updated.');
    }

    public function destroy(CabinetMember $cabinetMember)
    {
        $this->service->deleteMember($cabinetMember->id);
        return redirect()->route('admin.cabinet-members.index')->with('success', 'Member removed.');
    }
}
