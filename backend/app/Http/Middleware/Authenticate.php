<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use App\Exceptions\InvalidTokenException;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        // Проверка даты инвалидации токена
        $user = Auth::user();
        if ($user->token_invalid_before && JWTAuth::getPayload(JWTAuth::getToken())->get('iat') < $user->token_invalid_before->timestamp) {
            Auth::logout(true);
            throw new InvalidTokenException();
        }

        return $next($request);
    }

    protected function redirectTo(Request $request)
    {
        return 'auth.login';
    }
}
