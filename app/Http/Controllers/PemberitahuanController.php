<?php

namespace App\Http\Controllers;

use App\Models\MutuD;
use App\Models\Pemberitahuan;
use App\Services\PemberitahuanService;
use App\Services\PdfGeneratorService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemberitahuanController extends Controller
{
    protected PemberitahuanService $pemberitahuanService;

    protected PdfGeneratorService $pdfGeneratorService;


    public function __construct(
        PemberitahuanService $pemberitahuanService,
        PdfGeneratorService $pdfGeneratorService
    ) {
        $this->pemberitahuanService = $pemberitahuanService;

        $this->pdfGeneratorService = $pdfGeneratorService;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

     public function index(Request $request)
{
    $query = Pemberitahuan::query()
        ->with([
            'creator',
            'details.pta',
            'details.mutuD',
        ]);

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = trim($request->input('search'));

        $query->where(function ($q) use ($search) {

            $q->where('nomor', 'like', "%{$search}%")
                ->orWhere('judul', 'like', "%{$search}%");
        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    |
    | Kolom yang benar adalah status_pesan.
    |
    */

    if ($request->filled('status')) {

        $status = $request->input('status');

        /*
        | Blade menggunakan istilah:
        | draft
        | processing
        | sent
        | failed
        |
        | Sedangkan database:
        | draft
        | processing
        | terkirim
        | gagal
        |
        */

        $statusMap = [
            'draft'      => 'draft',
            'processing' => 'processing',
            'sent'       => 'terkirim',
            'failed'     => 'gagal',
        ];

        if (isset($statusMap[$status])) {

            $query->where(
                'status_pesan',
                $statusMap[$status]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    $pemberitahuans = $query
        ->latest('id')
        ->paginate(15)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | HITUNG DATA UNTUK SETIAP PEMBERITAHUAN
    |--------------------------------------------------------------------------
    */

    $pemberitahuans->getCollection()->transform(
        function ($item) {

            /*
            | Total PTA unik
            */

            $item->total_pta = $item->details
                ->pluck('pta_id')
                ->filter()
                ->unique()
                ->count();


            /*
            | Total Mutu D unik
            */

            $item->total_mutu_d = $item->details
                ->pluck('mutu_d_id')
                ->filter()
                ->unique()
                ->count();


            /*
            | Ambil waktu kirim terbaru dari detail
            */

            $item->latest_sent_at = $item->details
                ->pluck('sent_at')
                ->filter()
                ->sortDesc()
                ->first();


            return $item;
        }
    );


    return view(
        'pemberitahuan.index',
        compact('pemberitahuans')
    );
}


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $tanggalInput = $request->input(
            'tanggal',
            today()->format('Y-m-d')
        );


        try {

            $tanggal = Carbon::parse(
                $tanggalInput
            )->format('Y-m-d');

        } catch (\Throwable $e) {

            $tanggal = today()->format('Y-m-d');
        }


        /*
        |--------------------------------------------------------------------------
        | DATA MUTU D YANG BELUM DIBERITAHUKAN
        |--------------------------------------------------------------------------
        */

       $mutuDs = MutuD::query()
    ->with([
        'photos',
        'memoProdis',
        'pta',
    ])
    ->mutuD()
    ->whereDoesntHave(
        'pemberitahuanDetails'
    )
    ->whereHas(
        'memoProdis',
        function ($query) use ($tanggal) {

            $query->whereDate(
                'tanggal_memo',
                $tanggal
            );
        }
    )
    ->orderBy('nama_petani')
    ->get();


/*
|--------------------------------------------------------------------------
| AUTO MATCH PTA
|--------------------------------------------------------------------------
|
| Jika Mutu D belum memiliki pta_id,
| sistem mencoba mencocokkan nama_petani
| dengan nama_pta.
|
*/

$mutuDs->each(function ($mutuD) {

    if (!$mutuD->pta_id) {

        $mutuD->syncPtaFromNama();
    }

    $mutuD->load('pta');
});


        /*
        |--------------------------------------------------------------------------
        | TANGGAL MUTU D YANG TERSEDIA
        |--------------------------------------------------------------------------
        */

        $tanggalTersedia = MutuD::query()
            ->mutuD()
            ->whereDoesntHave(
                'pemberitahuanDetails'
            )
            ->whereHas(
                'memoProdis'
            )
            ->with([
                'memoProdis:id,tanggal_memo',
            ])
            ->get()
            ->map(function ($mutuD) {

                return optional(
                    $mutuD->memoProdis
                )->tanggal_memo;

            })
            ->filter()
            ->map(function ($date) {

                return Carbon::parse($date)
                    ->format('Y-m-d');

            })
            ->unique()
            ->sortDesc()
            ->values();


        return view(
            'pemberitahuan.create',
            compact(
                'mutuDs',
                'tanggal',
                'tanggalTersedia'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'tanggal' => [
                    'required',
                    'date',
                ],

                'mutu_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'mutu_ids.*' => [
                    'integer',
                    'exists:mutu_d,id',
                ],
            ],
            [
                'tanggal.required' =>
                    'Tanggal Mutu D wajib dipilih.',

                'tanggal.date' =>
                    'Format tanggal tidak valid.',

                'mutu_ids.required' =>
                    'Pilih minimal satu data Mutu D.',

                'mutu_ids.min' =>
                    'Pilih minimal satu data Mutu D.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA MUTU D
        |--------------------------------------------------------------------------
        */

        $mutuDs = MutuD::query()
    ->with([
        'memoProdis',
        'pta',
    ])
    ->mutuD()
    ->whereDoesntHave(
        'pemberitahuanDetails'
    )
    ->whereIn(
        'id',
        $validated['mutu_ids']
    )
    ->whereHas(
        'memoProdis',
        function ($query) use ($validated) {

            $query->whereDate(
                'tanggal_memo',
                $validated['tanggal']
            );
        }
    )
    ->get();


/*
|--------------------------------------------------------------------------
| AUTO MATCH PTA
|--------------------------------------------------------------------------
*/

$mutuDs->each(function ($mutuD) {

    if (!$mutuD->pta_id) {

        $mutuD->syncPtaFromNama();
    }

    $mutuD->load('pta');
});
if (
    $mutuDs->count()
    !== count($validated['mutu_ids'])
) {

    return back()
        ->withErrors([
            'mutu_ids' =>
                'Terdapat data Mutu D yang tidak sesuai dengan tanggal memo atau sudah pernah diproses.',
        ])
        ->withInput();
}


        /*
        |--------------------------------------------------------------------------
        | VALIDASI JUMLAH DATA
        |--------------------------------------------------------------------------
        */

        if (
            $mutuDs->count()
            !== count($validated['mutu_ids'])
        ) {

            return back()
                ->withErrors([
                    'mutu_ids' =>
                        'Terdapat data Mutu D yang tidak sesuai dengan tanggal memo atau sudah pernah diproses.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDASI PTA
        |--------------------------------------------------------------------------
        */

        $tanpaPta = $mutuDs->filter(
            fn ($mutuD) => !$mutuD->pta_id
        );


        if ($tanpaPta->isNotEmpty()) {

            $namaPetani = $tanpaPta
                ->pluck('nama_petani')
                ->filter()
                ->implode(', ');


            return back()
                ->withErrors([
                    'mutu_ids' =>
                        "Data Mutu D berikut belum memiliki PTA: {$namaPetani}.",
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE PEMBERITAHUAN
        |--------------------------------------------------------------------------
        */

        try {

            $pemberitahuan =
                $this->pemberitahuanService
                    ->createFromMutuD(
                        $validated['mutu_ids'],
                        auth()->id()
                    );

        } catch (\Throwable $e) {

            report($e);


            return back()
                ->withErrors([
                    'mutu_ids' =>
                        $e->getMessage()
                        ?: 'Gagal membuat draft pemberitahuan.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | REDIRECT PREVIEW
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'pemberitahuan.preview',
                $pemberitahuan->id
            )
            ->with(
                'success',
                'Data Mutu D berhasil dipilih. Silakan periksa preview pemberitahuan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    public function preview(
        Pemberitahuan $pemberitahuan
    ) {

        $pemberitahuan->load([
            'creator',
            'details.pta',
            'details.mutuD.photos',
        ]);


        $details = $pemberitahuan->details;


        /*
        |--------------------------------------------------------------------------
        | TOTAL PTA
        |--------------------------------------------------------------------------
        */

        $totalPta = $details
            ->pluck('pta_id')
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL MUTU D
        |--------------------------------------------------------------------------
        */

        $totalMutuD = $details
            ->pluck('mutu_d_id')
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | GROUP PTA
        |--------------------------------------------------------------------------
        */

        $ptaGroups = $details
            ->filter(
                fn ($detail) =>
                    $detail->pta !== null
            )
            ->groupBy('pta_id');


        return view(
            'pemberitahuan.preview',
            compact(
                'pemberitahuan',
                'details',
                'totalPta',
                'totalMutuD',
                'ptaGroups'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW / DETAIL
    |--------------------------------------------------------------------------
    */

    public function show(
        Pemberitahuan $pemberitahuan
    ) {

        $pemberitahuan->load([
            'creator',
            'details.pta',
            'details.mutuD.photos',
        ]);


        /*
        |--------------------------------------------------------------------------
        | TOTAL DATA
        |--------------------------------------------------------------------------
        */

        $totalPta = $pemberitahuan
            ->details
            ->pluck('pta_id')
            ->filter()
            ->unique()
            ->count();


        $totalMutuD = $pemberitahuan
            ->details
            ->pluck('mutu_d_id')
            ->filter()
            ->unique()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DATA TERKIRIM
        |--------------------------------------------------------------------------
        */

        $sentCount = $pemberitahuan
            ->details
            ->whereIn(
                'status',
                [
                    'sent',
                    'delivered',
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | DATA GAGAL
        |--------------------------------------------------------------------------
        */

        $failedCount = $pemberitahuan
            ->details
            ->where(
                'status',
                'failed'
            )
            ->count();


        return view(
            'pemberitahuan.detail',
            compact(
                'pemberitahuan',
                'sentCount',
                'failedCount',
                'totalPta',
                'totalMutuD'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SEND WHATSAPP
    |--------------------------------------------------------------------------
    */

    public function send(
        Pemberitahuan $pemberitahuan
    ) {

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS
        |--------------------------------------------------------------------------
        |
        | Gunakan status_pesan secara konsisten.
        |
        */

        if (
            !in_array(
                $pemberitahuan->status_pesan,
                [
                    'draft',
                    'gagal',
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'whatsapp' =>
                        'Pemberitahuan ini sudah diproses dan tidak dapat dikirim ulang.',
                ]);
        }


        try {

            $result =
                $this->pemberitahuanService
                    ->sendViaFonnte(
                        $pemberitahuan
                    );


            /*
            |--------------------------------------------------------------------------
            | JIKA GAGAL
            |--------------------------------------------------------------------------
            */

            if (
                empty($result['success'])
            ) {

                return back()
                    ->withErrors([
                        'whatsapp' =>
                            $result['message']
                            ?? 'Pemberitahuan gagal dikirim melalui WhatsApp.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | BERHASIL
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'pemberitahuan.detail',
                    $pemberitahuan->id
                )
                ->with(
                    'success',
                    $result['message']
                    ?? 'Pemberitahuan berhasil dikirim melalui WhatsApp.'
                );

        } catch (\Throwable $e) {

            report($e);


            return back()
                ->withErrors([
                    'whatsapp' =>
                        'Terjadi kesalahan saat mengirim pemberitahuan melalui WhatsApp: '
                        . $e->getMessage(),
                ]);
        }
    }


   /*
|--------------------------------------------------------------------------
| MONITORING
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| MONITORING
|--------------------------------------------------------------------------
*/

public function monitoring(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | QUERY PEMBERITAHUAN
    |--------------------------------------------------------------------------
    */

    $query = Pemberitahuan::query()
        ->with([
            'creator',
            'details.pta',
            'details.mutuD',
        ]);


    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

    if ($request->filled('status')) {

        $status = $request->input('status');

        $statusMap = [
            'draft'      => 'draft',
            'processing' => 'processing',
            'sent'       => 'terkirim',
            'partial'    => 'sebagian',
            'failed'     => 'gagal',
        ];

        if (isset($statusMap[$status])) {

            $query->where(
                'status_pesan',
                $statusMap[$status]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DATA STATISTIK
    |--------------------------------------------------------------------------
    |
    | Statistik dihitung dari status_pesan.
    |
    */

    $statistics = [
        'total' => Pemberitahuan::count(),

        'sent' => Pemberitahuan::whereIn(
            'status_pesan',
            [
                'terkirim',
                'sent',
                'delivered',
            ]
        )->count(),

        'partial' => Pemberitahuan::whereIn(
            'status_pesan',
            [
                'sebagian',
                'partial',
            ]
        )->count(),

        'failed' => Pemberitahuan::whereIn(
            'status_pesan',
            [
                'gagal',
                'failed',
            ]
        )->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | DATA AKTIVITAS
    |--------------------------------------------------------------------------
    */

    $pemberitahuans = $query
        ->latest('id')
        ->paginate(15)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | HITUNG TOTAL PTA DAN MUTU D
    |--------------------------------------------------------------------------
    */

    $pemberitahuans->getCollection()->transform(
        function ($item) {

            /*
            | Total PTA unik
            */

            $item->display_total_pta = $item->details
                ->pluck('pta_id')
                ->filter()
                ->unique()
                ->count();


            /*
            | Total Mutu D unik
            */

            $item->display_total_mutu_d = $item->details
                ->pluck('mutu_d_id')
                ->filter()
                ->unique()
                ->count();


            /*
            | Normalisasi status untuk Blade
            */

            $rawStatus = $item->status_pesan
                ?? $item->status
                ?? 'draft';


            if (in_array(
                $rawStatus,
                [
                    'terkirim',
                    'sent',
                    'delivered',
                ],
                true
            )) {

                $item->display_status = 'sent';

            } elseif (in_array(
                $rawStatus,
                [
                    'sebagian',
                    'partial',
                ],
                true
            )) {

                $item->display_status = 'partial';

            } elseif (in_array(
                $rawStatus,
                [
                    'gagal',
                    'failed',
                ],
                true
            )) {

                $item->display_status = 'failed';

            } elseif (in_array(
                $rawStatus,
                [
                    'processing',
                    'pending',
                    'diproses',
                ],
                true
            )) {

                $item->display_status = 'processing';

            } else {

                $item->display_status = 'draft';
            }


            /*
            |--------------------------------------------------------------------------
            | WAKTU AKTIVITAS
            |--------------------------------------------------------------------------
            |
            | Prioritas:
            | 1. waktu_kirim_pesan
            | 2. sent_at
            | 3. updated_at
            | 4. created_at
            |
            */

            $item->display_time =
                $item->waktu_kirim_pesan
                ?? $item->sent_at
                ?? $item->updated_at
                ?? $item->created_at;


            return $item;
        }
    );


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    return view(
        'monitoring.index',
        compact(
            'pemberitahuans',
            'statistics'
        )
    );
}
    /*
    |--------------------------------------------------------------------------
    | MONITORING DETAIL
    |--------------------------------------------------------------------------
    */

    public function monitoringDetail(
        Pemberitahuan $pemberitahuan
    ) {

        $pemberitahuan->load([
            'details.pta',
            'details.mutuD',
            'details.mutuD.photos',
        ]);


        return view(
            'monitoring.detail',
            compact('pemberitahuan')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE PDF
    |--------------------------------------------------------------------------
    */

    public function generatePdf(
        Pemberitahuan $pemberitahuan
    ) {

        try {

            $this->pdfGeneratorService->generate(
                $pemberitahuan
            );


            return back()
                ->with(
                    'success',
                    'Dokumentasi PDF berhasil dibuat.'
                );

        } catch (\Throwable $e) {

            report($e);


            return back()
                ->withErrors([
                    'pdf' =>
                        'Dokumentasi PDF gagal dibuat: '
                        . $e->getMessage(),
                ]);
        }
    }
}