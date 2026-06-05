<?php

namespace App\Repositories\Contracts;

interface PartnerRepositoryInterface extends BaseRepositoryInterface
{
    public function paginatePartners(int $perPage = 15);
    public function getFeaturedPartners();
}
