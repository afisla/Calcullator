<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AnonymousTokenMiddleware
{
    /**
     * Generate & maintain a unique anonymous token per browser session.
     * This token links orders to a student's browser without login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session()->has('anonymous_token')) {
            session(['anonymous_token' => Str::uuid()->toString()]);
        }

        // Share token with all views
        view()->share('anonymousToken', session('anonymous_token'));

        return $next($request);
    }
}
