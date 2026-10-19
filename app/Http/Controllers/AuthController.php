<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     *
     * Login hanya diperbolehkan untuk:
     * - admin_tma
     * - asman_tma
     *
     * PTA tidak memiliki akun login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => [
                'required',
                'string',
                'max:50',
            ],

            'password' => [
                'required',
                'string',
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Cek username dan password
        |--------------------------------------------------------------------------
        |
        | Kita tidak menggunakan is_active karena kolom tersebut
        | memang tidak terdapat pada tabel users.
        |
        */

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            /*
            |--------------------------------------------------------------------------
            | Cek role
            |--------------------------------------------------------------------------
            |
            | Hanya Admin TMA dan Asman TMA yang diperbolehkan
            | masuk ke sistem.
            |
            */

            if (!in_array(Auth::user()->role, [
                'admin_tma',
                'asman_tma',
            ])) {

                Auth::logout();

                return back()
                    ->withErrors([
                        'username' => 'Akun Anda tidak memiliki akses untuk masuk ke SIMUTU-TMA.',
                    ])
                    ->withInput(
                        $request->only('username')
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Regenerate session untuk keamanan
            |--------------------------------------------------------------------------
            */

            $request->session()->regenerate();

            /*
            |--------------------------------------------------------------------------
            | Redirect ke dashboard
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->intended(route('dashboard'))
                ->with(
                    'success',
                    'Selamat datang di SIMUTU-TMA.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Login gagal
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'username' => 'Username atau password tidak sesuai.',
            ])
            ->withInput(
                $request->only('username')
            );
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        /*
        |--------------------------------------------------------------------------
        | Hapus session lama
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();

        /*
        |--------------------------------------------------------------------------
        | Generate CSRF token baru
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();

        /*
        |--------------------------------------------------------------------------
        | Kembali ke halaman login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Anda berhasil keluar dari SIMUTU-TMA.'
            );
    }
}