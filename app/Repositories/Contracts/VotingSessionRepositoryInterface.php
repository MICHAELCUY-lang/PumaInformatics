<?php

namespace App\Repositories\Contracts;

interface VotingSessionRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithCounts(int $perPage = 15, array $filters = []);
    public function findWithCandidates(int $id);
}
