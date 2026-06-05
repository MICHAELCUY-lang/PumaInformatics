<?php

namespace App\Services;

use App\Repositories\Contracts\CandidateRepositoryInterface;
use App\DTOs\CandidateData;

class CandidateService
{
    public function __construct(
        protected CandidateRepositoryInterface $repository
    ) {}

    public function createCandidate(CandidateData $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function updateCandidate(int $id, CandidateData $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function deleteCandidate(int $id)
    {
        return $this->repository->delete($id);
    }
}
