<?php

namespace App\Repositories\Contracts;

interface EventRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 15);
    public function getUpcomingEvents(int $limit = 4);
}
