<?php

namespace App\Repositories\Contracts;

interface AspirationRepositoryInterface extends BaseRepositoryInterface
{
    public function paginateAspirations(int $perPage = 15, array $filters = []);
}
