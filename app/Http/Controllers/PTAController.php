<?php

namespace App\Http\Controllers;

use App\Models\PTA;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PTAController extends Controller
{
    /**
     * Menampilkan daftar PTA.
     */
    public function index(Request $request)
    {
        $query = PTA::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->input('search');

            $query->where(function ($q) use ($search) {

                $q->where(
                    'kode_pta',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'nama_pta',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'no_whatsapp',
                    'like',
                    "%{$search}%"
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->input('status')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $ptas = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'pta.index',
            compact('ptas')
        );
    }


    /**
     * Form tambah PTA.
     */
    public function create()
    {
        return view(
            'pta.create',
            [
                'pta' => new PTA(),
            ]
        );
    }


    /**
     * Simpan PTA baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'kode_pta' => [
                'required',
                'string',
                'max:50',
                'unique:ptas,kode_pta',
            ],

            'nama_pta' => [
                'required',
                'string',
                'max:150',
            ],

            'no_whatsapp' => [
                'required',
                'string',
                'max:30',
            ],

            'status' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],

        ], [

            'kode_pta.required' =>
                'Kode PTA wajib diisi.',

            'kode_pta.unique' =>
                'Kode PTA sudah terdaftar.',

            'nama_pta.required' =>
                'Nama PTA wajib diisi.',

            'no_whatsapp.required' =>
                'Nomor WhatsApp wajib diisi.',

            'status.required' =>
                'Status PTA wajib dipilih.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Normalisasi nomor WhatsApp
        |--------------------------------------------------------------------------
        */

        $validated['no_whatsapp'] =
            $this->normalizePhoneNumber(
                $validated['no_whatsapp']
            );


        /*
        |--------------------------------------------------------------------------
        | Simpan PTA
        |--------------------------------------------------------------------------
        */

        PTA::create($validated);


        return redirect()
            ->route('pta.index')
            ->with(
                'success',
                'Data PTA berhasil ditambahkan.'
            );
    }


    /**
     * Form edit PTA.
     */
    public function edit(PTA $pta)
    {
        return view(
            'pta.edit',
            compact('pta')
        );
    }


    /**
     * Update PTA.
     */
    public function update(
        Request $request,
        PTA $pta
    ) {

        $validated = $request->validate([

            'kode_pta' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'ptas',
                    'kode_pta'
                )->ignore($pta->id),
            ],

            'nama_pta' => [
                'required',
                'string',
                'max:150',
            ],

            'no_whatsapp' => [
                'required',
                'string',
                'max:30',
            ],

            'status' => [
                'required',
                Rule::in([
                    'aktif',
                    'nonaktif',
                ]),
            ],

        ], [

            'kode_pta.required' =>
                'Kode PTA wajib diisi.',

            'kode_pta.unique' =>
                'Kode PTA sudah digunakan oleh PTA lain.',

            'nama_pta.required' =>
                'Nama PTA wajib diisi.',

            'no_whatsapp.required' =>
                'Nomor WhatsApp wajib diisi.',

            'status.required' =>
                'Status PTA wajib dipilih.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Normalisasi nomor WhatsApp
        |--------------------------------------------------------------------------
        */

        $validated['no_whatsapp'] =
            $this->normalizePhoneNumber(
                $validated['no_whatsapp']
            );


        /*
        |--------------------------------------------------------------------------
        | Update PTA
        |--------------------------------------------------------------------------
        */

        $pta->update($validated);


        return redirect()
            ->route('pta.index')
            ->with(
                'success',
                'Data PTA berhasil diperbarui.'
            );
    }


    /**
     * Hapus PTA.
     */
    public function destroy(PTA $pta)
    {
        /*
        |--------------------------------------------------------------------------
        | Cek riwayat pemberitahuan
        |--------------------------------------------------------------------------
        */

        if ($pta->pemberitahuanDetails()->exists()) {

            return back()->withErrors([
                'pta' =>
                    'PTA tidak dapat dihapus karena sudah memiliki riwayat pemberitahuan.',
            ]);
        }


        $pta->delete();


        return back()->with(
            'success',
            'Data PTA berhasil dihapus.'
        );
    }


    /**
     * Normalisasi nomor WhatsApp Indonesia.
     *
     * 081234567890
     *       ↓
     * 6281234567890
     */
    private function normalizePhoneNumber(
        string $number
    ): string {

        $number = preg_replace(
            '/[^0-9]/',
            '',
            $number
        );


        if (str_starts_with($number, '0')) {

            $number =
                '62' . substr($number, 1);
        }


        if (str_starts_with($number, '8')) {

            $number =
                '62' . $number;
        }


        return $number;
    }
}