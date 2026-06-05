<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\DTOs\RoleData;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function __construct(
        protected RoleRepositoryInterface $roleRepository
    ) {}

    public function createRole(RoleData $data)
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create($data->toArray());
            
            if (!empty($data->permissions)) {
                $role->syncPermissions($data->permissions);
            }
            
            return $role;
        });
    }

    public function updateRole(int $id, RoleData $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->roleRepository->update($id, $data->toArray());
            
            if (!empty($data->permissions)) {
                $role->syncPermissions($data->permissions);
            } else {
                $role->syncPermissions([]);
            }
            
            return $role;
        });
    }

    public function deleteRole(int $id)
    {
        $role = $this->roleRepository->findById($id);
        
        $protectedRoles = ['Super Admin', 'Admin', 'User'];
        
        if (in_array($role->name, $protectedRoles)) {
            throw new \Exception("Cannot delete system-critical role: {$role->name}");
        }
        
        if ($role->users()->count() > 0) {
            throw new \Exception("Cannot delete role: {$role->name}. It is assigned to active users.");
        }
        
        return $this->roleRepository->delete($id);
    }
}
