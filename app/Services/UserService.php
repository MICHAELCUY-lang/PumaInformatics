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
            $user = $this->userRepository->update($id, $data->toArray());
            
            if (!empty($data->roles)) {
                $user->syncRoles($data->roles);
            } else {
                $user->syncRoles([]);
            }
            
            return $user;
        });
    }

    public function updateStatus(int $id, string $status)
    {
        $user = $this->userRepository->findById($id);
        
        if ($user->hasRole('Super Admin') && $status !== 'active') {
            throw new \Exception('Cannot suspend or deactivate a Super Admin.');
        }

        return $this->userRepository->updateStatus($id, $status);
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->findById($id);
        
        if ($user->hasRole('Super Admin')) {
            throw new \Exception('Cannot delete Super Admin');
        }
        
        return $this->userRepository->delete($id);
    }
}
