<?php

use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



return Application::configure(basePath: dirname(__DIR__))

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    */

    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )


    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    */

    ->withMiddleware(function (Middleware $middleware) {

        $middleware->trustProxies(at: '*');

        /*
        |--------------------------------------------------------------------------
        | Guest Redirects
        |--------------------------------------------------------------------------
        */

        $middleware->redirectTo(
            guests: function (Request $request) {

                // Alumni
                if (
                    $request->is('alumni') ||
                    $request->is('alumni/*')
                ) {
                    return route('alumni.login');
                }

                // Admin
                if (
                    $request->is('admin') ||
                    $request->is('admin/*')
                ) {
                    return route('admin.login');
                }

                // Normal users
                return route('login');
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })


    /*
    |--------------------------------------------------------------------------
    | Exception Handling
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->respond(function (
            $response,
            \Throwable $exception,
            Request $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | Get Laravel's final HTTP status
            |--------------------------------------------------------------------------
            |
            | We use the response status instead of calling
            | getStatusCode() on the exception because not every
            | Throwable has that method.
            |
            */
            $status = $response->getStatusCode();

            if ($status >= 300 && $status < 400) {
                return $response;
            }

            /*
            |--------------------------------------------------------------------------
            | Error Visitor Log
            |--------------------------------------------------------------------------
            |
            | Records every visitor who reaches an error response.
            | This is written to:
            |
            | storage/logs/error-visitors.log
            |
            */

            Log::channel('error_visitors')->error('Error page reached', [

                // HTTP status
                'status' => $status,

                // Account information
                // NULL = visitor is not logged in
                'user_id' => Auth::id(),

                // Request information
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'route' => $request->route()?->getName(),

                // Visitor information
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),

                // Exception information
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | JSON / AJAX / API
            |--------------------------------------------------------------------------
            */

            if ($request->expectsJson()) {

                // 403
                if ($status === 403) {
                    return response()->json([
                        'message' => 'You are not allowed to access this resource.',
                    ], 403);
                }

                // 404
                if ($status === 404) {
                    return response()->json([
                        'message' => 'The requested resource was not found.',
                    ], 404);
                }

                // 419
                if ($status === 419) {
                    return response()->json([
                        'message' => 'Your session has expired. Please refresh the page and try again.',
                    ], 419);
                }

                // 429
                if ($status === 429) {
                    return response()->json([
                        'message' => 'Too many requests. Please try again later.',
                    ], 429);
                }

                // 503
                if ($status === 503) {
                    return response()->json([
                        'message' => 'The service is temporarily unavailable.',
                    ], 503);
                }

                // Any 500+ error
                if ($status >= 500) {
                    return response()->json([
                        'message' => 'Something went wrong. Please try again later.',
                    ], $status);
                }

                /*
                |--------------------------------------------------------------------------
                | Other JSON responses
                |--------------------------------------------------------------------------
                */

                return $response;
            }


            /*
            |--------------------------------------------------------------------------
            | Normal Web Requests
            |--------------------------------------------------------------------------
            */

            // 403
            if ($status === 403) {
                return response()->view(
                    'errors.403',
                    [],
                    403
                );
            }

            // 404
            if ($status === 404) {
                return response()->view(
                    'errors.404',
                    [],
                    404
                );
            }

            // 419
            if ($status === 419) {
                return response()->view(
                    'errors.419',
                    [],
                    419
                );
            }

            // 429
            if ($status === 429) {
                return response()->view(
                    'errors.429',
                    [],
                    429
                );
            }

            // 503
            if ($status === 503) {
                return response()->view(
                    'errors.503',
                    [],
                    503
                );
            }


            /*
            |--------------------------------------------------------------------------
            | 500 / Unexpected Server Errors
            |--------------------------------------------------------------------------
            |
            | Database errors
            | TypeError
            | RuntimeException
            | PHP Error
            | Application exceptions
            | etc.
            |
            */

            if ($status >= 500) {
                return response()->view(
                    'errors.500',
                    [],
                    $status
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Generic HTTP Error
            |--------------------------------------------------------------------------
            |
            | Examples:
            |
            | 400
            | 405
            | 408
            | 410
            | 422
            | 423
            | 425
            | 428
            | 431
            | 451
            | etc.
            |
            | These use errors/default.blade.php.
            |
            */

            return response()->view(
                'errors.default',
                [
                    'status' => $status,
                ],
                $status
            );
        });

    })

    ->create();