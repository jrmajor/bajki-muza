<?php

use App\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Sentry\Laravel\Integration as Sentry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/routes.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectUsersTo('/bajki');

        $middleware->web(append: [
            App\Http\Middleware\HandleInertiaRequests::class,
            Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Sentry::handles($exceptions);

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return Inertia::render('Errors/404')->toResponse($request)->setStatusCode(404);
        });
    })
    ->create();
