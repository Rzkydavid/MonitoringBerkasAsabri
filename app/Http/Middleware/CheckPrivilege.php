<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPrivilege
{
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $required = $route->defaults['privilege'] ?? null;

        // If route has no privilege requirement → allow
        if (!$required) {
            return $next($request);
        }

        // Must be logged in
        $user = auth()->user();
        if (!$user)
            abort(401);

        // Get user's privileges (string array)
        $userPrivileges = $user->role->privileges();

        // Authorization check
        if (!in_array($required, $userPrivileges)) {
            abort(403, "Forbidden (missing privilege: $required)");

            return redirect()->route('unauthorized')
                ->with('error', 'Anda tidak memiliki akses untuk halaman ini.');
        }

        return $next($request);
    }
}
