<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;

class EventRepository extends BaseRepository implements EventRepositoryInterface
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(Event $model, \App\Services\CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    public function paginateWithRelations(int $perPage = 15)
    {
        return $this->model->with(['category'])
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);
    }

    public function getUpcomingEvents(int $limit = 5)
    {
        return $this->cacheService->rememberWithLock('events', "upcoming.{$limit}", now()->addHours(24), function () use ($limit) {
            return $this->model->with(['category', 'media'])
                ->where('status', 'published')
                ->where('start_date', '>=', now())
                ->orderBy('start_date', 'asc')
                ->take($limit)
                ->get();
        });
    }
}
