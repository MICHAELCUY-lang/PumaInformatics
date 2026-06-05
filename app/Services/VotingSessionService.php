<?php

namespace App\Services;

use App\Repositories\Contracts\VotingSessionRepositoryInterface;
use App\DTOs\VotingSessionData;

class VotingSessionService
{
    public function __construct(
        protected VotingSessionRepositoryInterface $repository
    ) {}

    public function createSession(VotingSessionData $data)
    {
        return $this->repository->create($data->toArray());
    }

    public function updateSession(int $id, VotingSessionData $data)
    {
        return $this->repository->update($id, $data->toArray());
    }

    public function deleteSession(int $id)
    {
        return $this->repository->delete($id);
    }
}
