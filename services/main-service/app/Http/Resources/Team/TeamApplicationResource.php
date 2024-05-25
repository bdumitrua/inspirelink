<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\User\UserDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userData = (new UserDataResource($this->userData))->resolve();
        $vacancyData = (new TeamVacancyResource($this->vacancyData))->resolve();
        $teamData = (new TeamDataResource($this->teamData))->resolve();

        return [
            'text' => $this->text,
            'status' => $this->status,
            'userId' => $this->user_id,
            'userData' => $userData,
            'vacancyId' => $this->vacancy_id,
            'vacancyData' => $vacancyData,
            'teamId' => $this->team_id,
            'teamData' => $teamData,
        ];
    }
}