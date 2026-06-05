<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginateWithRoles(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with('roles')->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        return $query->paginate($perPage);
    }

    public function findWithRoles(int $id)
    {
        return $this->model->with(['roles', 'permissions'])->findOrFail($id);
    }

    public function updateStatus(int $id, string $status)
    {
        $user = $this->findById($id);
        $user->status = $status;
        $user->save();
        return $user;
    }
}
