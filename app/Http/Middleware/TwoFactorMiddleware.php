<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Skip if user doesn't have 2FA enabled
        if (!$user || !$user->two_factor_secret) {
            return $next($request);
        }

        // Check if 2FA was recently verified
        $verified = session('2fa_verified', false);
        $verifiedAt = session('2fa_verified_at');

        // Require re-verification after 30 minutes
        if ($verified && $verifiedAt && now()->diffInMinutes($verifiedAt) < 30) {
            return $next($request);
        }

        // Clear old session
        session()->forget(['2fa_verified', '2fa_verified_at']);

        // Redirect to 2FA challenge
        if ($request->route()->named('two-factor.challenge') ||
            $request->route()->named('two-factor.verify')) {
            return $next($request);
        }

        return redirect()->route('two-factor.challenge');
    }
}
