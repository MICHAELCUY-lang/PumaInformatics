<?php

namespace App\Services;

use App\DTOs\NavigationData;
use App\Repositories\Contracts\NavigationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class NavigationService extends BaseService
{
    protected NavigationRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(NavigationRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function getTree()
    {
        return $this->cacheService->rememberWithLock('navigations', 'all', now()->addYears(1), function () {
            return $this->repository->getTree();
        });
    }

    public function createNavigation(NavigationData $data)
    {
        $navigation = $this->repository->create($data->toArray());
        $this->invalidateCache();
        return $navigation;
    }

    public function updateNavigation(int $id, NavigationData $data)
    {
        $navigation = $this->repository->update($id, $data->toArray());
        $this->invalidateCache();
        return $navigation;
    }

    public function deleteNavigation(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    public function reorder(array $items)
    {
        $this->repository->updateOrder($items);
        $this->invalidateCache();
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('navigations');
    }
}
