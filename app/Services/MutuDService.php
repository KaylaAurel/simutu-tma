<?php

namespace App\Services;

use App\Models\MemoProdis;
use App\Models\MutuD;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class MutuDService
{
    /**
     * ============================================================
     * QUERY MUTU D
     * ============================================================
     */
    public function query(array $filters = []): Builder
    {
        $query = MutuD::query()
            ->with([
                'memoProdis',
                'pta',
                'photos',
                'latestPemberitahuan',
            ])
            ->mutuD();

        /*
        |--------------------------------------------------------------------------
        | FILTER TANGGAL MEMO
        |--------------------------------------------------------------------------
        |
        | tanggal_memo berada di tabel memo_prodis.
        |
        */

        if (!empty($filters['tanggal'])) {
            $query->whereHas(
                'memoProdis',
                function (Builder $q) use ($filters) {
                    $q->whereDate(
                        'tanggal_memo',
                        $filters['tanggal']
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER PTA
        |--------------------------------------------------------------------------
        |
        | pta_id berada langsung di tabel mutu_d.
        |
        */

        if (!empty($filters['pta'])) {
            $query->where(
                'mutu_d.pta_id',
                $filters['pta']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function (Builder $q) use ($search) {

                $q->where(
                    'mutu_d.nama_petani',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'mutu_d.no_petani',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'mutu_d.no_spta',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'mutu_d.no_kontrak',
                    'like',
                    "%{$search}%"
                );

                $q->orWhere(
                    'mutu_d.nopol',
                    'like',
                    "%{$search}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS SKORSING
        |--------------------------------------------------------------------------
        |
        | status_skorsing bukan kolom database.
        | Status dihitung dari tanggal_skorsing_mulai
        | dan tanggal_skorsing_selesai.
        |
        */

        if (!empty($filters['status_skorsing'])) {

            switch ($filters['status_skorsing']) {

                case 'aktif':

                    $query->skorsingAktif();

                    break;

                case 'selesai':

                    $query
                        ->whereNotNull(
                            'mutu_d.tanggal_skorsing_selesai'
                        )
                        ->whereDate(
                            'mutu_d.tanggal_skorsing_selesai',
                            '<',
                            today()
                        );

                    break;

                case 'belum':

                    $query->where(function (Builder $q) {

                        $q->whereNull(
                            'mutu_d.tanggal_skorsing_mulai'
                        )

                        ->orWhereNull(
                            'mutu_d.tanggal_skorsing_selesai'
                        )

                        ->orWhereDate(
                            'mutu_d.tanggal_skorsing_mulai',
                            '>',
                            today()
                        );
                    });

                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        |
        | tanggal_memo berada di memo_prodis.
        |
        | Data terbaru ditampilkan paling atas.
        |
        */

        return $query
            ->orderByDesc(
                MemoProdis::select('tanggal_memo')
                    ->whereColumn(
                        'memo_prodis.id',
                        'mutu_d.memo_prodis_id'
                    )
            )
            ->orderByDesc('mutu_d.id');
    }

    /**
     * ============================================================
     * TERAPKAN SKORSING
     * ============================================================
     *
     * Default = 3 hari.
     */
    public function applySkorsing(
        MutuD $mutuD,
        int $durasi = 3,
        ?string $catatan = null
    ): MutuD {

        return DB::transaction(function () use (
            $mutuD,
            $durasi,
            $catatan
        ) {

            if ($durasi < 1) {
                $durasi = 1;
            }

            $mulai = today();

            $selesai = $mulai
                ->copy()
                ->addDays($durasi - 1);

            $mutuD->update([
                'durasi_skorsing' =>
                    $durasi,

                'tanggal_skorsing_mulai' =>
                    $mulai,

                'tanggal_skorsing_selesai' =>
                    $selesai,
            ]);

            return $mutuD->refresh();
        });
    }

    /**
     * ============================================================
     * HENTIKAN SKORSING
     * ============================================================
     *
     * Karena status aktif menggunakan:
     *
     * tanggal_mulai <= hari_ini
     * tanggal_selesai >= hari_ini
     *
     * maka tanggal selesai hari ini masih dianggap aktif.
     *
     * Untuk benar-benar menghentikan skorsing sekarang,
     * tanggal selesai dibuat kemarin.
     */
    public function stopSkorsing(
        MutuD $mutuD,
        ?string $catatan = null
    ): MutuD {

        $mutuD->update([
            'tanggal_skorsing_selesai' =>
                today()->subDay(),
        ]);

        return $mutuD->refresh();
    }

    /**
     * ============================================================
     * UPDATE SKORSING EXPIRED
     * ============================================================
     *
     * Tidak perlu update database karena status dihitung
     * otomatis melalui accessor pada model MutuD.
     */
    public function updateExpiredSkorsing(): int
    {
        return 0;
    }

    /**
     * ============================================================
     * STATISTIK MUTU D
     * ============================================================
     */
    public function statistics(
        ?string $tanggal = null
    ): array {

        $query = MutuD::query()
            ->mutuD();

        /*
        |--------------------------------------------------------------------------
        | Filter tanggal memo
        |--------------------------------------------------------------------------
        */

        if ($tanggal) {
            $query->whereHas(
                'memoProdis',
                function (Builder $q) use ($tanggal) {
                    $q->whereDate(
                        'tanggal_memo',
                        $tanggal
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $total = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Skorsing aktif
        |--------------------------------------------------------------------------
        */

        $skorsingAktif = (clone $query)
            ->skorsingAktif()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Sudah diberitahukan
        |--------------------------------------------------------------------------
        */

        $sudahDiberitahukan = (clone $query)
            ->whereHas(
                'pemberitahuanDetails',
                function (Builder $q) {
                    $q->where(
                        'status',
                        'sent'
                    );
                }
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Belum diberitahukan
        |--------------------------------------------------------------------------
        */

        $belumDiberitahukan = (clone $query)
            ->whereDoesntHave(
                'pemberitahuanDetails',
                function (Builder $q) {
                    $q->where(
                        'status',
                        'sent'
                    );
                }
            )
            ->count();

        return [
            'total' =>
                $total,

            'skorsing_aktif' =>
                $skorsingAktif,

            'sudah_diberitahukan' =>
                $sudahDiberitahukan,

            'belum_diberitahukan' =>
                $belumDiberitahukan,
        ];
    }
}