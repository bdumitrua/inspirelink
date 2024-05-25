<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    private $teamMemberService;

    public function __construct(TeamMemberServiceInterface $teamMemberService)
    {
        $this->teamMemberService = $teamMemberService;
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamMemberService->team($team->id);
        });
    }

    public function create(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamMemberService->create($team->id);
        });
    }

    public function delete(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamMemberService->delete($team->id);
        });
    }
}