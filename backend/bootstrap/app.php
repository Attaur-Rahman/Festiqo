<?php

use App\Helpers\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/api/health',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Force JSON responses for all API routes.
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*'),
        );

        // Handle request validation failures (HTTP 422).
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::error(
                message: 'Validation failed.',
                errors: $e->errors(),
                status: 422
            );
        });

        // Handle authentication failures such as missing or invalid access tokens (HTTP 401).
        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::error(
                message: $e->getMessage() ?: 'Unauthenticated.',
                status: 401
            );
        });

        // Handle access denied errors such as insufficient permissions or inactive accounts (HTTP 403).
        $exceptions->render(function (AccessDeniedHttpException $e) {
            return ApiResponse::error(
                message: $e->getMessage() ?: 'Forbidden.',
                status: 403
            );
        });

        // Returns a standardized JSON response when a user lacks the required permission.
        $exceptions->render(function (UnauthorizedException $e, $request) {
            if ($request->is('api/*')) {
                return ApiResponse::error(
                    message: 'You do not have permission to perform this action.',
                    status: 403
                );
            }
        });
    })->create();
