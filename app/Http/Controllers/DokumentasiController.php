<?php

namespace App\Http\Controllers;

use App\Models\MutuD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    /**
     * Menampilkan dokumentasi Mutu D.
     */
    public function show(MutuD $mutuD)
    {
        $mutuD->load([
            'photos',
        ]);

        return view(
            'mutu-d.detail',
            compact('mutuD')
        );
    }

    /**
     * Upload dokumentasi foto Mutu D.
     */
    public function store(
        Request $request,
        MutuD $mutuD
    ) {
        $validated = $request->validate([
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'caption' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'photo.required' =>
                'Foto dokumentasi wajib dipilih.',

            'photo.image' =>
                'File harus berupa gambar.',

            'photo.mimes' =>
                'Format foto harus JPG, JPEG, PNG, atau WEBP.',

            'photo.max' =>
                'Ukuran foto maksimal 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Simpan foto
        |--------------------------------------------------------------------------
        */

        $path = $validated['photo']->store(
            'mutu-d/photos',
            'public'
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan informasi foto ke database
        |--------------------------------------------------------------------------
        */

        $photo = $mutuD->photos()->create([
            'file_path' => $path,

            'caption' =>
                $validated['caption']
                ?? null,

            'uploaded_by' =>
                auth()->id(),
        ]);

        return back()->with(
            'success',
            'Dokumentasi berhasil ditambahkan.'
        );
    }

    /**
     * Hapus dokumentasi.
     */
    public function destroy(
        MutuD $mutuD,
        $foto
    ) {
        $photo = $mutuD->photos()
            ->where('id', $foto)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Hapus file fisik
        |--------------------------------------------------------------------------
        */

        if (
            $photo->file_path &&
            Storage::disk('public')
                ->exists($photo->file_path)
        ) {

            Storage::disk('public')
                ->delete($photo->file_path);
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus record database
        |--------------------------------------------------------------------------
        */

        $photo->delete();

        return back()->with(
            'success',
            'Dokumentasi berhasil dihapus.'
        );
    }
}