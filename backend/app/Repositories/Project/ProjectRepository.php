<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Models\Project;
use App\DTO\Project\UpdateProjectDTO;
use App\DTO\Project\CreateProjectDTO;

class ProjectRepository implements ProjectRepositoryInterface
{
    use Updateable;

    public function getAll(): Collection
    {
        return Project::get();
    }

    public function search(string $query): Collection
    {
        return Project::search($query);
    }

    public function getById(int $projectId): ?Project
    {
        Log::debug('Getting project data by id', [
            'projectId' => $projectId
        ]);

        return Project::find($projectId);
    }

    public function getByIds(array $projectsIds): Collection
    {
        Log::debug('Getting projects data by ids', [
            'projectsIds' => $projectsIds
        ]);

        return Project::whereIn('id', $projectsIds);
    }

    public function getByAuthorId(int $authorId): Collection
    {
        Log::debug('Getting projects data by authorId', [
            'authorId' => $authorId
        ]);

        return Project::fromUser($authorId)->get();
    }

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug('Getting projects data by teamId', [
            'teamId' => $teamId
        ]);

        return Project::fromTeam($teamId)->get();
    }

    public function create(CreateProjectDTO $createProjectDTO): Project
    {
        Log::debug('Creating project from dto', [
            'createProjectDTO' => $createProjectDTO->toArray()
        ]);

        $newProject = Project::create(
            $createProjectDTO->toArray()
        );

        Log::debug('Succesfully created project from dto', [
            'newProject' => $newProject->toArray()
        ]);

        return $newProject;
    }

    public function update(Project $project, UpdateProjectDTO $updateProjectDTO): void
    {
        Log::debug('Updating project from dto', [
            'project id' => $project->id,
            'updateProjectDTO' => $updateProjectDTO->toArray()
        ]);

        $this->updateFromDto($project, $updateProjectDTO);

        Log::debug('Succesfully updated project from dto', [
            'project id' => $project->id,
        ]);
    }

    public function delete(Project $project): void
    {
        $projectId = $project->id;

        Log::debug('Deleting project', [
            'projectId' => $projectId,
        ]);

        $project->delete();

        Log::debug('Succesfully deleted project', [
            'projectId' => $projectId,
        ]);
    }
}
