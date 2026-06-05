<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\DTOs\RoleData;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function index(Request $request)
    {
        $this->authorize('manage.roles');
        
        $filters = $request->only(['search']);
        $roles = $this->roleRepository->paginateWithCounts(15, $filters);
        
        return view('admin.roles.index', compact('roles', 'filters'));
    }

    public function create()
    {
        $this->authorize('manage.roles');
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0] ?? 'general';
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request)
    {
        $roleData = RoleData::fromArray($request->validated());
        
        $this->roleService->createRole($roleData);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role)
    {
        $this->authorize('manage.roles');
        $role = $this->roleRepository->findWithPermissions($role->id);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $this->authorize('manage.roles');
        $role = $this->roleRepository->findWithPermissions($role->id);
        
        $permissions = Permission::all()->groupBy(function($perm) {
            return explode('.', $perm->name)[0] ?? 'general';
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $protectedRoles = ['Super Admin', 'Admin', 'User'];
        if (in_array($role->name, $protectedRoles) && $request->name !== $role->name) {
            return back()->with('error', "Cannot rename system-critical role: {$role->name}");
        }

        $roleData = RoleData::fromArray($request->validated());
        
        $this->roleService->updateRole($role->id, $roleData);
        
        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $this->authorize('manage.roles');
        
        try {
            $this->roleService->deleteRole($role->id);
            return redirect()->route('admin.roles.index')
                ->with('success', 'Role deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
