<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        foreach ($roles as $role) {
            if ($role == auth()->user()->roles && auth()->user()->roles == 'admin') {
                return $next($request);
            }
        }

        Auth::logout();

        return redirect()->back()->withErrors('Anda tidak memiliki izin untuk mengakses halaman ini, pastikan data pengguna dan level anda sesuai.');;
    }
}
