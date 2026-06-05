<?php

namespace App\Repositories\Eloquent;

use App\Models\Project;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProjectRepository extends BaseRepository implements ProjectRepositoryInterface
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(Project $model, \App\Services\CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    public function paginateProjects(int $perPage = 15)
    {
        return $this->model->with(['category', 'technologies'])->latest()->paginate($perPage);
    }

    public function getFeatured(int $limit = 3)
    {
        return $this->cacheService->rememberWithLock('projects', "featured.{$limit}", now()->addHours(24), function () use ($limit) {
            return $this->model->with(['category', 'technologies', 'media'])
                ->where('status', 'published')
                ->where('is_featured', true)
                ->latest()
                ->take($limit)
                ->get();
        });
    }
}
