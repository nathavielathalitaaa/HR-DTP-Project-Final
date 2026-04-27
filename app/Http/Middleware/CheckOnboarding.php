<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) return $next($request);

        // Skip for these routes to avoid redirect loop
        $skipRoutes = ['onboarding', 'logout', 'logout/page', 'profile.ttd', 'profile.pin', 'profile.ttd.preview'];
        if ($request->routeIs(...$skipRoutes)) return $next($request);

        $profile = auth()->user()->profile;

        // If no TTD or no PIN — redirect to onboarding
        if (!$profile || !$profile->ttd_path || !$profile->pin) {
            return redirect()->route('onboarding');
        }

        return $next($request);
    }
}
