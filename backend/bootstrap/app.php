<?php

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Application;
use App\Exceptions\LikeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            \App\Http\Middleware\ForceJsonResponse::class,
            \App\Http\Middleware\Authenticate::class . ':api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json([
                'error: ' . $e->getMessage(),
            ], 404);
        });

        $exceptions->render(function (LikeException $e) {
            return response()->json([
                'error: ' . $e->getMessage(),
            ], 409);
        });

        $exceptions->render(function (HttpException $e) {
            return response()->json([
                'error: ' . $e->getMessage()
            ], $e->getStatusCode());
        });

        $exceptions->report(function (LikeException $e) {
            return false;
        });
    })->create();