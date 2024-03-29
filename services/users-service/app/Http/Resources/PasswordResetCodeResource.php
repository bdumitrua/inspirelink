<?php

namespace App\Http\Resources;

use App\Http\Resources\ActionsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PasswordResetCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $resetId = $this->resource;

        $actions = (array) ActionsResource::collection([
            [
                "ConfirmCode",
                "confirmPasswordReset",
                ['authReset' => $resetId],
            ]
        ]);

        return [
            'resetId' => $resetId,
            'actions' => $actions,
        ];
    }
}
