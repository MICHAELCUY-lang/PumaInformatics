<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\DTOs\UserData;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function createUser(UserData $data)
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create($data->toArray());
            
            if (!empty($data->roles)) {
                $user->syncRoles($data->roles);
            }
            
            return $user;
        });
    }

    public function updateUser(int $id, UserData $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $roles = $data->roles ?: [];

            // Don't let an operator strip their own Super Admin role and lock
            // the whole organisation out of the panel.
            if ($this->isSelf($id)
                && auth()->user()->hasRole('Super Admin')
                && ! in_array('Super Admin', $roles, true)) {
                throw new \Exception('You cannot remove your own Super Admin role.');
            }

            $user = $this->userRepository->update($id, $data->toArray());
            $user->syncRoles($roles);

            return $user;
        });
    }

    public function updateStatus(int $id, string $status)
    {
        $user = $this->userRepository->findById($id);

        if ($this->isSelf($id) && $status !== 'active') {
            throw new \Exception('You cannot suspend or deactivate your own account.');
        }

        if ($user->hasRole('Super Admin') && $status !== 'active') {
            throw new \Exception('Cannot suspend or deactivate a Super Admin.');
        }

        return $this->userRepository->updateStatus($id, $status);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->findById($id);

        if ($this->isSelf($id)) {
            throw new \Exception('You cannot delete your own account.');
        }

        if ($user->hasRole('Super Admin')) {
            throw new \Exception('Cannot delete Super Admin');
        }

        return $this->userRepository->delete($id);
    }

    protected function isSelf(int $id): bool
    {
        return auth()->check() && auth()->id() === $id;
    }
}
