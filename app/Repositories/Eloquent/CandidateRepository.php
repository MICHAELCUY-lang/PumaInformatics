<?php

namespace App\Repositories\Eloquent;

use App\Models\Candidate;
use App\Repositories\Contracts\CandidateRepositoryInterface;

class CandidateRepository extends BaseRepository implements CandidateRepositoryInterface
{
    public function __construct(Candidate $model)
    {
        parent::__construct($model);
    }
}
