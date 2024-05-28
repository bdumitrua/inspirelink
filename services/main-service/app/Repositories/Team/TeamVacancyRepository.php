<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;

class TeamVacancyRepository implements TeamVacancyRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getByTeamId(int $teamId): Collection
    {
        $teamVacancyIds = TeamVacancy::where('team_id', '=', $teamId)->get()
            ->pluck('id')->toArray();

        return $this->getByIds($teamVacancyIds);
    }

    public function getByIds(array $teamVacancyIds): Collection
    {
        $vacancies = $this->getCachedCollection($teamVacancyIds, function ($id) {
            return $this->getById($id);
        });

        return $vacancies->sortByDesc('created_at')->values();
    }

    public function getById(int $teamVacancyId): ?TeamVacancy
    {
        $cacheKey = $this->getVacancyCacheKey($teamVacancyId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_VACANCY_DATA, function () use ($teamVacancyId) {
            return  TeamVacancy::find($teamVacancyId);
        });
    }

    public function create(CreateTeamVacancyDTO $dto): TeamVacancy
    {
        $newVacancy = TeamVacancy::create(
            $dto->toArray()
        );

        return $newVacancy;
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $dto): void
    {
        $this->updateFromDto($teamVacancy, $dto);
        $this->clearVacancyCache($teamVacancy->id);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        $teamVacancyId = $teamVacancy->id;

        $teamVacancy->delete();
        $this->clearVacancyCache($teamVacancyId);
    }

    protected function getVacancyCacheKey(int $teamVacancyId): string
    {
        return CACHE_KEY_TEAM_VACANCY_DATA . $teamVacancyId;
    }

    protected function clearVacancyCache(int $teamVacancyId): void
    {
        $this->clearCache($this->getVacancyCacheKey($teamVacancyId));
    }
}
