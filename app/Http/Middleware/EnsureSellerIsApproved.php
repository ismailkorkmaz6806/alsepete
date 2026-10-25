<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Admin her zaman satici alanina erisebilir
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        if (! $user->hasRole('seller')) {
            abort(403, 'Bu alana yalnızca satıcılar erişebilir.');
        }

        $seller = $user->seller;
        if (! $seller || ! in_array($seller->status, ['onayli', 'approved'])) {
            abort(403, 'Satıcı hesabınız henüz onaylanmamış veya askıya alınmıştır.');
        }

        return $next($request);
    }
}
