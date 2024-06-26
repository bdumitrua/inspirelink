<?php

namespace App\DTO\Team;

class CreateTeamMemberDTO
{
    public int $userId;
    public int $teamId;
    public bool $isModerator;

    public function __construct(int $userId = 0, int $teamId = 0, bool $isModerator = false)
    {
        $this->userId = $userId;
        $this->teamId = $teamId;
        $this->isModerator = $isModerator;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'team_id' => $this->teamId,
            'is_moderator' => $this->isModerator,
        ];
    }

    public function setTeamId(int $teamId): void
    {
        $this->teamId = $teamId;
    }
}
