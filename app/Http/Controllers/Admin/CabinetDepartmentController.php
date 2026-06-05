<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CabinetDepartment;
use App\Http\Requests\Admin\StoreCabinetDepartmentRequest;

class CabinetDepartmentController extends Controller
{
    public function index()
    {
        $departments = CabinetDepartment::withCount('members')->orderBy('order')->get();
        return view('admin.cabinet-departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.cabinet-departments.create');
    }

    public function store(StoreCabinetDepartmentRequest $request)
    {
        CabinetDepartment::create($request->validated());
        return redirect()->route('admin.cabinet-departments.index')->with('success', 'Department created.');
    }

    public function edit(CabinetDepartment $cabinetDepartment)
    {
        return view('admin.cabinet-departments.edit', compact('cabinetDepartment'));
    }

    public function update(StoreCabinetDepartmentRequest $request, CabinetDepartment $cabinetDepartment)
    {
        $cabinetDepartment->update($request->validated());
        return redirect()->route('admin.cabinet-departments.index')->with('success', 'Department updated.');
    }

    public function destroy(CabinetDepartment $cabinetDepartment)
    {
        if ($cabinetDepartment->members()->count() > 0) {
            return redirect()->route('admin.cabinet-departments.index')
                ->with('error', 'Cannot delete a department with active members. Reassign members first.');
        }

        $cabinetDepartment->delete();
        return redirect()->route('admin.cabinet-departments.index')->with('success', 'Department removed.');
    }
}
