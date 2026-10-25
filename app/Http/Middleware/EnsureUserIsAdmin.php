<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if (! $request->user()->hasRole('admin')) {
            abort(403, 'Bu alana yalnızca sistem yöneticileri erişebilir.');
        }

        return $next($request);
    }
}
