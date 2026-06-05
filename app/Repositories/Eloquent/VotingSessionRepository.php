<?php

namespace App\Repositories\Eloquent;

use App\Models\VotingSession;
use App\Repositories\Contracts\VotingSessionRepositoryInterface;

class VotingSessionRepository extends BaseRepository implements VotingSessionRepositoryInterface
{
    public function __construct(VotingSession $model)
    {
        parent::__construct($model);
    }

    public function paginateWithCounts(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->withCount(['candidates', 'votes'])->latest();

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($perPage);
    }

    public function findWithCandidates(int $id)
    {
        return $this->model->with(['candidates' => function($query) {
            $query->withCount('votes')->orderBy('order');
        }])->withCount('votes')->findOrFail($id);
    }
}
