<?php

namespace App\Services;

use App\DTOs\PartnerData;
use App\Repositories\Contracts\PartnerRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PartnerService extends BaseService
{
    protected PartnerRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(PartnerRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginatePartners()
    {
        return $this->repository->paginatePartners();
    }

    public function createPartner(PartnerData $data)
    {
        $partner = $this->repository->create($data->toArray());
        $this->invalidateCache();
        return $partner;
    }

    public function updatePartner(int $id, PartnerData $data)
    {
        $partner = $this->repository->update($id, $data->toArray());
        $this->invalidateCache();
        return $partner;
    }

    public function deletePartner(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('partners');
    }
}
