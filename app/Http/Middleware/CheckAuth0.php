<?php
// app/Http/Middleware/CheckAuth0.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth0
{
    public function handle(Request $request, Closure $next)
    {
        if (session('auth0_id')) {
            // User logged in via Auth0
            config(['auth.defaults.guard' => 'auth0']);
        }

        return $next($request);
    }
}
