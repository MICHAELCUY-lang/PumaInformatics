<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithRoles(int $perPage = 15, array $filters = []);
    public function findWithRoles(int $id);
    public function updateStatus(int $id, string $status);
}
