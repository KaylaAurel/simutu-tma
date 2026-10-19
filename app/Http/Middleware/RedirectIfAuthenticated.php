<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Redirect user yang sudah login
     * agar tidak kembali ke halaman login.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$guards
    ): Response {
        $guards = empty($guards)
            ? [null]
            : $guards;

        foreach ($guards as $guard) {

            if (auth()->guard($guard)->check()) {

                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}