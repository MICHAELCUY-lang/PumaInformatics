<?php

namespace App\Services;

use App\DTOs\AspirationData;
use App\Repositories\Contracts\AspirationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AspirationService extends BaseService
{
    protected AspirationRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(AspirationRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginateAspirations(array $filters = [])
    {
        return $this->repository->paginateAspirations(15, $filters);
    }

    public function createAspiration(AspirationData $data)
    {
        $aspiration = $this->repository->create($data->toArray());
        $this->invalidateCache();
        return $aspiration;
    }

    public function updateStatus(int $id, string $status, ?string $adminNotes = null)
    {
        $aspiration = $this->repository->update($id, [
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);
        $this->invalidateCache();
        return $aspiration;
    }

    public function deleteAspiration(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('aspirations');
    }
}
