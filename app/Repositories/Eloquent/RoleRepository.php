<?php

namespace App\Repositories\Eloquent;

use Spatie\Permission\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function paginateWithCounts(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->withCount('users')->latest();

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->paginate($perPage);
    }

    public function findWithPermissions(int $id)
    {
        return $this->model->with('permissions')->findOrFail($id);
    }
}
