<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedIfAuthenticated
{
    public function handle(Request $request, Closure $next, string $redirectToRoute = 'verification.notice'): Response
    {
        if (!$request->user()) {
            return $next($request);
        }

        if ($request->user()->hasVerifiedEmail()) {
            return $next($request);
        }

        return $request->expectsJson()
            ? abort(409, 'Your email address is not verified.')
            : redirect()->route($redirectToRoute);
    }
}
