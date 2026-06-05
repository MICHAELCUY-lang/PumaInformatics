<?php

namespace App\Repositories\Contracts;

use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithCounts(int $perPage = 15, array $filters = []);
    public function findWithPermissions(int $id);
}
