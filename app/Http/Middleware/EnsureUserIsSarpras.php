<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSarpras
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->isSarpras() || Auth::user()->isKepalaSekolah())) {
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Khusus bidang Sarana Prasarana atau Kepala Sekolah.');
    }
}
