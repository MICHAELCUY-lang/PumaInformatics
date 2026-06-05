<?php

namespace App\Services;

use App\DTOs\EventData;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class EventService extends BaseService
{
    protected EventRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(EventRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginateEvents()
    {
        return $this->repository->paginateWithRelations();
    }

    public function createEvent(EventData $data)
    {
        $event = $this->repository->create($data->toArray());
        
        if ($data->tags) {
            $event->tags()->sync($data->tags);
        }

        $this->invalidateCache();
        return $event;
    }

    public function updateEvent(int $id, EventData $data)
    {
        $event = $this->repository->update($id, $data->toArray());
        
        if ($data->tags !== null) {
            $event->tags()->sync($data->tags);
        }

        $this->invalidateCache();
        return $event;
    }

    public function deleteEvent(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('events');
    }
}
