<?php

namespace App\Services;

use App\DTOs\CabinetMemberData;
use App\Repositories\Contracts\CabinetMemberRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CabinetMemberService extends BaseService
{
    protected CabinetMemberRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(CabinetMemberRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginateMembers()
    {
        return $this->repository->paginateWithRelations();
    }

    public function createMember(CabinetMemberData $data)
    {
        $member = $this->repository->create($data->toArray());
        $this->invalidateCache();
        return $member;
    }

    public function updateMember(int $id, CabinetMemberData $data)
    {
        $member = $this->repository->update($id, $data->toArray());
        $this->invalidateCache();
        return $member;
    }

    public function deleteMember(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('cabinet');
    }
}
