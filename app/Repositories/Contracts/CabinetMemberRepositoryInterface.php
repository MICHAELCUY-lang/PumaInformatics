<?php

namespace App\Repositories\Contracts;

interface CabinetMemberRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15);
    public function getActiveMembersByTerm(string $termYear);
}
