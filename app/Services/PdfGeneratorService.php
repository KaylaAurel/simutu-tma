<?php

namespace App\Services;

use App\Models\Pemberitahuan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PdfGeneratorService
{
    /**
     * Generate PDF pemberitahuan.
     *
     * @return string
     */
    public function generate(
        Pemberitahuan $pemberitahuan
    ): string {

        $pemberitahuan->load([
            'creator',
            'details.pta',
            'details.mutuD.photos',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Nama file
        |--------------------------------------------------------------------------
        */

        $safeNumber =
            Str::slug(
                $pemberitahuan->nomor
            );

        $fileName =
            'pemberitahuan-'
            . $safeNumber
            . '.pdf';

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'pemberitahuan.pdf',
            [
                'pemberitahuan' =>
                    $pemberitahuan,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Ukuran kertas
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        $path =
            'dokumentasi/pdf/'
            . $fileName;

        Storage::disk('public')
            ->put(
                $path,
                $pdf->output()
            );

        return $path;
    }

    /**
     * Generate PDF dan langsung download.
     */
    public function download(
        Pemberitahuan $pemberitahuan
    ) {

        $pemberitahuan->load([
            'creator',
            'details.pta',
            'details.mutuD.photos',
        ]);

        $safeNumber =
            Str::slug(
                $pemberitahuan->nomor
            );

        $fileName =
            'pemberitahuan-'
            . $safeNumber
            . '.pdf';

        return Pdf::loadView(
            'pemberitahuan.pdf',
            [
                'pemberitahuan' =>
                    $pemberitahuan,
            ]
        )
            ->setPaper(
                'A4',
                'portrait'
            )
            ->download(
                $fileName
            );
    }

    /**
     * Mendapatkan URL dokumentasi PDF.
     */
    public function url(
        string $path
    ): string {

        return Storage::disk('public')
            ->url($path);
    }

    /**
     * Hapus dokumentasi PDF.
     */
    public function delete(
        string $path
    ): bool {

        if (
            Storage::disk('public')
                ->exists($path)
        ) {

            return Storage::disk('public')
                ->delete($path);
        }

        return false;
    }
}