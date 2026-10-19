<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class WhatsAppService
{
    protected string $endpoint;

    protected ?string $token;

    public function __construct()
    {
        $this->endpoint = config(
            'services.fonnte.endpoint',
            'https://api.fonnte.com/send'
        );

        $this->token = config(
            'services.fonnte.token'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK CONFIGURATION
    |--------------------------------------------------------------------------
    */

    public function checkConnection(): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'FONNTE_TOKEN belum dikonfigurasi.',
            ];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->post($this->endpoint, [
                    'target' => '6280000000000',
                    'message' => 'SIMUTU-TMA connection test.',
                    'connectOnly' => true,
                ]);

            return $this->parseResponse($response);

        } catch (Throwable $e) {

            Log::error('Fonnte connection error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' =>
                    'Tidak dapat terhubung ke Fonnte: '
                    . $e->getMessage(),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SEND TEXT MESSAGE
    |--------------------------------------------------------------------------
    */

    public function sendMessage(
        string $number,
        string $message,
        array $options = []
    ): array {

        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'FONNTE_TOKEN belum dikonfigurasi.',
            ];
        }

        $number = $this->normalizeNumber($number);

        if (empty($number)) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid.',
            ];
        }

        try {

            $payload = [
                'target' => $number,

                'message' => $message,

                'countryCode' => '62',

                /*
                |--------------------------------------------------------------------------
                | FALSE = tetap masuk antrean Fonnte ketika device reconnect
                |--------------------------------------------------------------------------
                */

                'connectOnly' =>
                    $options['connectOnly'] ?? false,

                'preview' => false,
            ];

            if (isset($options['delay'])) {
                $payload['delay'] =
                    (string) $options['delay'];
            }

            if (isset($options['typing'])) {
                $payload['typing'] =
                    (bool) $options['typing'];
            }

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->post(
                    $this->endpoint,
                    $payload
                );

            Log::info(
                'Fonnte send message response',
                [
                    'number' => $number,

                    'http_status' =>
                        $response->status(),

                    'payload' => $payload,

                    'response' =>
                        $response->json(),

                    'raw_response' =>
                        $response->body(),
                ]
            );

            return $this->parseResponse(
                $response
            );

        } catch (Throwable $e) {

            Log::error(
                'Fonnte send message exception',
                [
                    'number' => $number,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return [
                'success' => false,

                'message' =>
                    'Gagal menghubungi Fonnte: '
                    . $e->getMessage(),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SEND LOCAL FILE
    |--------------------------------------------------------------------------
    */

    public function sendStorageFile(
        string $number,
        string $message,
        string $storagePath,
        ?string $filename = null
    ): array {

        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'FONNTE_TOKEN belum dikonfigurasi.',
            ];
        }

        $number = $this->normalizeNumber($number);

        if (empty($number)) {
            return [
                'success' => false,
                'message' => 'Nomor WhatsApp tidak valid.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Cek file
        |--------------------------------------------------------------------------
        */

        if (
            !Storage::disk('public')
                ->exists($storagePath)
        ) {

            Log::error(
                'Fonnte file not found',
                [
                    'storage_path' =>
                        $storagePath,
                ]
            );

            return [
                'success' => false,

                'message' =>
                    'File dokumentasi tidak ditemukan: '
                    . $storagePath,
            ];
        }

        $fullPath =
            Storage::disk('public')
                ->path($storagePath);

        if (!is_file($fullPath)) {
            return [
                'success' => false,

                'message' =>
                    'Path dokumentasi bukan merupakan file.',
            ];
        }

        if (!is_readable($fullPath)) {
            return [
                'success' => false,

                'message' =>
                    'File dokumentasi tidak dapat dibaca.',
            ];
        }

        $filename ??=
            basename($storagePath);

        /*
        |--------------------------------------------------------------------------
        | Cek ukuran file
        |--------------------------------------------------------------------------
        */

        $fileSize = filesize($fullPath);

        if (
            $fileSize !== false
            &&
            $fileSize > 10 * 1024 * 1024
        ) {

            return [
                'success' => false,

                'message' =>
                    'Ukuran file melebihi batas 10 MB Fonnte.',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MIME TYPE
        |--------------------------------------------------------------------------
        */

        $mimeType = mime_content_type(
            $fullPath
        );

        Log::info(
            'Fonnte preparing file',
            [
                'number' => $number,

                'file' => $storagePath,

                'full_path' => $fullPath,

                'filename' => $filename,

                'mime_type' => $mimeType,

                'size' => $fileSize,
            ]
        );

        try {

            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' =>
                        $this->token,
                ])
                ->attach(
                    'file',
                    fopen($fullPath, 'r'),
                    $filename
                )
                ->post(
                    $this->endpoint,
                    [
                        'target' =>
                            $number,

                        'message' =>
                            $message,

                        'filename' =>
                            $filename,

                        'countryCode' =>
                            '62',

                        /*
                        |--------------------------------------------------------------------------
                        | Jangan blokir antrean ketika device reconnect.
                        |--------------------------------------------------------------------------
                        */

                        'connectOnly' =>
                            false,
                    ]
                );

            Log::info(
                'Fonnte file response',
                [
                    'number' => $number,

                    'file' =>
                        $storagePath,

                    'http_status' =>
                        $response->status(),

                    'response' =>
                        $response->json(),

                    'raw_response' =>
                        $response->body(),
                ]
            );

            return $this->parseResponse(
                $response
            );

        } catch (Throwable $e) {

            Log::error(
                'Fonnte storage file exception',
                [
                    'number' => $number,

                    'file' =>
                        $storagePath,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            return [
                'success' => false,

                'message' =>
                    'Gagal mengirim file melalui Fonnte: '
                    . $e->getMessage(),
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE FONNTE RESPONSE
    |--------------------------------------------------------------------------
    */

    private function parseResponse(
        Response $response
    ): array {

        $data = $response->json();

        /*
        |--------------------------------------------------------------------------
        | JSON tidak valid
        |--------------------------------------------------------------------------
        */

        if (!is_array($data)) {

            return [
                'success' => false,

                'message' =>
                    'Fonnte memberikan response yang tidak valid.',

                'http_status' =>
                    $response->status(),

                'response' =>
                    $response->body(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | HTTP ERROR
        |--------------------------------------------------------------------------
        */

        if (!$response->successful()) {

            $message =
                $data['reason']
                ?? $data['detail']
                ?? $data['message']
                ?? 'Fonnte mengembalikan HTTP error.';

            return [
                'success' => false,

                'message' => $message,

                'http_status' =>
                    $response->status(),

                'response' =>
                    $data,

                'id' =>
                    $data['id']
                    ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | FONNTE STATUS FALSE
        |--------------------------------------------------------------------------
        */

        if (
            isset($data['status'])
            &&
            (
                $data['status'] === false
                ||
                $data['status'] === 'false'
                ||
                $data['status'] === 0
                ||
                $data['status'] === '0'
            )
        ) {

            $message =
                $data['reason']
                ?? $data['detail']
                ?? $data['message']
                ?? 'Fonnte menolak pengiriman pesan.';

            return [
                'success' => false,

                'message' => $message,

                'http_status' =>
                    $response->status(),

                'response' =>
                    $data,

                'id' =>
                    $data['id']
                    ?? null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return [
            'success' => true,

            'message' =>
                $data['detail']
                ?? $data['message']
                ?? 'Pesan berhasil diteruskan ke Fonnte.',

            'response' =>
                $data,

            'id' =>
                $data['id']
                ?? null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | NORMALIZE NUMBER
    |--------------------------------------------------------------------------
    */

    private function normalizeNumber(
        string $number
    ): string {

        $number = preg_replace(
            '/[^0-9]/',
            '',
            $number
        );

        if (empty($number)) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | 08xxxxxxxxxx -> 628xxxxxxxxxx
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $number,
                '0'
            )
        ) {

            $number =
                '62'
                . substr(
                    $number,
                    1
                );
        }

        /*
        |--------------------------------------------------------------------------
        | 8xxxxxxxxxx -> 628xxxxxxxxxx
        |--------------------------------------------------------------------------
        */

        elseif (
            str_starts_with(
                $number,
                '8'
            )
        ) {

            $number =
                '62'
                . $number;
        }

        /*
        |--------------------------------------------------------------------------
        | +62xxxxxxxx -> 62xxxxxxxx
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with(
                $number,
                '620'
            )
        ) {

            $number =
                '62'
                . substr(
                    $number,
                    3
                );
        }

        return $number;
    }
}