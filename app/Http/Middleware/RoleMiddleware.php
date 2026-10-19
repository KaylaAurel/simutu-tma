<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Memeriksa apakah user mempunyai role
     * yang diperbolehkan mengakses route.
     *
     * Contoh:
     *
     * ->middleware('role:admin')
     *
     * atau:
     *
     * ->middleware('role:admin,asman_tma')
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Pastikan user login
        |--------------------------------------------------------------------------
        */

        if (!$request->user()) {

            return redirect()
                ->route('login');
        }

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Pastikan role user termasuk role yang diizinkan
        |--------------------------------------------------------------------------
        */

        if (
            empty($user->role) ||
            !in_array(
                $user->role,
                $roles,
                true
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Jika request AJAX / API
            |--------------------------------------------------------------------------
            */

            if ($request->expectsJson()) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Anda tidak memiliki akses ke fitur ini.',
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | Jika request web biasa
            |--------------------------------------------------------------------------
            */

            abort(
                403,
                'Anda tidak memiliki hak akses ke halaman ini.'
            );
        }

        return $next($request);
    }
}