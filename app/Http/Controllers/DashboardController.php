<?php

namespace App\Http\Controllers;

use App\Models\MutuD;
use App\Models\PTA;
use App\Models\ImportProdis;
use App\Models\Pemberitahuan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * ============================================================
     * DASHBOARD
     * ============================================================
     *
     * Dashboard dibedakan berdasarkan role:
     *
     * - admin_tma
     * - asman_tma
     *
     */
    public function index()
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | TOTAL DATA MUTU D
        |--------------------------------------------------------------------------
        */

        $totalMutuD = MutuD::query()
            ->where('kualitas', 'D')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MUTU D HARI INI
        |--------------------------------------------------------------------------
        |
        | Tetap dihitung untuk kebutuhan dashboard admin.
        |
        | Tanggal memo berada di tabel memo_prodis.
        |
        */

        $mutuDHariIni = MutuD::query()
            ->where('kualitas', 'D')
            ->whereHas('memoProdis', function ($query) {

                $query->whereDate(
                    'tanggal_memo',
                    today()
                );

            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | SKORSING AKTIF
        |--------------------------------------------------------------------------
        |
        | Data yang tanggal skorsingnya sedang aktif hari ini.
        |
        */

        $skorsingAktif = MutuD::query()
            ->where('kualitas', 'D')
            ->whereNotNull('tanggal_skorsing_mulai')
            ->whereNotNull('tanggal_skorsing_selesai')
            ->whereDate(
                'tanggal_skorsing_mulai',
                '<=',
                today()
            )
            ->whereDate(
                'tanggal_skorsing_selesai',
                '>=',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PTA
        |--------------------------------------------------------------------------
        */

        $totalPTA = PTA::count();


        /*
        |--------------------------------------------------------------------------
        | PTA AKTIF
        |--------------------------------------------------------------------------
        */

        $ptaAktif = PTA::query()
            ->where(
                'status',
                'aktif'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL IMPORT PRODIS
        |--------------------------------------------------------------------------
        */

        $totalImport = ImportProdis::count();


        /*
        |--------------------------------------------------------------------------
        | IMPORT PRODIS BERHASIL
        |--------------------------------------------------------------------------
        */

        $importBerhasil = ImportProdis::query()
            ->where(
                'status',
                'berhasil'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL PEMBERITAHUAN
        |--------------------------------------------------------------------------
        */

        $totalPemberitahuan = Pemberitahuan::count();


        /*
        |--------------------------------------------------------------------------
        | PEMBERITAHUAN HARI INI
        |--------------------------------------------------------------------------
        */

        $pemberitahuanHariIni = Pemberitahuan::query()
            ->whereDate(
                'tanggal_pemberitahuan',
                today()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PEMBERITAHUAN TERKIRIM
        |--------------------------------------------------------------------------
        |
        | Ini untuk kebutuhan dashboard admin.
        |
        */

        $pemberitahuanTerkirim = Pemberitahuan::query()
            ->where(
                'status_pesan',
                'terkirim'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MUTU D TERBARU
        |--------------------------------------------------------------------------
        |
        | PENTING:
        |
        | Jangan menggunakan whereDate(today()).
        |
        | Dashboard harus tetap menampilkan data hasil import
        | meskipun tanggal datanya bukan hari ini.
        |
        */

        $mutuDTerbaru = MutuD::query()
            ->with([
                'memoProdis',
                'pta',
                'photos',
            ])
            ->where(
                'kualitas',
                'D'
            )
            ->latest('created_at')
            ->take(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | IMPORT PRODIS TERBARU
        |--------------------------------------------------------------------------
        */

        $importTerbaru = ImportProdis::query()
            ->with('importedBy')
            ->latest('created_at')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PEMBERITAHUAN TERBARU
        |--------------------------------------------------------------------------
        */

        $pemberitahuanTerbaru = Pemberitahuan::query()
            ->with([
                'pta',
                'creator',
            ])
            ->latest('created_at')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK DASHBOARD ADMIN
        |--------------------------------------------------------------------------
        */

        $stats = [

            // MUTU D
            'total_mutu_d' =>
                $totalMutuD,

            'mutu_d_hari_ini' =>
                $mutuDHariIni,


            // SKORSING
            'skorsing_aktif' =>
                $skorsingAktif,


            // PTA
            'total_pta' =>
                $totalPTA,

            'pta_aktif' =>
                $ptaAktif,


            // IMPORT PRODIS
            'total_import' =>
                $totalImport,

            'import_berhasil' =>
                $importBerhasil,


            // PEMBERITAHUAN
            'total_pemberitahuan' =>
                $totalPemberitahuan,

            'pemberitahuan_hari_ini' =>
                $pemberitahuanHariIni,

            'pemberitahuan_terkirim' =>
                $pemberitahuanTerkirim,
        ];


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ADMIN TMA
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin_tma') {

            return view(
                'dashboard.admin',
                compact(
                    'stats',
                    'mutuDTerbaru',
                    'importTerbaru',
                    'pemberitahuanTerbaru'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD ASMAN TMA
        |--------------------------------------------------------------------------
        |
        | Blade Asman menggunakan variabel:
        |
        | $statistics
        | $latestMutuD
        |
        | Jadi kita siapkan khusus di sini.
        |
        */


        if ($user->role === 'asman_tma') {


            /*
            |--------------------------------------------------------------------------
            | TOTAL MUTU D
            |--------------------------------------------------------------------------
            */

            $total = MutuD::query()
                ->where(
                    'kualitas',
                    'D'
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | SKORSING AKTIF
            |--------------------------------------------------------------------------
            */

            $skorsingAktifAsman = MutuD::query()
                ->where(
                    'kualitas',
                    'D'
                )
                ->whereNotNull(
                    'tanggal_skorsing_mulai'
                )
                ->whereNotNull(
                    'tanggal_skorsing_selesai'
                )
                ->whereDate(
                    'tanggal_skorsing_mulai',
                    '<=',
                    today()
                )
                ->whereDate(
                    'tanggal_skorsing_selesai',
                    '>=',
                    today()
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | SUDAH DIBERITAHUKAN
            |--------------------------------------------------------------------------
            */

            $sudahDiberitahukan = MutuD::query()
                ->where(
                    'kualitas',
                    'D'
                )
                ->where(
                    'status_pemberitahuan',
                    'sudah_diberitahukan'
                )
                ->count();


            /*
            |--------------------------------------------------------------------------
            | BELUM DIBERITAHUKAN
            |--------------------------------------------------------------------------
            |
            | Termasuk:
            |
            | - NULL
            | - belum_diberitahukan
            |
            */

            $belumDiberitahukan = MutuD::query()
                ->where(
                    'kualitas',
                    'D'
                )
                ->where(function ($query) {

                    $query
                        ->whereNull(
                            'status_pemberitahuan'
                        )
                        ->orWhere(
                            'status_pemberitahuan',
                            'belum_diberitahukan'
                        );

                })
                ->count();


            /*
            |--------------------------------------------------------------------------
            | STATISTICS KHUSUS BLADE ASMAN
            |--------------------------------------------------------------------------
            |
            | Ini menyesuaikan langsung dengan Blade yang kita buat.
            |
            */

            $statistics = [

                'total' =>
                    $total,

                'skorsing_aktif' =>
                    $skorsingAktifAsman,

                'sudah_diberitahukan' =>
                    $sudahDiberitahukan,

                'belum_diberitahukan' =>
                    $belumDiberitahukan,
            ];


            /*
            |--------------------------------------------------------------------------
            | MUTU D TERBARU UNTUK ASMAN
            |--------------------------------------------------------------------------
            |
            | Tidak dibatasi tanggal hari ini.
            |
            */

            $latestMutuD = MutuD::query()
                ->with([
                    'memoProdis',
                    'pta',
                    'photos',
                ])
                ->where(
                    'kualitas',
                    'D'
                )
                ->latest('created_at')
                ->take(8)
                ->get();


            /*
            |--------------------------------------------------------------------------
            | RETURN DASHBOARD ASMAN
            |--------------------------------------------------------------------------
            */

            return view(
                'dashboard.asman',
                compact(
                    'statistics',
                    'latestMutuD',
                    'pemberitahuanTerbaru'
                )
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE TIDAK DIIZINKAN
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        return redirect()
            ->route('login')
            ->withErrors([
                'username' =>
                    'Role akun tidak memiliki akses ke sistem.',
            ]);
    }
}