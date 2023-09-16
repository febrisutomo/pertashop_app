<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;

        foreach ($roles as $role) {
            if ($userRole == $role) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
