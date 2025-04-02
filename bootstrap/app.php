<?php

use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\XssProtection;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'locale' => LocaleMiddleware::class,
            'xss' => XssProtection::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Object not found.',
                ], 404);
            }

        });
        $exceptions->render(function (AuthorizationException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized.',
                ], 404);
            }

        });
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Forbidden',
                ], 403);
            }
        });
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {

            if ($request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Too Many Attempts try again later .',
                ], 429);
            }
        });
        if (app()->environment() == 'production') {
            $exceptions->render(function (Exception $e, Request $request) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong.',
                    ], 500);
                }
            });
        }

    })
    ->create();
