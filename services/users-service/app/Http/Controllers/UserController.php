<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthConfirmCodeRequest;
use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\AuthReset;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->userService->index();
        });
    }
}
