<?php

namespace App\Services;

use App\Models\MutuD;
use App\Models\Pemberitahuan;
use App\Models\PemberitahuanDetail;
use App\Models\PTA;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class PemberitahuanService
{
    protected WhatsAppService $whatsappService;

    public function __construct(
        WhatsAppService $whatsappService
    ) {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Membuat draft pemberitahuan berdasarkan Mutu D.
     */
    public function createFromMutuD(
        array $mutuIds,
        int $userId
    ): Pemberitahuan {
        return DB::transaction(function () use (
            $mutuIds,
            $userId
        ) {
            /*
            |--------------------------------------------------------------------------
            | Ambil Mutu D yang valid
            |--------------------------------------------------------------------------
            */

            $mutuDs = MutuD::query()
                ->with([
                    'memoProdis',
                    'pta',
                ])
                ->whereIn('id', $mutuIds)
                ->mutuD()
                ->whereDoesntHave('pemberitahuanDetails')
                ->get();

            if ($mutuDs->isEmpty()) {
                throw new \RuntimeException(
                    'Tidak ada data Mutu D yang dapat diproses.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Buat nomor pemberitahuan
            |--------------------------------------------------------------------------
            */

            $nomor = $this->generateNumber();

            /*
            |--------------------------------------------------------------------------
            | Tentukan PTA dari data Mutu D
            |--------------------------------------------------------------------------
            */

            $ptaIds = $mutuDs
                ->pluck('pta_id')
                ->filter()
                ->unique()
                ->values();

            if ($ptaIds->isEmpty()) {
                throw new \RuntimeException(
                    'Data Mutu D yang dipilih belum memiliki PTA.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Untuk saat ini satu pemberitahuan menggunakan satu PTA
            |--------------------------------------------------------------------------
            */

            if ($ptaIds->count() > 1) {
                throw new \RuntimeException(
                    'Data Mutu D yang dipilih memiliki lebih dari satu PTA. '
                    . 'Silakan pilih data Mutu D dengan PTA yang sama.'
                );
            }

            $ptaId = $ptaIds->first();

            /*
            |--------------------------------------------------------------------------
            | Buat pemberitahuan
            |--------------------------------------------------------------------------
            */

            $pemberitahuan = Pemberitahuan::create([

                // NOMOR PEMBERITAHUAN
                'nomor' => $nomor,

                // PTA UTAMA
                'pta_id' => $ptaId,

                // TANGGAL
                'tanggal_pemberitahuan' => today(),

                // JUDUL
                'judul' => 'Pemberitahuan Mutu D Tebu',

                // ISI AWAL
                'isi_pesan' => '',

                // PDF
                'nama_file_pdf' => null,
                'path_pdf' => null,

                // STATUS
                'status_pesan' => 'draft',
                'status_pdf' => 'belum_dibuat',

                // WAKTU KIRIM
                'waktu_kirim_pesan' => null,
                'waktu_kirim_pdf' => null,

                // PEMBUAT
                'created_by' => $userId,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Buat detail pemberitahuan
            |--------------------------------------------------------------------------
            */

            foreach ($mutuDs as $mutuD) {

                $pta = $mutuD->pta;

                /*
                |--------------------------------------------------------------------------
                | Mutu D tanpa PTA tidak dibuatkan detail
                |--------------------------------------------------------------------------
                */

                if (!$pta) {
                    continue;
                }

                PemberitahuanDetail::create([
                    'pemberitahuan_id' => $pemberitahuan->id,

                    'pta_id' => $pta->id,

                    'mutu_d_id' => $mutuD->id,

                    'status' => 'pending',

                    'sent_at' => null,

                    'response' => null,

                    'message_id' => null,

                    'error_message' => null,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Tandai Mutu D sedang diproses
                |--------------------------------------------------------------------------
                */

                $mutuD->update([
                    'status_pemberitahuan' => 'diproses',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Pastikan ada detail
            |--------------------------------------------------------------------------
            */

            $pemberitahuan->load('details');

            if ($pemberitahuan->details->isEmpty()) {
                throw new \RuntimeException(
                    'Tidak ada Mutu D yang memiliki PTA untuk dibuatkan pemberitahuan.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Generate preview pesan
            |--------------------------------------------------------------------------
            */

            $preview = $this->buildPreviewText(
                $pemberitahuan->fresh()
            );

            $pemberitahuan->update([
                'isi_pesan' => $preview,
            ]);

            return $pemberitahuan->fresh();
        });
    }

    /**
     * Mengirim pemberitahuan melalui Fonnte.
     */
    public function sendViaFonnte(
        Pemberitahuan $pemberitahuan
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Load data yang dibutuhkan
        |--------------------------------------------------------------------------
        */

        $pemberitahuan->load([
            'details.pta',
            'details.mutuD.photos',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi detail
        |--------------------------------------------------------------------------
        */

        if ($pemberitahuan->details->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Tidak ada detail pemberitahuan.',
                'sent' => 0,
                'failed' => 0,
                'total' => 0,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Tandai sedang diproses
        |--------------------------------------------------------------------------
        */

        $pemberitahuan->update([
            'status_pesan' => 'processing',
        ]);

        $sent = 0;
        $failed = 0;

        /*
        |--------------------------------------------------------------------------
        | Kelompokkan berdasarkan PTA
        |--------------------------------------------------------------------------
        */

        $groups = $pemberitahuan
            ->details
            ->groupBy('pta_id');

        foreach ($groups as $details) {

            $pta = $details
                ->first()
                ->pta;

            /*
            |--------------------------------------------------------------------------
            | PTA tidak ditemukan
            |--------------------------------------------------------------------------
            */

            if (!$pta) {

                $failed++;

                foreach ($details as $detail) {

                    $detail->update([
                        'status' => 'failed',

                        'error_message' =>
                            'Data PTA tidak ditemukan.',
                    ]);

                    $detail->mutuD?->update([
                        'status_pemberitahuan' => 'gagal',
                    ]);
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Nomor WhatsApp tidak tersedia
            |--------------------------------------------------------------------------
            */

            if (empty($pta->no_whatsapp)) {

                $failed++;

                foreach ($details as $detail) {

                    $detail->update([
                        'status' => 'failed',

                        'error_message' =>
                            'Nomor WhatsApp PTA belum tersedia.',
                    ]);

                    $detail->mutuD?->update([
                        'status_pemberitahuan' => 'gagal',
                    ]);
                }

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Buat pesan khusus PTA
            |--------------------------------------------------------------------------
            */

            $message = $this->buildPtaMessage(
                $pta,
                $details
            );

            /*
            |--------------------------------------------------------------------------
            | Ambil foto pertama
            |--------------------------------------------------------------------------
            */

            $photo = $details
                ->flatMap(
                    fn ($detail) =>
                        $detail->mutuD?->photos ?? collect()
                )
                ->first();

            try {

                /*
                |--------------------------------------------------------------------------
                | Kirim pesan
                |--------------------------------------------------------------------------
                */

                if ($photo) {

                    $result =
                        $this->whatsappService->sendStorageFile(
                            $pta->no_whatsapp,
                            $message,
                            $photo->file_path
                        );

                } else {

                    $result =
                        $this->whatsappService->sendMessage(
                            $pta->no_whatsapp,
                            $message
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Simpan hasil pengiriman ke setiap detail
                |--------------------------------------------------------------------------
                */

                foreach ($details as $detail) {

                    if (!empty($result['success'])) {

                        $detail->update([

                            'status' => 'sent',

                            'sent_at' => now(),

                            'response' => json_encode(
                                $result['response'] ?? null,
                                JSON_UNESCAPED_UNICODE
                            ),

                            'message_id' =>
                                $result['id'] ?? null,

                            'error_message' => null,
                        ]);

                        $detail->mutuD?->update([
                            'status_pemberitahuan' =>
                                'sudah_diberitahukan',
                        ]);

                    } else {

                        $detail->update([

                            'status' => 'failed',

                            'response' => json_encode(
                                $result['response'] ?? null,
                                JSON_UNESCAPED_UNICODE
                            ),

                            'message_id' =>
                                $result['id'] ?? null,

                            'error_message' =>
                                $result['message']
                                ?? 'Pengiriman gagal.',
                        ]);

                        $detail->mutuD?->update([
                            'status_pemberitahuan' => 'gagal',
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Hitung PTA berhasil / gagal
                |--------------------------------------------------------------------------
                */

                if (!empty($result['success'])) {

                    $sent++;

                } else {

                    $failed++;
                }

            } catch (Throwable $e) {

                $failed++;

                Log::error(
                    'SIMUTU-TMA Fonnte notification failed',
                    [
                        'pemberitahuan_id' =>
                            $pemberitahuan->id,

                        'pta_id' =>
                            $pta->id,

                        'error' =>
                            $e->getMessage(),
                    ]
                );

                foreach ($details as $detail) {

                    $detail->update([

                        'status' => 'failed',

                        'error_message' =>
                            $e->getMessage(),
                    ]);

                    $detail->mutuD?->update([
                        'status_pemberitahuan' => 'gagal',
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Tentukan status pemberitahuan
        |--------------------------------------------------------------------------
        |
        | Migration baru hanya memiliki:
        |
        | draft
        | processing
        | terkirim
        | gagal
        |
        | Karena belum ada status "partial", jika minimal satu
        | PTA berhasil maka status utama dianggap "terkirim".
        |--------------------------------------------------------------------------
        */

        if ($sent > 0) {

            $statusPesan = 'terkirim';

        } else {

            $statusPesan = 'gagal';
        }

        /*
        |--------------------------------------------------------------------------
        | Update status pemberitahuan
        |--------------------------------------------------------------------------
        */

        $pemberitahuan->update([

            'status_pesan' => $statusPesan,

            'waktu_kirim_pesan' =>
                $sent > 0
                    ? now()
                    : null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Return hasil pengiriman
        |--------------------------------------------------------------------------
        */

        return [

            'success' => $sent > 0,

            'message' =>
                "Pengiriman selesai. Berhasil: {$sent} PTA, gagal: {$failed} PTA.",

            'sent' => $sent,

            'failed' => $failed,

            'total' => $groups->count(),
        ];
    }

    /**
     * Membuat preview pesan umum.
     *
     * Preview ini digunakan pada halaman detail pemberitahuan.
     */
    public function buildPreviewText(
        Pemberitahuan $pemberitahuan
    ): string {

        $pemberitahuan->load([
            'details.pta',
            'details.mutuD',
        ]);

        $lines = [];

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $lines[] =
            '📢 *PEMBERITAHUAN MUTU D TEBU*';

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | NOMOR
        |--------------------------------------------------------------------------
        */

        $lines[] =
            "Nomor: {$pemberitahuan->nomor}";

        /*
        |--------------------------------------------------------------------------
        | TANGGAL
        |--------------------------------------------------------------------------
        */

        if ($pemberitahuan->tanggal_pemberitahuan) {

            $tanggalPemberitahuan =
                $pemberitahuan->tanggal_pemberitahuan;

            if (
                $tanggalPemberitahuan instanceof Carbon
            ) {

                $tanggalFormatted =
                    $tanggalPemberitahuan->format('d/m/Y');

            } else {

                $tanggalFormatted =
                    Carbon::parse(
                        $tanggalPemberitahuan
                    )->format('d/m/Y');
            }

            $lines[] =
                'Tanggal: ' . $tanggalFormatted;
        }

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | PEMBUKA
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Dengan hormat,';

        $lines[] = '';

        $lines[] =
            'Kami menyampaikan pemberitahuan terkait hasil identifikasi kualitas tebu yang tercatat dalam sistem SIMUTU-TMA dan termasuk dalam kategori *Mutu D*.';

        $lines[] = '';

        $lines[] =
            'Informasi berikut disampaikan sebagai bahan perhatian dan tindak lanjut Bapak/Ibu PTA sesuai dengan ketentuan yang berlaku.';

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | KELOMPOKKAN BERDASARKAN PTA
        |--------------------------------------------------------------------------
        */

        $groups = $pemberitahuan
            ->details
            ->groupBy('pta_id');

        foreach ($groups as $details) {

            $pta = $details
                ->first()
                ->pta;

            if (!$pta) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | PTA
            |--------------------------------------------------------------------------
            */

            $lines[] =
                "📍 *PTA {$pta->nama_pta}*";

            $lines[] = '';

            $number = 1;

            foreach ($details as $detail) {

                $mutuD = $detail->mutuD;

                if (!$mutuD) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DATA PETANI
                |--------------------------------------------------------------------------
                */

                $lines[] =
                    "*{$number}. {$mutuD->nama_petani}*";

                if ($mutuD->no_petani) {

                    $lines[] =
                        "No. Petani: {$mutuD->no_petani}";
                }

                if ($mutuD->no_spta) {

                    $lines[] =
                        "SPTA: {$mutuD->no_spta}";
                }

                if ($mutuD->no_kontrak) {

                    $lines[] =
                        "No. Kontrak: {$mutuD->no_kontrak}";
                }

                if ($mutuD->nopol) {

                    $lines[] =
                        "No. Polisi: {$mutuD->nopol}";
                }

                if ($mutuD->deskripsi_blok) {

                    $lines[] =
                        "Blok: {$mutuD->deskripsi_blok}";
                }

                if ($mutuD->tanggal_meja_tebu) {

                    $tanggalMejaTebu =
                        $mutuD->tanggal_meja_tebu;

                    if (
                        $tanggalMejaTebu instanceof Carbon
                    ) {

                        $tanggalFormatted =
                            $tanggalMejaTebu->format('d/m/Y');

                    } else {

                        $tanggalFormatted =
                            Carbon::parse(
                                $tanggalMejaTebu
                            )->format('d/m/Y');
                    }

                    $lines[] =
                        'Tanggal Meja Tebu: '
                        . $tanggalFormatted;
                }

                if ($mutuD->jam_meja_tebu) {

                    $lines[] =
                        "Jam Meja Tebu: {$mutuD->jam_meja_tebu}";
                }

                if ($mutuD->durasi_skorsing) {

                    $lines[] =
                        "Durasi Skorsing: {$mutuD->durasi_skorsing} hari";
                }

                $lines[] = '';

                $number++;
            }

            $lines[] = '';
        }

        /*
        |--------------------------------------------------------------------------
        | PENUTUP
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Kami mohon bantuan Bapak/Ibu PTA untuk dapat memperhatikan dan menindaklanjuti informasi tersebut sesuai dengan prosedur dan ketentuan yang berlaku.';

        $lines[] = '';

        $lines[] =
            'Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.';

        $lines[] = '';

        $lines[] =
            '_SIMUTU-TMA_';

        return implode("\n", $lines);
    }

    /**
     * Membuat pesan khusus untuk satu PTA.
     *
     * Pesan dibuat lebih sopan dan informatif karena ditujukan
     * langsung kepada Bapak/Ibu PTA.
     */
    private function buildPtaMessage(
        PTA $pta,
        $details
    ): string {

        $lines = [];

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        $lines[] =
            '📢 *PEMBERITAHUAN MUTU D TEBU*';

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | SAPAAN PTA
        |--------------------------------------------------------------------------
        */

        $lines[] =
            "Yth. Bapak/Ibu PTA *{$pta->nama_pta}*,";

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | PEMBUKA
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Dengan hormat,';

        $lines[] = '';

        $lines[] =
            'Kami menyampaikan pemberitahuan terkait hasil identifikasi kualitas tebu yang tercatat dalam sistem SIMUTU-TMA dan termasuk dalam kategori *Mutu D*.';

        $lines[] = '';

        $lines[] =
            'Informasi ini disampaikan sebagai bahan perhatian dan tindak lanjut Bapak/Ibu PTA sesuai dengan ketentuan dan prosedur yang berlaku.';

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | DATA MUTU D
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Berikut rincian data yang perlu menjadi perhatian:';

        $lines[] = '';

        $number = 1;

        foreach ($details as $detail) {

            $mutuD = $detail->mutuD;

            if (!$mutuD) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS PETANI
            |--------------------------------------------------------------------------
            */

            $lines[] =
                "*{$number}. {$mutuD->nama_petani}*";

            if ($mutuD->no_petani) {

                $lines[] =
                    "No. Petani: {$mutuD->no_petani}";
            }

            /*
            |--------------------------------------------------------------------------
            | SPTA
            |--------------------------------------------------------------------------
            */

            if ($mutuD->no_spta) {

                $lines[] =
                    "SPTA: {$mutuD->no_spta}";
            }

            /*
            |--------------------------------------------------------------------------
            | KONTRAK
            |--------------------------------------------------------------------------
            */

            if ($mutuD->no_kontrak) {

                $lines[] =
                    "No. Kontrak: {$mutuD->no_kontrak}";
            }

            /*
            |--------------------------------------------------------------------------
            | NOMOR POLISI
            |--------------------------------------------------------------------------
            */

            if ($mutuD->nopol) {

                $lines[] =
                    "No. Polisi: {$mutuD->nopol}";
            }

            /*
            |--------------------------------------------------------------------------
            | BLOK
            |--------------------------------------------------------------------------
            */

            if ($mutuD->deskripsi_blok) {

                $lines[] =
                    "Blok: {$mutuD->deskripsi_blok}";
            }

            /*
            |--------------------------------------------------------------------------
            | TANGGAL MEJA TEBU
            |--------------------------------------------------------------------------
            */

            if ($mutuD->tanggal_meja_tebu) {

                $tanggalMejaTebu =
                    $mutuD->tanggal_meja_tebu;

                if (
                    $tanggalMejaTebu instanceof Carbon
                ) {

                    $tanggalFormatted =
                        $tanggalMejaTebu->format('d/m/Y');

                } else {

                    $tanggalFormatted =
                        Carbon::parse(
                            $tanggalMejaTebu
                        )->format('d/m/Y');
                }

                $lines[] =
                    'Tanggal Meja Tebu: '
                    . $tanggalFormatted;
            }

            /*
            |--------------------------------------------------------------------------
            | JAM MEJA TEBU
            |--------------------------------------------------------------------------
            */

            if ($mutuD->jam_meja_tebu) {

                $lines[] =
                    "Jam Meja Tebu: {$mutuD->jam_meja_tebu}";
            }

            /*
            |--------------------------------------------------------------------------
            | DURASI SKORSING
            |--------------------------------------------------------------------------
            */

            if ($mutuD->durasi_skorsing) {

                $lines[] =
                    "Durasi Skorsing: {$mutuD->durasi_skorsing} hari";
            }

            $lines[] = '';

            $number++;
        }

        /*
        |--------------------------------------------------------------------------
        | TINDAK LANJUT
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Data tersebut kami sampaikan sebagai bahan informasi dan perhatian Bapak/Ibu PTA dalam rangka tindak lanjut terhadap hasil identifikasi kualitas tebu tersebut.';

        $lines[] = '';

        $lines[] =
            'Kami mohon bantuan Bapak/Ibu PTA untuk dapat memperhatikan dan menindaklanjuti informasi ini sesuai dengan prosedur serta ketentuan yang berlaku.';

        $lines[] = '';

        /*
        |--------------------------------------------------------------------------
        | PENUTUP
        |--------------------------------------------------------------------------
        */

        $lines[] =
            'Demikian pemberitahuan ini kami sampaikan. Atas perhatian, kerja sama, dan tindak lanjut Bapak/Ibu, kami ucapkan terima kasih.';

        $lines[] = '';

        $lines[] =
            '_SIMUTU-TMA PG NGADIREDJO_';

        return implode("\n", $lines);
    }

    /**
     * Generate nomor pemberitahuan.
     */
    private function generateNumber(): string
    {
        $prefix =
            'SMT/'
            . now()->format('Ymd')
            . '/';

        $last = Pemberitahuan::query()
            ->whereDate(
                'tanggal_pemberitahuan',
                today()
            )
            ->latest('id')
            ->first();

        $number = $last
            ? (
                (int) substr(
                    $last->nomor,
                    -4
                )
            ) + 1
            : 1;

        return $prefix
            . str_pad(
                $number,
                4,
                '0',
                STR_PAD_LEFT
            );
    }
}