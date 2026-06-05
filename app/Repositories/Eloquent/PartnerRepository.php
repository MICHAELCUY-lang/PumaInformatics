<?php

namespace App\Repositories\Eloquent;

use App\Models\Partner;
use App\Repositories\Contracts\PartnerRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PartnerRepository extends BaseRepository implements PartnerRepositoryInterface
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(Partner $model, \App\Services\CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    public function paginatePartners(int $perPage = 15)
    {
        return $this->model->with(['category'])
            ->orderBy('is_featured', 'desc')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($perPage);
    }

    public function getFeaturedPartners()
    {
        return $this->cacheService->rememberWithLock('partners', 'featured', now()->addHours(24), function () {
            return $this->model->with(['category', 'media'])
                ->where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();
        });
    }
}
