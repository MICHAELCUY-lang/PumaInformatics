<?php

namespace App\Services;

use App\DTOs\ProjectData;
use App\Repositories\Contracts\ProjectRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProjectService extends BaseService
{
    protected ProjectRepositoryInterface $repository;
    protected \App\Services\CacheService $cacheService;

    public function __construct(ProjectRepositoryInterface $repository, \App\Services\CacheService $cacheService)
    {
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    public function paginateProjects()
    {
        return $this->repository->paginateProjects();
    }

    public function createProject(ProjectData $data)
    {
        $project = $this->repository->create($data->toArray());
        
        if (!empty($data->technologies)) {
            $project->technologies()->sync($data->technologies);
        }

        $this->invalidateCache();
        return $project;
    }

    public function updateProject(int $id, ProjectData $data)
    {
        $project = $this->repository->update($id, $data->toArray());
        
        if (isset($data->technologies)) {
            $project->technologies()->sync($data->technologies);
        }

        $this->invalidateCache();
        return $project;
    }

    public function deleteProject(int $id)
    {
        $result = $this->repository->delete($id);
        $this->invalidateCache();
        return $result;
    }

    protected function invalidateCache(): void
    {
        $this->cacheService->flush('projects');
    }
}
