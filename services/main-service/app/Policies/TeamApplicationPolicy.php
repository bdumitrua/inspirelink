<?php

namespace App\Policies;

use App\Models\TeamApplication;
use App\Models\User;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class TeamApplicationPolicy
{
    protected $teamMemberRepository;
    protected $teamApplicationRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
        TeamApplicationRepositoryInterface $teamApplicationRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->teamApplicationRepository = $teamApplicationRepository;
    }

    /**
     * Determine whether the user can view the application.
     * 
     * @see VIEW_TEAM_APPLICATIONS_GATE
     */
    public function viewTeamApplication(User $user, TeamApplication $teamApplication): bool
    {
        if ($user->id === $teamApplication->user_id) {
            return true;
        }

        if ($this->teamMemberRepository->userIsModerator($teamApplication->team_id, $user->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can apply to the vacancy.
     * 
     * @see APPLY_TO_VACANCY_GATE
     */
    public function applyToVacancy(User $user, int $teamId, int $vacancyId): bool
    {
        if ($this->teamMemberRepository->userIsMember($teamId, $user->id)) {
            return Response::denyWithStatus(403);
        }

        $alreadyApplied = $this->teamApplicationRepository->userAppliedToVacancy(
            $user->id,
            $vacancyId
        );

        if ($alreadyApplied) {
            return Response::denyWithStatus(400);
        }

        return true;
    }

    /**
     * Determine whether the user can update the application.
     * 
     * @see UPDATE_TEAM_APPLICATION_GATE
     */
    public function updateTeamApplication(User $user, TeamApplication $teamApplication): bool
    {
        return $this->teamMemberRepository->userIsModerator($teamApplication->team_id, $user->id);
    }

    /**
     * Determine whether the user can delete the application.
     * 
     * @see DELETE_TEAM_APPLICATION_GATE
     */
    public function deleteTeamApplication(User $user, TeamApplication $teamApplication): bool
    {
        return $user->id === $teamApplication->user_id;
    }
}