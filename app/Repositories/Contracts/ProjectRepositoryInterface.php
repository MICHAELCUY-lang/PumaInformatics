<?php

namespace App\Repositories\Contracts;

interface ProjectRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateProjects(int $perPage = 15);
    public function getFeatured(int $limit = 3);
}
