<?php

namespace App\Repositories\Eloquent;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(Article $model, \App\Services\CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    public function paginateWithAuthor(int $perPage = 15)
    {
        return $this->model->with('author')->latest()->paginate($perPage);
    }

    public function getLatest(int $limit = 3)
    {
        return $this->cacheService->rememberWithLock('news', "latest.{$limit}", now()->addHours(24), function () use ($limit) {
            return $this->model->with('author', 'media')
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->latest('published_at')
                ->take($limit)
                ->get();
        });
    }
}
