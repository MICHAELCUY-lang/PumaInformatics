<?php

namespace App\Services;

use App\DTOs\ArticleData;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Support\Str;

class ArticleService extends BaseService
{
    protected ArticleRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(ArticleRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginateArticles()
    {
        return $this->repository->paginateWithAuthor();
    }

    public function createArticle(ArticleData $data, int $authorId)
    {
        $payload = $data->toArray();
        $payload['author_id'] = $authorId;
        
        // Calculate reading time
        $payload['reading_time_minutes'] = $this->calculateReadingTime($payload['content'] ?? '');

        $article = $this->repository->create($payload);
        $this->invalidateCache();
        return $article;
    }

    public function updateArticle(int $id, ArticleData $data)
    {
        $payload = $data->toArray();
        
        if (isset($payload['content'])) {
            $payload['reading_time_minutes'] = $this->calculateReadingTime($payload['content']);
        }

        $article = $this->repository->update($id, $payload);
        $this->invalidateCache();
        return $article;
    }

    public function deleteArticle(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('news');
    }

    /**
     * Calculate estimated reading time based on 250 words per minute.
     */
    protected function calculateReadingTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));
        $minutes = ceil($wordCount / 250);
        
        return max(1, (int) $minutes);
    }
}
