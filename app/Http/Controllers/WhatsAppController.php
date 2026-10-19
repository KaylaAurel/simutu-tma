<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    protected WhatsAppService $whatsappService;

    /**
     * Dependency Injection.
     */
    public function __construct(
        WhatsAppService $whatsappService
    ) {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Test koneksi Fonnte.
     */
    public function testConnection()
    {
        try {

            $result =
                $this->whatsappService
                    ->checkConnection();

            return response()->json(
                $result
            );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Gagal menghubungkan SIMUTU-TMA dengan Fonnte.',
            ], 500);
        }
    }

    /**
     * Test kirim WhatsApp melalui Fonnte.
     *
     * Method ini hanya digunakan untuk pengujian
     * konfigurasi admin.
     */
    public function testSend(
        Request $request
    ) {
        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'max:30',
            ],

            'message' => [
                'required',
                'string',
                'max:4096',
            ],
        ]);

        try {

            $result =
                $this->whatsappService
                    ->sendMessage(
                        $validated['number'],
                        $validated['message']
                    );

            if (!$result['success']) {

                return response()->json(
                    $result,
                    422
                );
            }

            return response()->json(
                $result
            );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Terjadi kesalahan saat mengirim pesan melalui Fonnte.',
            ], 500);
        }
    }

    /**
     * Test kirim pesan dengan foto.
     *
     * Digunakan untuk memastikan fitur
     * dokumentasi/foto dapat dikirim.
     */
    public function testSendWithPhoto(
        Request $request
    ) {
        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'max:30',
            ],

            'message' => [
                'required',
                'string',
                'max:4096',
            ],

            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        try {

            $result =
                $this->whatsappService
                    ->sendMessageWithFile(
                        $validated['number'],
                        $validated['message'],
                        $validated['photo']
                    );

            if (!$result['success']) {

                return response()->json(
                    $result,
                    422
                );
            }

            return response()->json(
                $result
            );

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' =>
                    'Gagal mengirim foto melalui Fonnte.',
            ], 500);
        }
    }
}