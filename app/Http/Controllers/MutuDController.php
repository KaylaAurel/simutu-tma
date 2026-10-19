<?php

namespace App\Http\Controllers;

use App\Models\MutuD;
use App\Services\MutuDService;
use Illuminate\Http\Request;

class MutuDController extends Controller
{
    protected MutuDService $mutuDService;

    public function __construct(
        MutuDService $mutuDService
    ) {
        $this->mutuDService = $mutuDService;
    }

    /**
     * ============================================================
     * DAFTAR MUTU D
     * ============================================================
     */
    public function index(Request $request)
    {
        $filters = [
            'tanggal' => $request->input('tanggal'),
            'pta' => $request->input('pta'),
            'search' => $request->input('search'),
            'status_skorsing' => $request->input('status_skorsing'),
        ];

        $mutuDs = $this->mutuDService
            ->query($filters)
            ->with([
                'pta',
                'memoProdis',
                'latestPemberitahuan',
            ])
            ->paginate(15)
            ->withQueryString();

        return view('mutu-d.index', [
            'mutuDs' => $mutuDs,
            'filters' => $filters,
        ]);
    }

    /**
     * ============================================================
     * DETAIL MUTU D
     * ============================================================
     */
    public function show(MutuD $mutuD)
    {
        $mutuD->load([
            'memoProdis',
            'pta',
            'photos',
            'pemberitahuanDetails.pemberitahuan',
        ]);

        return view(
            'mutu-d.detail',
            compact('mutuD')
        );
    }

    /**
     * ============================================================
     * TERAPKAN SKORSING
     * ============================================================
     */
    public function applySkorsing(
        Request $request,
        MutuD $mutuD
    ) {
        $validated = $request->validate([
            'durasi' => [
                'nullable',
                'integer',
                'min:1',
                'max:30',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $durasi = $validated['durasi'] ?? 3;

        $this->mutuDService->applySkorsing(
            $mutuD,
            $durasi,
            $validated['catatan'] ?? null
        );

        return redirect()
            ->route(
                'mutu-d.detail',
                $mutuD->id
            )
            ->with(
                'success',
                "Skorsing berhasil diterapkan selama {$durasi} hari."
            );
    }
}