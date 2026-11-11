<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles Optional specific roles allowed to pass
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if logged in via admin guard
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Please log in to access the admin portal.',
            ]);
        }

        $admin = Auth::guard('admin')->user();

        // 2. If specific roles are passed to the middleware, check against them
        if (!empty($roles) && !$admin->hasRole($roles)) {
            abort(403, 'Unauthorized. You do not have permission to access this section.');
        }

        return $next($request);
    }
}