<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\ActionsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AuthTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $actions = (array) ActionsResource::collection([
            [
                "GetAuthorizedUserData",
                "users.index"
            ],
            [
                "Logout",
                "auth.logout"
            ],
        ]);

        return [
            'tokenType' => 'bearer',
            'accessToken' => $this->resource,
            'expiresIn' => Auth::factory()->getTTL() * 60,
            'actions' => $actions
        ];
    }
}
