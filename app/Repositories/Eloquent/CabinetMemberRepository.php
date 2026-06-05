<?php

namespace App\Repositories\Eloquent;

use App\Models\CabinetMember;
use App\Repositories\Contracts\CabinetMemberRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class CabinetMemberRepository extends BaseRepository implements CabinetMemberRepositoryInterface
{
    protected \App\Services\CacheService $cacheService;

    public function __construct(CabinetMember $model, \App\Services\CacheService $cacheService)
    {
        parent::__construct($model);
        $this->cacheService = $cacheService;
    }

    public function paginateWithRelations(int $perPage = 15)
    {
        return $this->model->with(['department', 'cabinet'])
            ->orderBy('term_year', 'desc')
            ->orderBy('role_hierarchy_level', 'asc')
            ->paginate($perPage);
    }

    public function getActiveMembersByTerm(string $termYear)
    {
        return $this->cacheService->rememberWithLock('cabinet', "cabinet.{$termYear}", now()->addHours(24), function () use ($termYear) {
            return $this->model->with(['department', 'media'])
                ->where('term_year', $termYear)
                ->where('is_active', true)
                ->orderBy('role_hierarchy_level', 'asc')
                ->get();
        });
    }
}
