<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\UpdateFromDTO;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use App\DTO\Team\UpdateTeamMemberDTO;
use App\DTO\Team\CreateTeamMemberDTO;

class TeamMemberRepository implements TeamMemberRepositoryInterface
{
    use UpdateFromDTO;

    protected function queryByBothIds(int $teamId, int $userId): Builder
    {
        return TeamMember::query()
            ->where('team_id', '=', $teamId)
            ->where('user_id', '=', $userId);
    }

    public function getByTeamId(int $teamId): Collection
    {
        return TeamMember::where('team_id', '=', $teamId)->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return TeamMember::where('user_id', '=', $userId)->get();
    }

    public function getMemberByBothIds(int $teamId, int $userId): ?TeamMember
    {
        return $this->queryByBothIds($teamId, $userId)->first();
    }

    public function userIsMember(int $teamId, int $userId): bool
    {
        return $this->queryByBothIds($teamId, $userId)->exists();
    }

    public function userIsModerator(int $teamId, int $userId): bool
    {
        $membership = $this->getMemberByBothIds($teamId, $userId);

        if (empty($membership)) {
            return false;
        }

        return $membership->is_moderator;
    }

    public function addMember(CreateTeamMemberDTO $dto): void
    {
        TeamMember::create(
            $dto->toArray()
        );
    }

    public function updateMember(TeamMember $teamMember, UpdateTeamMemberDTO $dto): void
    {
        $this->updateFromDto($teamMember, $dto);
    }

    public function removeMember(TeamMember $teamMember): void
    {
        $teamMember->delete();
    }
}
