<?php

namespace App\Services;

use App\Models\ImportProdis;
use App\Models\MemoProdis;
use App\Models\MutuD;
use App\Models\PTA;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class ProdisImportService
{
    /**
     * ============================================================
     * PREVIEW FILE UPLOAD
     * ============================================================
     */
    public function preview(UploadedFile $file): array
    {
        $rows = $this->readExcel($file);

        if (empty($rows)) {
            throw new \RuntimeException(
                'File Excel tidak memiliki data.'
            );
        }

        $normalizedRows = collect($rows)
    ->map(function ($row, $index) {
        return $this->normalizeRow(
            $row,
            $index + 2
        );
    })
    ->filter(function ($row) {

        // Buang baris TOTAL
        if (
            strtoupper(
                trim(
                    (string) ($row['nama_petani'] ?? '')
                )
            ) === 'TOTAL'
        ) {
            return false;
        }

        return !$this->isEmptyRow($row);
    })
    ->values()
    ->all();

        return $this->buildPreviewResult(
            $normalizedRows
        );
    }


    /**
     * ============================================================
     * PREVIEW FILE YANG SUDAH DISIMPAN
     * ============================================================
     */
    public function previewStoredFile(
        ImportProdis $import
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Support dua nama kolom database
        |--------------------------------------------------------------------------
        */

        $path = $import->path_file;

        if (empty($path)) {
            $path = $import->file_path;
        }

        if (empty($path)) {
            throw new \RuntimeException(
                'File import tidak memiliki path.'
            );
        }

        if (
            !Storage::disk('public')->exists($path)
        ) {
            throw new \RuntimeException(
                'File import tidak ditemukan di storage.'
            );
        }

        $fullPath =
            Storage::disk('public')->path($path);

        $rows =
            $this->readExcelFromPath($fullPath);

        if (empty($rows)) {
            throw new \RuntimeException(
                'File Excel tidak memiliki data.'
            );
        }

       $normalizedRows = collect($rows)
    ->map(function ($row, $index) {

        return $this->normalizeRow(
            $row,
            $index + 2
        );

    })
    ->filter(function ($row) {

        // Buang baris TOTAL
        if (
            strtoupper(
                trim(
                    (string) ($row['nama_petani'] ?? '')
                )
            ) === 'TOTAL'
        ) {
            return false;
        }

        return !$this->isEmptyRow($row);

    })
    ->values()
    ->all();

        return $this->buildPreviewResult(
            $normalizedRows
        );
    }


    /**
     * ============================================================
     * HASIL PREVIEW
     * ============================================================
     */
    private function buildPreviewResult(
        array $normalizedRows
    ): array {

        $total =
            count($normalizedRows);

        /*
        |--------------------------------------------------------------------------
        | Hitung Mutu D
        |--------------------------------------------------------------------------
        */

        $mutuD =
            collect($normalizedRows)
                ->filter(function ($row) {

                    return strtoupper(
                        trim(
                            (string) (
                                $row['kualitas'] ?? ''
                            )
                        )
                    ) === 'D';

                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | Tanggal memo
        |--------------------------------------------------------------------------
        */

        $memoDates =
            collect($normalizedRows)
                ->pluck('tanggal_memo')
                ->filter(function ($date) {

                    return !empty($date);

                })
                ->unique()
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Nomor memo
        |--------------------------------------------------------------------------
        */

        $memoNumbers =
            collect($normalizedRows)
                ->pluck('nomor_memo')
                ->filter(function ($memo) {

                    return !empty($memo);

                })
                ->unique()
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | Format row untuk Blade
        |--------------------------------------------------------------------------
        */

        $bladeRows =
            collect($normalizedRows)
                ->map(function ($row) {

                    return [

                        'nama_petani' =>
                            $row['nama_petani'] ?? null,

                        'kode_petani' =>
                            $row['no_petani'] ?? null,

                        'nomor_spta' =>
                            $row['no_spta'] ?? null,

                        'wilayah' =>
                            $row['nama_pta']
                            ?? $row['kode_pta']
                            ?? null,

                        'tanggal_memo' =>
                            $row['tanggal_memo'] ?? null,

                        'nomor_memo' =>
                            $row['nomor_memo'] ?? null,

                        /*
                        | Blade lama menggunakan kualitas
                        */
                        'kualitas' =>
                            $row['kualitas'] ?? null,

                        /*
                        | Tetap sediakan jenis_mutu
                        | supaya kompatibel dengan Blade lama.
                        */
                        'jenis_mutu' =>
                            $row['kualitas'] ?? null,

                    ];

                })
                ->values()
                ->all();


        return [

            'total_rows' =>
                $total,

            'mutu_d_rows' =>
                $mutuD,

            'non_mutu_d_rows' =>
                $total - $mutuD,

            'memo_dates' =>
                $memoDates,

            'memo_numbers' =>
                $memoNumbers,

            'rows' =>
                $bladeRows,
        ];
    }


    /**
     * ============================================================
     * IMPORT KE DATABASE
     * ============================================================
     */
    public function import(
        UploadedFile $file,
        int $userId
    ): ImportProdis {

        $storedPath = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | Baca Excel
            |--------------------------------------------------------------------------
            */

            $rows =
                $this->readExcel($file);

            if (empty($rows)) {
                throw new \RuntimeException(
                    'File Excel tidak memiliki data.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Simpan file asli
            |--------------------------------------------------------------------------
            */

            $fileName =
                now()->format('Ymd_His')
                . '_'
                . Str::slug(
                    pathinfo(
                        $file->getClientOriginalName(),
                        PATHINFO_FILENAME
                    )
                )
                . '.'
                . $file->getClientOriginalExtension();


            $storedPath =
                $file->storeAs(
                    'imports/prodis',
                    $fileName,
                    'public'
                );


            /*
            |--------------------------------------------------------------------------
            | Simpan record import
            |--------------------------------------------------------------------------
            */

            $import =
                ImportProdis::create([

                    'nama_file' =>
                        $file->getClientOriginalName(),

                    'path_file' =>
                        $storedPath,

                    'tanggal_import' =>
                        today(),

                    'jumlah_data' =>
                        count($rows),

                    'jumlah_memo' =>
                        0,

                    'status' =>
                        'processing',

                    'imported_by' =>
                        $userId,

                ]);


            $successRows = 0;
            $failedRows = 0;
            $errors = [];
            $memoCount = 0;


            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                &$successRows,
                &$failedRows,
                &$errors,
                &$memoCount,
                $rows,
                $import
            ) {

                foreach ($rows as $index => $row) {

                    try {

                        $normalized =
                            $this->normalizeRow(
                                $row,
                                $index + 2
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Skip baris kosong
                        |--------------------------------------------------------------------------
                        */

                        if (
                            $this->isEmptyRow(
                                $normalized
                            )
                        ) {
                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Tanggal memo wajib
                        |--------------------------------------------------------------------------
                        */

                        if (
                            empty(
                                $normalized['tanggal_memo']
                            )
                        ) {

                            throw new \RuntimeException(
                                'Tanggal memo tidak ditemukan pada baris '
                                . ($index + 2)
                            );
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Nomor memo
                        |--------------------------------------------------------------------------
                        */

                        if (
                            empty(
                                $normalized['nomor_memo']
                            )
                        ) {

                            throw new \RuntimeException(
                                'Nomor memo tidak ditemukan pada baris '
                                . ($index + 2)
                            );
                        }

// Abaikan baris TOTAL dari Excel
if (
    strtoupper(
        trim(
            (string) ($normalized['nama_petani'] ?? '')
        )
    ) === 'TOTAL'
) {
    continue;
}
                        /*
                        |--------------------------------------------------------------------------
                        | Cari / buat MemoProdis
                        |--------------------------------------------------------------------------
                        */

                       $memoCreated = false;

$normalized['nomor_memo'] = $nomorMemo;

$memo = $this->resolveMemoProdis(
    $normalized,
    $import,
    $memoCreated
);

if ($memoCreated) {
    $memoCount++;
}



                        /*
                        |--------------------------------------------------------------------------
                        | Hanya proses Mutu D
                        |--------------------------------------------------------------------------
                        */

                        if (
                            strtoupper(
                                trim(
                                    (string) (
                                        $normalized['kualitas']
                                        ?? ''
                                    )
                                )
                            ) !== 'D'
                        ) {

                            $successRows++;

                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Resolve PTA
                        |--------------------------------------------------------------------------
                        */

                        $pta =
                            $this->resolvePTA(
                                $normalized
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Cek duplicate
                        |--------------------------------------------------------------------------
                        */

                        $exists =
                            MutuD::query()
                                ->where(
                                    'memo_prodis_id',
                                    $memo->id
                                )
                                ->where(
                                    'no_petani',
                                    $normalized['no_petani']
                                )
                                ->where(
                                    'no_spta',
                                    $normalized['no_spta']
                                )
                                ->exists();


                        if ($exists) {

                            $successRows++;

                            continue;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Simpan Mutu D
                        |--------------------------------------------------------------------------
                        */

                        MutuD::create([

                            'memo_prodis_id' =>
                                $memo->id,

                            'pta_id' =>
                                $pta?->id,

                            'no_spta' =>
                                $normalized['no_spta'],

                            'no_kontrak' =>
                                $normalized['no_kontrak'],

                            'kode_afdeling' =>
                                $normalized['kode_afdeling'],

                            'nama_asman' =>
                                $normalized['nama_asman'],

                            'no_petani' =>
                                $normalized['no_petani'],

                            'deskripsi_blok' =>
                                $normalized['deskripsi_blok'],

                            'nama_petani' =>
                                $normalized['nama_petani'],

                            'nopol' =>
                                $normalized['nopol'],

                            'kualitas' =>
                                'D',

                            'tanggal_selektor' =>
                                $normalized['tanggal_selektor'],

                            'jam_selektor' =>
                                $normalized['jam_selektor'],

                            'tanggal_timbang' =>
                                $normalized['tanggal_timbang'],

                            'jam_timbang' =>
                                $normalized['jam_timbang'],

                            'tanggal_meja_tebu' =>
                                $normalized['tanggal_meja_tebu'],

                            'jam_meja_tebu' =>
                                $normalized['jam_meja_tebu'],

                            'durasi_skorsing' =>
                                $normalized['durasi_skorsing'],

                            'tanggal_skorsing_mulai' =>
                                $normalized['tanggal_skorsing_mulai'],

                            'tanggal_skorsing_selesai' =>
                                $normalized['tanggal_skorsing_selesai'],

                            'status_pemberitahuan' =>
    'belum_diberitahu',

                        ]);


                        $successRows++;

                    } catch (Throwable $e) {

                        $failedRows++;

                        $errors[] = [

                            'row' =>
                                $index + 2,

                            'message' =>
                                $e->getMessage(),

                        ];
                    }
                }
            });


            /*
            |--------------------------------------------------------------------------
            | Update import
            |--------------------------------------------------------------------------
            */

            $import->update([

                'jumlah_data' =>
                    $successRows,

                'jumlah_memo' =>
                    $memoCount,

                'status' =>
                    $failedRows > 0
                        ? 'partial'
                        : 'success',

            ]);


            return $import;

        } catch (Throwable $e) {

            if (
                $storedPath &&
                Storage::disk('public')
                    ->exists($storedPath)
            ) {

                Storage::disk('public')
                    ->delete($storedPath);
            }

            throw $e;
        }
    }

/**
 * ============================================================
 * PROCESS IMPORT YANG SUDAH DISIMPAN
 * ============================================================
 */
public function process(
    ImportProdis $import
): ImportProdis {

    $path = $import->path_file;

    /*
    |--------------------------------------------------------------------------
    | Kompatibilitas database lama
    |--------------------------------------------------------------------------
    */

    if (empty($path)) {
        $path = $import->file_path;
    }

    if (empty($path)) {
        throw new \RuntimeException(
            'File import tidak memiliki path.'
        );
    }

    if (
        !Storage::disk('public')->exists($path)
    ) {
        throw new \RuntimeException(
            'File import tidak ditemukan di storage.'
        );
    }

    $fullPath =
        Storage::disk('public')->path($path);

    /*
    |--------------------------------------------------------------------------
    | Baca Excel
    |--------------------------------------------------------------------------
    */

    $rows =
        $this->readExcelFromPath($fullPath);

    if (empty($rows)) {
        throw new \RuntimeException(
            'File Excel tidak memiliki data.'
        );
    }

    $successRows = 0;
    $failedRows = 0;
    $memoCount = 0;
    $errors = [];

    /*
    |--------------------------------------------------------------------------
    | Transaction
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use (
        &$successRows,
        &$failedRows,
        &$memoCount,
        &$errors,
        $rows,
        $import
    ) {

        foreach ($rows as $index => $row) {

            try {

                /*
                |--------------------------------------------------------------------------
                | Normalisasi
                |--------------------------------------------------------------------------
                */

                $normalized =
                    $this->normalizeRow(
                        $row,
                        $index + 2
                    );


                /*
                |--------------------------------------------------------------------------
                | Skip baris kosong
                |--------------------------------------------------------------------------
                */

                if (
                    $this->isEmptyRow(
                        $normalized
                    )
                ) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Tanggal memo wajib
                |--------------------------------------------------------------------------
                */

                if (
                    empty(
                        $normalized['tanggal_memo']
                    )
                ) {

                    throw new \RuntimeException(
                        'Tanggal memo tidak ditemukan pada baris '
                        . ($index + 2)
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Nomor memo
                |--------------------------------------------------------------------------
                |
                | Kalau nomor memo kosong tetapi tanggal ada,
                | tetap buat identifier memo berdasarkan tanggal.
                |
                */

                $nomorMemo =
                    $normalized['nomor_memo'];

                if (
                    empty($nomorMemo)
                ) {

                    $nomorMemo =
                        'MEMO-' .
                        str_replace(
                            '-',
                            '',
                            $normalized['tanggal_memo']
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | Memo Prodis
                |--------------------------------------------------------------------------
                */

                $memoCreated = false;

$normalized['nomor_memo'] = $nomorMemo;

$memo = $this->resolveMemoProdis(
    $normalized,
    $import,
    $memoCreated
);

if ($memoCreated) {
    $memoCount++;
}

                /*
                |--------------------------------------------------------------------------
                | HANYA MUTU D
                |--------------------------------------------------------------------------
                */

                if (
                    strtoupper(
                        trim(
                            (string) (
                                $normalized['kualitas']
                                ?? ''
                            )
                        )
                    ) !== 'D'
                ) {

                    $successRows++;

                    continue;
                }


               /*
|--------------------------------------------------------------------------
| Cek duplicate Mutu D
|--------------------------------------------------------------------------
|
| Duplicate ditentukan berdasarkan:
| - no_spta
| - no_petani
|
| Tidak menggunakan memo_prodis_id karena
| file import berikutnya dapat memiliki memo berbeda.
|
*/

$exists = MutuD::query()
    ->where('no_spta', $normalized['no_spta'])
    ->where('no_petani', $normalized['no_petani'])
    ->exists();

if ($exists) {
    $successRows++;
    continue;
}

/*
|--------------------------------------------------------------------------
| Cari PTA
|--------------------------------------------------------------------------
*/

$pta = $this->resolvePTA(
    $normalized
);
                /*
                |--------------------------------------------------------------------------
                | Simpan Mutu D
                |--------------------------------------------------------------------------
                */

                MutuD::create([

                    'memo_prodis_id' =>
                        $memo->id,

                    'pta_id' =>
                        $pta?->id,

                    'no_spta' =>
                        $normalized['no_spta'],

                    'no_kontrak' =>
                        $normalized['no_kontrak'],

                    'kode_afdeling' =>
                        $normalized['kode_afdeling'],

                    'nama_asman' =>
                        $normalized['nama_asman'],

                    'no_petani' =>
                        $normalized['no_petani'],

                    'deskripsi_blok' =>
                        $normalized['deskripsi_blok'],

                    'nama_petani' =>
                        $normalized['nama_petani'],

                    'nopol' =>
                        $normalized['nopol'],

                    'kualitas' =>
                        'D',

                    'tanggal_selektor' =>
                        $normalized['tanggal_selektor'],

                    'jam_selektor' =>
                        $normalized['jam_selektor'],

                    'tanggal_timbang' =>
                        $normalized['tanggal_timbang'],

                    'jam_timbang' =>
                        $normalized['jam_timbang'],

                    'tanggal_meja_tebu' =>
                        $normalized['tanggal_meja_tebu'],

                    'jam_meja_tebu' =>
                        $normalized['jam_meja_tebu'],

                    'durasi_skorsing' =>
                        $normalized['durasi_skorsing'],

                    'tanggal_skorsing_mulai' =>
                        $normalized['tanggal_skorsing_mulai'],

                    'tanggal_skorsing_selesai' =>
                        $normalized['tanggal_skorsing_selesai'],

                   'status_pemberitahuan' =>
    'belum_diberitahu',

                ]);


                $successRows++;

            } catch (Throwable $e) {

                $failedRows++;

                $errors[] = [

                    'row' =>
                        $index + 2,

                    'message' =>
                        $e->getMessage(),

                ];
            }
        }
    });


    /*
    |--------------------------------------------------------------------------
    | Update status import
    |--------------------------------------------------------------------------
    */

   $import->update([

    'jumlah_data' =>
        $successRows,

    'jumlah_memo' =>
        $memoCount,

    'status' =>
        $failedRows > 0
            ? 'failed'
            : 'success',

]);
if ($failedRows > 0) {

    $detailError = collect($errors)
        ->map(function ($error) {

            return 'Baris '
                . $error['row']
                . ': '
                . $error['message'];

        })
        ->implode("\n");

    throw new \RuntimeException(
        "Import selesai tetapi ada {$failedRows} baris gagal:\n"
        . $detailError
    );
}
    return $import;
}
    /**
     * ============================================================
     * RESOLVE / CREATE MEMO PRODIS
     * ============================================================
     *
     * Identitas memo bersifat GLOBAL, bukan per import.
     *
     * Artinya:
     * - Import 27 Juli - 12 Agustus membuat memo 27..12.
     * - Import berikutnya 27 Juli - 13 Agustus akan menemukan
     *   memo 27..12 yang sudah ada.
     * - Hanya memo tanggal 13 Agustus yang dibuat baru.
     *
     * Kombinasi nomor_memo + tanggal_memo menjadi identitas memo.
     */
    private function resolveMemoProdis(
    array $normalized,
    ImportProdis $import,
    bool &$created
): MemoProdis {
    $created = false;

    $nomorMemo = trim(
        (string) ($normalized['nomor_memo'] ?? '')
    );

    if ($nomorMemo === '') {
        throw new \RuntimeException(
            'Nomor memo tidak ditemukan.'
        );
    }

    $memo = MemoProdis::where(
        'nomor_memo',
        $nomorMemo
    )->first();

    if ($memo) {
        return $memo;
    }

    $memo = MemoProdis::create([
        'import_prodis_id' => $import->id,
        'nomor_memo' => $nomorMemo,
        'tanggal_memo' => $normalized['tanggal_memo'],
        'keterangan' => $normalized['keterangan'] ?? null,
    ]);

    $created = true;

    return $memo;
}

    /**
     * ============================================================
     * RESOLVE PTA
     * ============================================================
     */
    private function resolvePTA(array $row): ?PTA
{
    /*
    |--------------------------------------------------------------------------
    | PRIORITAS 1: KODE PTA
    |--------------------------------------------------------------------------
    */

    $kode = trim((string) ($row['kode_pta'] ?? ''));

    if ($kode !== '') {

        $pta = PTA::active()
            ->whereRaw(
                'LOWER(TRIM(kode_pta)) = ?',
                [strtolower($kode)]
            )
            ->first();

        if ($pta) {
            return $pta;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PRIORITAS 2: NAMA PTA
    |--------------------------------------------------------------------------
    */

    $nama = trim((string) ($row['nama_pta'] ?? ''));

    if ($nama === '') {
        return null;
    }

    /*
    | Normalisasi spasi
    */

    $namaNormal = preg_replace('/\s+/', ' ', $nama);

    /*
    | Cari exact berdasarkan nama
    */

    $pta = PTA::active()
        ->whereRaw(
            'LOWER(TRIM(nama_pta)) = ?',
            [strtolower($namaNormal)]
        )
        ->first();

    if ($pta) {
        return $pta;
    }

    /*
    |--------------------------------------------------------------------------
    | FALLBACK: pencarian LIKE
    |--------------------------------------------------------------------------
    */

    return PTA::active()
        ->where(
            'nama_pta',
            'like',
            '%' . $namaNormal . '%'
        )
        ->first();
}


    /**
     * ============================================================
     * BACA EXCEL UPLOAD
     * ============================================================
     */
    private function readExcel(
        UploadedFile $file
    ): array {

        return $this->readExcelFromPath(
            $file->getRealPath()
        );
    }
private function backfillPTA(): void
{
    MutuD::query()
        ->whereNull('pta_id')
        ->whereNotNull('nama_petani')
        ->chunkById(100, function ($mutuDs) {

            foreach ($mutuDs as $mutuD) {

                /*
                |--------------------------------------------------------------------------
                | Cari berdasarkan data PTA dari Mutu D
                |--------------------------------------------------------------------------
                |
                | Karena nama PTA sebenarnya tersimpan di data Excel,
                | pastikan field tersebut memang tersedia.
                |
                */

                $namaPta = trim(
                    (string) ($mutuD->nama_pta ?? '')
                );

                if ($namaPta === '') {
                    continue;
                }

                $namaPta = preg_replace(
                    '/\s+/',
                    ' ',
                    $namaPta
                );

                $pta = PTA::active()
                    ->whereRaw(
                        'LOWER(TRIM(nama_pta)) = ?',
                        [strtolower($namaPta)]
                    )
                    ->first();

                if (!$pta) {
                    $pta = PTA::active()
                        ->where(
                            'nama_pta',
                            'like',
                            '%' . $namaPta . '%'
                        )
                        ->first();
                }

                if ($pta) {

                    $mutuD->update([
                        'pta_id' => $pta->id,
                    ]);

                }
            }
        });
}

    /**
     * ============================================================
     * BACA EXCEL DARI PATH
     *
     * PERBAIKAN UTAMA:
     *
     * Header tidak selalu baris pertama.
     *
     * Kita cari header sebenarnya terlebih dahulu.
     * Kemudian informasi Memo No + Tanggal dari baris sebelum
     * header akan diterapkan ke semua data.
     * ============================================================
     */
   /**
 * ============================================================
 * BACA EXCEL DARI PATH
 * ============================================================
 */
private function readExcelFromPath(
    string $path
): array {

    $spreadsheet = IOFactory::load($path);

    $sheet = $spreadsheet->getActiveSheet();

    $rows = $sheet->toArray(
        null,
        true,
        true,
        true
    );

    if (count($rows) < 2) {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER EXCEL
    |--------------------------------------------------------------------------
    */

    $headers = array_shift($rows);

    $headers = array_map(
        fn ($header) =>
            $this->normalizeHeader($header),
        $headers
    );


    /*
    |--------------------------------------------------------------------------
    | MEMO AKTIF
    |--------------------------------------------------------------------------
    */

    $currentMemoNumber = null;
    $currentMemoDate   = null;


    /*
    |--------------------------------------------------------------------------
    | MEMO PENDING
    |--------------------------------------------------------------------------
    |
    | Digunakan ketika nomor memo dan tanggal memo berada
    | pada baris yang berbeda.
    |
    */

    $pendingMemoNumber = null;
    $pendingMemoDate   = null;


    $result = [];


    /*
    |--------------------------------------------------------------------------
    | BACA SETIAP BARIS
    |--------------------------------------------------------------------------
    */

    foreach ($rows as $row) {

        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh isi cell
        |--------------------------------------------------------------------------
        */

        $rowValues = array_map(
            function ($value) {

                return trim(
                    (string) $value
                );

            },
            $row
        );


        $rowValues = array_filter(
            $rowValues,
            function ($value) {

                return $value !== '';

            }
        );


        $rowText = trim(
            implode(
                ' ',
                $rowValues
            )
        );


        /*
        |--------------------------------------------------------------------------
        | BARIS KOSONG
        |--------------------------------------------------------------------------
        */

        if ($rowText === '') {
            continue;
        }


        $upperRowText =
            strtoupper($rowText);


        /*
        |--------------------------------------------------------------------------
        | ================================================================
        | DETEKSI NOMOR MEMO
        | ================================================================
        |
        | Sekarang nomor memo boleh berdiri sendiri.
        |
        */

        $detectedMemoNumber = null;


        $memoPatterns = [

            '/MEMO\s*NO\.?\s*:?\s*(.+)$/iu',

            '/NOMOR\s*MEMO\s*:?\s*(.+)$/iu',

            '/NO\.?\s*MEMO\s*:?\s*(.+)$/iu',

        ];


        foreach ($memoPatterns as $pattern) {

            if (
                preg_match(
                    $pattern,
                    $rowText,
                    $matches
                )
            ) {

                $detectedMemoNumber =
                    trim(
                        $matches[1]
                    );

                break;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Bersihkan nomor memo kalau dalam satu baris
        | masih terdapat kata TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($detectedMemoNumber !== null) {

            $detectedMemoNumber =
                preg_replace(
                    '/\s+TANGGAL.*$/iu',
                    '',
                    $detectedMemoNumber
                );

            $detectedMemoNumber =
                trim(
                    $detectedMemoNumber
                );

            if (
                $detectedMemoNumber !== ''
            ) {

                $pendingMemoNumber =
                    $detectedMemoNumber;

            }
        }


        /*
        |--------------------------------------------------------------------------
        | ================================================================
        | DETEKSI TANGGAL MEMO
        | ================================================================
        |
        | Bisa:
        |
        | Tanggal 10 Agustus 2026
        | Tanggal: 10 Agustus 2026
        | 10 Agustus 2026
        |
        */

        $detectedMemoDate = null;


        /*
        |--------------------------------------------------------------------------
        | Format:
        | TANGGAL 10 AGUSTUS 2026
        |--------------------------------------------------------------------------
        */

        if (
            preg_match(
                '/TANGGAL\s*[:\-]?\s*(\d{1,2})\s+([A-Za-zÀ-ÿ]+)\s+(\d{4})/iu',
                $rowText,
                $matches
            )
        ) {

            $detectedMemoDate =
                $this->parseIndonesianMemoDate(
                    $matches[1],
                    $matches[2],
                    $matches[3]
                );

        }


        /*
        |--------------------------------------------------------------------------
        | Kalau tanggal belum ditemukan dan row hanya
        | berisi tanggal Indonesia
        |--------------------------------------------------------------------------
        */

        if (
            $detectedMemoDate === null
            &&
            preg_match(
                '/^\s*(\d{1,2})\s+([A-Za-zÀ-ÿ]+)\s+(\d{4})\s*$/iu',
                $rowText,
                $matches
            )
        ) {

            $detectedMemoDate =
                $this->parseIndonesianMemoDate(
                    $matches[1],
                    $matches[2],
                    $matches[3]
                );

        }


        /*
        |--------------------------------------------------------------------------
        | ================================================================
        | JIKA NOMOR MEMO + TANGGAL SUDAH LENGKAP
        |--------------------------------------------------------------------------
        */

        if (
            $pendingMemoNumber !== null
            &&
            $detectedMemoDate !== null
        ) {

            $currentMemoNumber =
                $pendingMemoNumber;

            $currentMemoDate =
                $detectedMemoDate;


            /*
            | Reset pending
            */

            $pendingMemoNumber = null;
            $pendingMemoDate   = null;


            /*
            | Baris memo tidak masuk data petani
            */

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Kalau hanya tanggal yang ditemukan
        |--------------------------------------------------------------------------
        */

        if (
            $detectedMemoDate !== null
        ) {

            $pendingMemoDate =
                $detectedMemoDate;


            /*
            | Kalau nomor memo sudah tersedia
            | langsung aktifkan memo baru.
            */

            if (
                $pendingMemoNumber !== null
            ) {

                $currentMemoNumber =
                    $pendingMemoNumber;

                $currentMemoDate =
                    $detectedMemoDate;


                $pendingMemoNumber = null;
                $pendingMemoDate   = null;
            }


            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | Kalau hanya nomor memo yang ditemukan
        |--------------------------------------------------------------------------
        */

        if (
            $detectedMemoNumber !== null
        ) {

            /*
            | Jangan masukkan baris memo
            | ke data petani.
            */

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | ================================================================
        | DETEKSI TOTAL
        |--------------------------------------------------------------------------
        */

        $isTotalRow = false;


        foreach ($rowValues as $value) {

            $normalizedValue =
                strtoupper(
                    trim(
                        preg_replace(
                            '/\s+/u',
                            ' ',
                            $value
                        )
                    )
                );


            if (
                $normalizedValue === 'TOTAL'
                ||
                str_starts_with(
                    $normalizedValue,
                    'TOTAL '
                )
                ||
                str_ends_with(
                    $normalizedValue,
                    ' TOTAL'
                )
            ) {

                $isTotalRow = true;

                break;
            }
        }


        if ($isTotalRow) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER MUNCUL LAGI
        |--------------------------------------------------------------------------
        */

        if (
            $this->isRepeatedHeaderRow(
                $row
            )
        ) {

            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | MAPPING HEADER → VALUE
        |--------------------------------------------------------------------------
        */

        $mapped = [];


        foreach (
            $headers as $column => $header
        ) {

            if ($header === '') {
                continue;
            }


            $mapped[$header] =
                $row[$column] ?? null;
        }


        /*
        |--------------------------------------------------------------------------
        | PASANG MEMO AKTIF
        |--------------------------------------------------------------------------
        */

        $mapped['nomor_memo'] =
            $currentMemoNumber;


        $mapped['tanggal_memo'] =
            $currentMemoDate;


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN DATA PETANI
        |--------------------------------------------------------------------------
        */

        if (
            $this->isRawExcelDataRow(
                $mapped
            )
        ) {

            $result[] =
                $mapped;
        }
    }


    return $result;
}
private function parseIndonesianMemoDate(
    mixed $day,
    mixed $month,
    mixed $year
): ?string {

    $months = [

        'januari'   => 1,
        'februari'  => 2,
        'maret'     => 3,
        'april'     => 4,
        'mei'       => 5,
        'juni'      => 6,
        'juli'      => 7,
        'agustus'   => 8,
        'september' => 9,
        'oktober'   => 10,
        'november'  => 11,
        'desember'  => 12,

    ];


    $month =
        strtolower(
            trim(
                (string) $month
            )
        );


    if (
        !isset(
            $months[$month]
        )
    ) {

        return null;
    }


    try {

        return \Carbon\Carbon::create(
            (int) $year,
            $months[$month],
            (int) $day
        )->format('Y-m-d');

    } catch (Throwable) {

        return null;
    }
}
/**
 * ============================================================
 * DETEKSI INFORMASI MEMO
 * ============================================================
 */
private function extractMemoInformation(
    string $text
): ?array {

    /*
    |--------------------------------------------------------------------------
    | Contoh:
    |
    | Memo No.: SG17D-SG17E-SBI/20260811.002
    | Tanggal 11 Agustus 2026
    |--------------------------------------------------------------------------
    */

    $pattern =
        '/Memo\s*No\.?\s*:?\s*(.*?)\s+Tanggal\s+(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})/iu';


    if (
        !preg_match(
            $pattern,
            $text,
            $matches
        )
    ) {

        return null;
    }


    $nomorMemo =
        trim($matches[1]);


    $day =
        (int) $matches[2];


    $monthName =
        strtolower(
            trim($matches[3])
        );


    $year =
        (int) $matches[4];


    /*
    |--------------------------------------------------------------------------
    | Bulan Indonesia
    |--------------------------------------------------------------------------
    */

    $months = [

        'januari' =>
            1,

        'februari' =>
            2,

        'maret' =>
            3,

        'april' =>
            4,

        'mei' =>
            5,

        'juni' =>
            6,

        'juli' =>
            7,

        'agustus' =>
            8,

        'september' =>
            9,

        'oktober' =>
            10,

        'november' =>
            11,

        'desember' =>
            12,

    ];


    /*
    |--------------------------------------------------------------------------
    | Support nama bulan Inggris juga
    |--------------------------------------------------------------------------
    */

    $months += [

        'january' =>
            1,

        'february' =>
            2,

        'march' =>
            3,

        'may' =>
            5,

        'june' =>
            6,

        'july' =>
            7,

        'august' =>
            8,

        'october' =>
            10,

        'december' =>
            12,

    ];


    if (
        !isset(
            $months[$monthName]
        )
    ) {

        return null;
    }


    try {

        $tanggalMemo =
            \Carbon\Carbon::create(
                $year,
                $months[$monthName],
                $day
            )->format('Y-m-d');

    } catch (Throwable) {

        return null;
    }


    return [

        'nomor_memo' =>
            $nomorMemo,

        'tanggal_memo' =>
            $tanggalMemo,

    ];
}
/**
 * ============================================================
 * CEK HEADER ULANG
 * ============================================================
 */
private function isRepeatedHeaderRow(
    array $row
): bool {

    $text =
        strtolower(
            implode(
                ' ',
                array_map(
                    fn ($value) =>
                        trim((string) $value),
                    $row
                )
            )
        );


    return
        str_contains(
            $text,
            'no spta'
        )
        &&
        str_contains(
            $text,
            'kualitas'
        );
}
/**
 * ============================================================
 * CEK DATA RAW EXCEL
 * ============================================================
 */
private function isRawExcelDataRow(
    array $row
): bool {

    $fields = [

        'no_spta',

        'no_kontrak',

        'kode_afdeling',

        'nama_asman',

        'petani',

        'nama_petani',

        'nama_pta',

        'kualitas',

    ];


    foreach ($fields as $field) {

        if (
            array_key_exists(
                $field,
                $row
            )
            &&
            trim(
                (string) (
                    $row[$field] ?? ''
                )
            ) !== ''
        ) {

            return true;
        }
    }


    return false;
}

    /**
     * ============================================================
     * CARI HEADER EXCEL SEBENARNYA
     * ============================================================
     */
    private function findHeaderRow(
        array $rows
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | Header yang umum pada file Prodis
        |--------------------------------------------------------------------------
        */

        $knownHeaders = [

            'no_spta',
            'nomor_spta',
            'spta',

            'no_petani',
            'nomor_petani',
            'kode_petani',

            'nama_petani',

            'no_kontrak',
            'nomor_kontrak',

            'kode_afdeling',
            'afdeling',

            'nama_asman',

            'kualitas',
            'mutu',
            'grade',

            'tanggal_selektor',
            'tgl_selektor',

            'tanggal_timbang',
            'tgl_timbang',

            'tanggal_meja_tebu',
            'tgl_meja_tebu',

        ];


        /*
        |--------------------------------------------------------------------------
        | Cari maksimal 30 baris pertama
        |--------------------------------------------------------------------------
        */

        foreach (
            $rows as $rowNumber => $row
        ) {

            /*
            | File Excel sering punya title / memo / keterangan
            | sebelum header.
            */

            $found = 0;


            foreach ($row as $cell) {

                if (
                    $cell === null ||
                    $cell === ''
                ) {
                    continue;
                }


                $normalized =
                    $this->normalizeHeader(
                        $cell
                    );


                /*
                |--------------------------------------------------------------------------
                | Alias ke canonical
                |--------------------------------------------------------------------------
                */

                $canonical =
                    $this->canonicalHeader(
                        $normalized
                    );


                if (
                    in_array(
                        $canonical,
                        $knownHeaders,
                        true
                    )
                ) {

                    $found++;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Minimal 2 header dikenali
            |--------------------------------------------------------------------------
            |
            | Supaya baris memo seperti:
            |
            | Memo No.: xxx Tanggal 27 Juli 2026
            |
            | tidak dianggap sebagai header.
            |--------------------------------------------------------------------------
            */

            if ($found >= 2) {

                return [

                    'row_number' =>
                        $rowNumber,

                    'row' =>
                        $row,

                ];
            }
        }


        return null;
    }


    /**
     * ============================================================
     * EXTRACT MEMO DARI HEADER / BAGIAN ATAS EXCEL
     * ============================================================
     */
    private function extractMemoMetadata(
        array $rows,
        int $headerRowNumber
    ): array {

        $nomorMemo = null;
        $tanggalMemo = null;


        /*
        |--------------------------------------------------------------------------
        | Hanya scan bagian sebelum header.
        |--------------------------------------------------------------------------
        */

        foreach (
            $rows as $rowNumber => $row
        ) {

            if (
                $rowNumber >= $headerRowNumber
            ) {
                break;
            }


            foreach ($row as $value) {

                if (
                    $value === null ||
                    trim((string) $value) === ''
                ) {
                    continue;
                }


                $text =
                    trim(
                        (string) $value
                    );


                /*
                |--------------------------------------------------------------------------
                | Cari Nomor Memo
                |--------------------------------------------------------------------------
                |
                | Contoh:
                |
                | Memo No.: SG17D-SG17E-SB/20260727.003
                |--------------------------------------------------------------------------
                */

                if (
                    empty($nomorMemo)
                ) {

                    $patterns = [

                        '/memo\s*no\.?\s*[:\-]?\s*(.+?)(?=\s+tanggal\b|$)/iu',

                        '/nomor\s*memo\s*[:\-]?\s*(.+?)(?=\s+tanggal\b|$)/iu',

                        '/no\.?\s*memo\s*[:\-]?\s*(.+?)(?=\s+tanggal\b|$)/iu',

                    ];


                    foreach (
                        $patterns as $pattern
                    ) {

                        if (
                            preg_match(
                                $pattern,
                                $text,
                                $matches
                            )
                        ) {

                            $nomorMemo =
                                trim(
                                    $matches[1]
                                );

                            break;
                        }
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | Cari tanggal memo
                |--------------------------------------------------------------------------
                |
                | Contoh:
                |
                | Tanggal 27 Juli 2026
                |--------------------------------------------------------------------------
                */

                if (
                    empty($tanggalMemo)
                ) {

                    $patterns = [

                        '/tanggal\s*[:\-]?\s*(\d{1,2}\s+[A-Za-zÀ-ÿ]+\s+\d{4})/iu',

                        '/tgl\.?\s*[:\-]?\s*(\d{1,2}\s+[A-Za-zÀ-ÿ]+\s+\d{4})/iu',

                    ];


                    foreach (
                        $patterns as $pattern
                    ) {

                        if (
                            preg_match(
                                $pattern,
                                $text,
                                $matches
                            )
                        ) {

                            $tanggalMemo =
                                $this->parseDate(
                                    $matches[1]
                                );

                            if ($tanggalMemo) {
                                break;
                            }
                        }
                    }
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback:
        |
        | Kadang tanggal dan memo ada di cell terpisah.
        |--------------------------------------------------------------------------
        */

        return [

            'nomor_memo' =>
                $nomorMemo,

            'tanggal_memo' =>
                $tanggalMemo,

        ];
    }


    /**
     * ============================================================
     * NORMALISASI HEADER
     * ============================================================
     */
    private function normalizeHeader(
        mixed $header
    ): string {

        $header =
            trim(
                strtolower(
                    (string) $header
                )
            );


        /*
        |--------------------------------------------------------------------------
        | Hilangkan karakter non ASCII
        |--------------------------------------------------------------------------
        */

        $header =
            Str::ascii(
                $header
            );


        /*
        |--------------------------------------------------------------------------
        | Normalisasi whitespace
        |--------------------------------------------------------------------------
        */

        $header =
            preg_replace(
                '/\s+/u',
                ' ',
                $header
            );


        /*
        |--------------------------------------------------------------------------
        | Ganti separator
        |--------------------------------------------------------------------------
        */

        $header =
            str_replace(
                [
                    '.',
                    ',',
                    ':',
                    ';',
                    '/',
                    '\\',
                    '-',
                    '(',
                    ')',
                    '[',
                    ']',
                    '%',
                ],
                ' ',
                $header
            );


        $header =
            preg_replace(
                '/\s+/u',
                '_',
                trim($header)
            );


        return trim(
            $header,
            '_'
        );
    }
private function isTotalRow(array $row): bool
{
    foreach ($row as $value) {

        if ($value === null) {
            continue;
        }

        $value = strtoupper(
            trim(
                preg_replace(
                    '/\s+/',
                    ' ',
                    (string) $value
                )
            )
        );

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        if (
            $value === 'TOTAL' ||
            str_starts_with($value, 'TOTAL ') ||
            str_ends_with($value, ' TOTAL')
        ) {
            return true;
        }
    }

    return false;
}

    /**
     * ============================================================
     * CANONICAL HEADER
     * ============================================================
     *
     * Digunakan khusus untuk mendeteksi header sebenarnya.
     */
    private function canonicalHeader(
        string $header
    ): string {

        $aliases = [

    /*
    |--------------------------------------------------------------------------
    | MEMO
    |--------------------------------------------------------------------------
    */

    'no_memo' =>
        'nomor_memo',

    'nomor_memo_qa' =>
        'nomor_memo',

    'no_memo_qa' =>
        'nomor_memo',

    'memo_qa' =>
        'nomor_memo',


    /*
    |--------------------------------------------------------------------------
    | PETANI
    |--------------------------------------------------------------------------
    */

    'kodepetani' =>
        'no_petani',

    'kode_petani' =>
        'no_petani',

    'nomor_petani' =>
        'no_petani',

    'id_petani' =>
        'no_petani',

    'idpetani' =>
        'no_petani',

    'no_petani_tebu' =>
        'no_petani',


    'namapetani' =>
        'nama_petani',

    'nama_petani_tebu' =>
        'nama_petani',

    /*
     * INI YANG PENTING
     */

    'petani' =>
        'nama_petani',


    /*
    |--------------------------------------------------------------------------
    | SPTA
    |--------------------------------------------------------------------------
    */

    'nomor_spta' =>
        'no_spta',

    'nomor_spta_tebu' =>
        'no_spta',

    'no_spta_tebu' =>
        'no_spta',

    'no_spta_' =>
        'no_spta',


    /*
    |--------------------------------------------------------------------------
    | PTA
    |--------------------------------------------------------------------------
    */

    'kodepta' =>
        'kode_pta',

    'kode_pta_wilayah' =>
        'kode_pta',

    'kode_wilayah' =>
        'kode_pta',

    'kode_area' =>
        'kode_pta',


    'namapta' =>
        'nama_pta',

    'nama_pta_wilayah' =>
        'nama_pta',

    'wilayah_pta' =>
        'nama_pta',

    'nama_wilayah' =>
        'nama_pta',

    'wilayah' =>
        'nama_pta',

    'area' =>
        'nama_pta',

    /*
     * INI JUGA PENTING
     */

    'pta' =>
        'nama_pta',


    /*
    |--------------------------------------------------------------------------
    | KONTRAK
    |--------------------------------------------------------------------------
    */

    'nomor_kontrak' =>
        'no_kontrak',

    'nomor_spk' =>
        'no_kontrak',

    'no_spk' =>
        'no_kontrak',


    /*
    |--------------------------------------------------------------------------
    | AFD
    |--------------------------------------------------------------------------
    */

    'kode_afd' =>
        'kode_afdeling',

    'afd' =>
        'kode_afdeling',


    /*
    |--------------------------------------------------------------------------
    | ASMAN
    |--------------------------------------------------------------------------
    */

    'asman' =>
        'nama_asman',

    'nama_asisten' =>
        'nama_asman',

    'nama_assman' =>
        'nama_asman',

    'assman' =>
        'nama_asman',


    /*
    |--------------------------------------------------------------------------
    | MUTU
    |--------------------------------------------------------------------------
    */

    'jenis_mutu' =>
        'kualitas',

    'jenis_kualitas' =>
        'kualitas',

    'kelas_mutu' =>
        'kualitas',

    'kategori_mutu' =>
        'kualitas',

    'grade' =>
        'kualitas',


    /*
    |--------------------------------------------------------------------------
    | TANGGAL
    |--------------------------------------------------------------------------
    */

    'tgl_jam_meja_tebu' =>
    'tgl_jam_meja_tebu',

'tgl_meja_tebu' =>
    'tanggal_meja_tebu',

'tanggal_meja' =>
    'tanggal_meja_tebu',

'tgl_meja' =>
    'tanggal_meja_tebu',

'tanggal_bongkar' =>
    'tanggal_meja_tebu',

'tgl_bongkar' =>
    'tanggal_meja_tebu',
        'tanggal_meja_tebu',


    /*
    |--------------------------------------------------------------------------
    | KETERANGAN
    |--------------------------------------------------------------------------
    */

    'catatan' =>
        'keterangan',

    'keterangan_memo' =>
        'keterangan',

    'ket' =>
        'keterangan',
];


        return $aliases[$header]
            ?? $header;
    }


    /**
     * ============================================================
     * NORMALISASI ROW
     * ============================================================
     */
   /**
 * ============================================================
 * NORMALISASI ROW
 * ============================================================
 */
private function normalizeRow(
    array $row,
    int $rowNumber
): array {

    /*
    |--------------------------------------------------------------------------
    | MEJA TEBU
    |--------------------------------------------------------------------------
    |
    | Excel bisa menyimpan:
    |
    | TGL JAM MEJA TEBU
    | 8/14/2026 14:54
    |
    | Maka tanggal dan jam harus dipisahkan sebelum disimpan
    | ke database.
    |
    */

    $mejaTebuDateTime =
        $this->parseDateTimeToDateAndTime(
            $this->getValue(
                $row,
                [
                    'tgl_jam_meja_tebu',
                    'tanggal_meja_tebu',
                    'tgl_meja_tebu',
                    'tanggal_meja',
                    'tgl_meja',
                    'tanggal_bongkar',
                    'tgl_bongkar',
                    'jam_meja_tebu',
                ]
            )
        );

    $tanggalMejaTebu =
        $mejaTebuDateTime['date'];

    $jamMejaTebu =
        $mejaTebuDateTime['time'];


    return [

        /*
        |--------------------------------------------------------------------------
        | MEMO
        |--------------------------------------------------------------------------
        */

        'nomor_memo' =>
            $this->getValue(
                $row,
                [
                    'nomor_memo',
                    'memo',
                ]
            ),

        'tanggal_memo' =>
            $this->parseDate(
                $this->getValue(
                    $row,
                    [
                        'tanggal_memo',
                    ]
                )
            ),


        /*
        |--------------------------------------------------------------------------
        | PTA
        |--------------------------------------------------------------------------
        */

        'kode_pta' =>
            $this->getValue(
                $row,
                [
                    'kode_pta',
                ]
            ),

        'nama_pta' =>
            $this->getValue(
                $row,
                [
                    'nama_pta',
                    'pta',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | SPTA
        |--------------------------------------------------------------------------
        */

        'no_spta' =>
            $this->getValue(
                $row,
                [
                    'no_spta',
                    'spta',
                    'nomor_spta',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | KONTRAK
        |--------------------------------------------------------------------------
        */

        'no_kontrak' =>
            $this->getValue(
                $row,
                [
                    'no_kontrak',
                    'kontrak',
                    'nomor_kontrak',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | AFD
        |--------------------------------------------------------------------------
        */

        'kode_afdeling' =>
            $this->getValue(
                $row,
                [
                    'kode_afdeling',
                    'kode_afd',
                    'afd',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | ASMAN
        |--------------------------------------------------------------------------
        */

        'nama_asman' =>
            $this->getValue(
                $row,
                [
                    'nama_asman',
                    'asman',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | PETANI
        |--------------------------------------------------------------------------
        */

        'no_petani' =>
            $this->getValue(
                $row,
                [
                    'no_petani',
                    'kode_petani',
                    'no_petak_kode_blok',
                    'no_petak',
                    'kode_blok',
                ]
            ),

        'nama_petani' =>
            $this->getValue(
                $row,
                [
                    'nama_petani',
                    'petani',
                    'namapetani',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | BLOK
        |--------------------------------------------------------------------------
        */

        'deskripsi_blok' =>
            $this->getValue(
                $row,
                [
                    'deskripsi_blok',
                    'blok',
                    'uraian',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | KENDARAAN
        |--------------------------------------------------------------------------
        */

        'nopol' =>
            $this->getValue(
                $row,
                [
                    'nopol',
                    'no_polisi',
                    'nomor_polisi',
                    'plat_nomor',
                    'no_kendaraan',
                    'nomor_kendaraan',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | MUTU
        |--------------------------------------------------------------------------
        */

        'kualitas' =>
            strtoupper(
                trim(
                    (string) (
                        $this->getValue(
                            $row,
                            [
                                'kualitas',
                                'mutu',
                                'jenis_mutu',
                            ]
                        ) ?? ''
                    )
                )
            ),


        /*
        |--------------------------------------------------------------------------
        | SELEKTOR
        |--------------------------------------------------------------------------
        */

        'tanggal_selektor' =>
            $this->parseDate(
                $this->getValue(
                    $row,
                    [
                        'tanggal_selektor',
                        'tgl_selektor',
                        'tanggal_seleksi',
                        'tgl_seleksi',
                    ]
                )
            ),

        'jam_selektor' =>
            $this->getValue(
                $row,
                [
                    'jam_selektor',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | TIMBANG
        |--------------------------------------------------------------------------
        */

        'tanggal_timbang' =>
            $this->parseDate(
                $this->getValue(
                    $row,
                    [
                        'tanggal_timbang',
                        'tgl_timbang',
                        'tanggal_timbang_tebu',
                    ]
                )
            ),

        'jam_timbang' =>
            $this->getValue(
                $row,
                [
                    'jam_timbang',
                ]
            ),


        /*
        |--------------------------------------------------------------------------
        | MEJA TEBU
        |--------------------------------------------------------------------------
        |
        | Jika Excel:
        |
        | TGL JAM MEJA TEBU
        | 8/14/2026 14:54
        |
        | Maka:
        |
        | tanggal_meja_tebu = 2026-08-14
        | jam_meja_tebu     = 14:54:00
        |
        */

        'tanggal_meja_tebu' =>
            $tanggalMejaTebu,

        'jam_meja_tebu' =>
            $jamMejaTebu,


        /*
        |--------------------------------------------------------------------------
        | SKORSING
        |--------------------------------------------------------------------------
        */

        'durasi_skorsing' =>
            $this->getValue(
                $row,
                [
                    'durasi_skorsing',
                ]
            ),

        'tanggal_skorsing_mulai' =>
            $this->parseDate(
                $this->getValue(
                    $row,
                    [
                        'tanggal_skorsing_mulai',
                    ]
                )
            ),

        'tanggal_skorsing_selesai' =>
            $this->parseDate(
                $this->getValue(
                    $row,
                    [
                        'tanggal_skorsing_selesai',
                    ]
                )
            ),


        /*
        |--------------------------------------------------------------------------
        | KETERANGAN
        |--------------------------------------------------------------------------
        */

        'keterangan' =>
            $this->getValue(
                $row,
                [
                    'keterangan',
                    'catatan',
                    'ket',
                ]
            ),
    ];
}
    /**
     * ============================================================
     * AMBIL VALUE
     * ============================================================
     */
    private function getValue(
        array $row,
        array $keys
    ): mixed {

        foreach ($keys as $key) {

            if (
                !array_key_exists(
                    $key,
                    $row
                )
            ) {
                continue;
            }


            $value =
                $row[$key];


            if (
                is_string($value)
            ) {

                $value =
                    trim($value);
            }


            if (
                $value !== null &&
                $value !== ''
            ) {

                return $value;
            }
        }


        return null;
    }


    /**
     * ============================================================
     * PARSE TANGGAL
     * ============================================================
     */
    /**
 * Parse tanggal Excel / string secara aman.
 *
 * Prioritas:
 * 1. Excel serial date
 * 2. Format Indonesia dd/mm/yyyy
 * 3. Format Indonesia dd-mm-yyyy
 * 4. Format Indonesia dd.mm.yyyy
 * 5. Format datetime Indonesia
 * 6. Format ISO Y-m-d
 */
/**
 * ============================================================
 * PARSE DATETIME MENJADI TANGGAL + JAM
 * ============================================================
 *
 * Contoh input:
 * 8/14/2026 14:54
 *
 * Output:
 * [
 *     'date' => '2026-08-14',
 *     'time' => '14:54:00',
 * ]
 */
private function parseDateTimeToDateAndTime(
    mixed $value
): array {

    if ($value === null || $value === '') {
        return [
            'date' => null,
            'time' => null,
        ];
    }

    /*
     |--------------------------------------------------------------------------
     | 1. EXCEL SERIAL DATETIME
     |--------------------------------------------------------------------------
     */
    if (
        is_numeric($value) &&
        (float) $value > 20000
    ) {
        try {

            $dateTime =
                ExcelDate::excelToDateTimeObject(
                    (float) $value
                );

            return [
                'date' => $dateTime->format('Y-m-d'),
                'time' => $dateTime->format('H:i:s'),
            ];

        } catch (Throwable) {

            return [
                'date' => null,
                'time' => null,
            ];
        }
    }

    /*
     |--------------------------------------------------------------------------
     | 2. STRING DATETIME
     |--------------------------------------------------------------------------
     */

    $value = trim((string) $value);

    if ($value === '') {
        return [
            'date' => null,
            'time' => null,
        ];
    }

    /*
     |--------------------------------------------------------------------------
     | Format yang umum dari Excel
     |--------------------------------------------------------------------------
     */

    $formats = [

        // US format
        'm/d/Y H:i',
        'm/d/Y H:i:s',

        // Indonesia format
        'd/m/Y H:i',
        'd/m/Y H:i:s',

        'd-m-Y H:i',
        'd-m-Y H:i:s',

        'd.m.Y H:i',
        'd.m.Y H:i:s',

        // ISO
        'Y-m-d H:i',
        'Y-m-d H:i:s',
    ];

    foreach ($formats as $format) {

        try {

            $dateTime =
                \DateTime::createFromFormat(
                    $format,
                    $value
                );

            if (
                $dateTime !== false &&
                $dateTime->format($format) === $value
            ) {

                return [
                    'date' => $dateTime->format('Y-m-d'),
                    'time' => $dateTime->format('H:i:s'),
                ];
            }

        } catch (Throwable) {

            // lanjut format berikutnya
        }
    }

    /*
     |--------------------------------------------------------------------------
     | 3. FALLBACK CARBON
     |--------------------------------------------------------------------------
     */

    try {

        $dateTime =
            \Carbon\Carbon::parse($value);

        return [
            'date' => $dateTime->format('Y-m-d'),
            'time' => $dateTime->format('H:i:s'),
        ];

    } catch (Throwable) {

        return [
            'date' => null,
            'time' => null,
        ];
    }
}
private function parseDate(
    mixed $value
): ?string {

    if (
        $value === null ||
        $value === ''
    ) {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | 1. EXCEL SERIAL DATE
    |--------------------------------------------------------------------------
    */

    if (
        is_numeric($value) &&
        (float) $value > 20000
    ) {

        try {

            return ExcelDate::excelToDateTimeObject(
                (float) $value
            )->format('Y-m-d');

        } catch (Throwable) {

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Bersihkan value
    |--------------------------------------------------------------------------
    */

    $value = trim(
        (string) $value
    );

    if ($value === '') {
        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | 2. FORMAT INDONESIA: DD/MM/YYYY
    |--------------------------------------------------------------------------
    |
    | Contoh:
    | 03/08/2026
    | 09/08/2026
    | 10/08/2026
    | 11/08/2026
    | 12/08/2026
    |
    */

    $formats = [
        'd/m/Y',
        'd-m-Y',
        'd.m.Y',

        'd/m/Y H:i',
        'd-m-Y H:i',
        'd.m.Y H:i',

        'd/m/Y H:i:s',
        'd-m-Y H:i:s',
        'd.m.Y H:i:s',

        'Y-m-d',
        'Y-m-d H:i',
        'Y-m-d H:i:s',
    ];


    foreach ($formats as $format) {

        try {

            $date = \DateTime::createFromFormat(
                $format,
                $value
            );

            if (
                $date !== false &&
                $date->format($format) === $value
            ) {

                return $date->format('Y-m-d');
            }

        } catch (Throwable) {

            // lanjut ke format berikutnya

        }
    }


    /*
    |--------------------------------------------------------------------------
    | 3. FALLBACK
    |--------------------------------------------------------------------------
    */

    try {

        return \Carbon\Carbon::parse(
            $value
        )->format('Y-m-d');

    } catch (Throwable) {

        return null;
    }
}


    /**
     * ============================================================
     * CEK ROW EXCEL MENTAH KOSONG
     * ============================================================
     */
    private function rawExcelRowIsEmpty(
        array $row
    ): bool {

        foreach ($row as $value) {

            if (
                $value !== null &&
                trim(
                    (string) $value
                ) !== ''
            ) {

                return false;
            }
        }


        return true;
    }


    /**
     * ============================================================
     * CEK BARIS DATA KOSONG
     * ============================================================
     */
    private function isEmptyRow(
        array $row
    ): bool {

        $fields = [

            'nama_petani',
            'no_petani',
            'no_spta',
            'nama_pta',
            'kode_pta',
            'kualitas',

        ];


        foreach ($fields as $field) {

            $value =
                $row[$field] ?? null;


            if (
                $value !== null &&
                trim(
                    (string) $value
                ) !== ''
            ) {

                return false;
            }
        }


        return true;
    }
    
}
