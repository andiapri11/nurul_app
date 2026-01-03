<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsPrincipal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isKepalaSekolah()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki wewenang Kepala Sekolah.');
        }

        return $next($request);
    }
}
