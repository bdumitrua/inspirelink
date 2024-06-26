<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\ActionsResource;

class TeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userData = (new UserDataResource($this->userData))->resolve();

        $actions = $this->prepareActions();

        return [
            'teamId' => $this->team_id,
            'userId' => $this->user_id,
            'userData' => $userData,
            'isModerator' => $this->is_moderator,
            'about' => $this->about,
            'canChange' => $this->canChange,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'actions' => $actions,
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->canChange) {
            $actions[] = [
                "DeleteUserMembership",
                "teams.members.delete",
                [
                    "team" => $this->team_id,
                    'user' => $this->user_id
                ]
            ];
        }

        return (array) ActionsResource::collection($actions);
    }
}
