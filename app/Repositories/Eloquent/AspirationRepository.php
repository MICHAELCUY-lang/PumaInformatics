<?php

namespace App\Repositories\Eloquent;

use App\Models\Aspiration;
use App\Repositories\Contracts\AspirationRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AspirationRepository extends BaseRepository implements AspirationRepositoryInterface
{
    public function __construct(Aspiration $model)
    {
        parent::__construct($model);
    }

    public function paginateAspirations(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['category']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
