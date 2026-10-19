<?php

namespace App\Http\Controllers;

use App\Models\ImportProdis;
use App\Services\ProdisImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportProdisController extends Controller
{
    protected ProdisImportService $prodisImportService;

    public function __construct(
        ProdisImportService $prodisImportService
    ) {
        $this->prodisImportService = $prodisImportService;
    }

    /**
     * Halaman Import Prodis.
     */
    public function index()
    {
        $imports = ImportProdis::with('importedBy')
            ->latest()
            ->paginate(10);

        return view(
            'import.index',
            compact('imports')
        );
    }

    /**
     * Upload file Excel Prodis.
     */
   public function store(Request $request)
{
    $request->validate([
        'file' => [
            'required',
            'file',
            'mimes:xlsx,xls',
            'max:10240',
        ],
    ]);

    $file = $request->file('file');

    /*
    |--------------------------------------------------------------------------
    | Simpan file
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

    $path = $file->storeAs(
        'imports/prodis',
        $fileName,
        'public'
    );

    /*
    |--------------------------------------------------------------------------
    | Simpan informasi import
    |--------------------------------------------------------------------------
    */

    $import = ImportProdis::create([
        'nama_file' =>
            $file->getClientOriginalName(),

        'path_file' =>
            $path,

        'tanggal_import' =>
            today(),

        'jumlah_data' =>
            0,

        'jumlah_memo' =>
            0,

        'status' =>
            'uploaded',

        'imported_by' =>
            Auth::id(),
    ]);

    /*
    |--------------------------------------------------------------------------
    | Redirect preview
    |--------------------------------------------------------------------------
    */

    return redirect()->route(
    'import.preview',
    [
        'import' => $import->id
    ]
    );
}
    /**
     * Preview file Prodis.
     */
   public function preview($id)
{
    $import = ImportProdis::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Pastikan path file tersedia
    |--------------------------------------------------------------------------
    */

    if (empty($import->path_file)) {
        return redirect()
            ->route('import.index')
            ->with(
                'error',
                'File import tidak memiliki path.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pastikan file benar-benar ada
    |--------------------------------------------------------------------------
    */

    if (!Storage::disk('public')->exists($import->path_file)) {
        return redirect()
            ->route('import.index')
            ->with(
                'error',
                'File Excel tidak ditemukan di storage.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Ambil lokasi file
    |--------------------------------------------------------------------------
    */

    $fullPath = Storage::disk('public')
        ->path($import->path_file);

    /*
    |--------------------------------------------------------------------------
    | Buat UploadedFile dari file yang tersimpan
    |--------------------------------------------------------------------------
    */

    $uploadedFile = new \Illuminate\Http\UploadedFile(
        $fullPath,
        $import->nama_file,
        mime_content_type($fullPath),
        null,
        true
    );

    /*
    |--------------------------------------------------------------------------
    | Baca Excel
    |--------------------------------------------------------------------------
    */

    try {

        $preview = $this->prodisImportService
            ->preview($uploadedFile);

    } catch (\Throwable $e) {

        return redirect()
            ->route('import.index')
            ->with(
                'error',
                'File tidak dapat dibaca: '
                . $e->getMessage()
            );
    }

    return view(
        'import.preview',
        compact(
            'import',
            'preview'
        )
    );
}

    /**
     * Memproses import Prodis.
     */
    public function process($id)
    {
        $import = ImportProdis::findOrFail($id);

        $path = $import->path_file;

        if (!$path) {
            return back()->with(
                'error',
                'File Excel Prodis tidak ditemukan.'
            );
        }

        if (!Storage::disk('public')->exists($path)) {
            return back()->with(
                'error',
                'File Excel Prodis tidak ditemukan di storage.'
            );
        }

        try {

            $import->update([
                'status' => 'processing',
            ]);

            $result = $this->prodisImportService
                ->process(
                    $import,
                    $path
                );

            return redirect()
                ->route('import.index')
                ->with(
                    'success',
                    $result['message']
                    ?? 'Data Prodis berhasil diproses.'
                );

        } catch (\Throwable $e) {

            $import->update([
                'status' => 'failed',
            ]);

            return back()
                ->with(
                    'error',
                    'Import Prodis gagal: '
                    . $e->getMessage()
                );
        }
    }

    /**
     * Hapus import.
     */
    public function destroy($id)
    {
        $import = ImportProdis::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Hapus file Excel
        |--------------------------------------------------------------------------
        */

        if ($import->path_file) {
            Storage::disk('public')
                ->delete($import->path_file);
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus data riwayat
        |--------------------------------------------------------------------------
        */

        $import->delete();

        return redirect()
            ->route('import.index')
            ->with(
                'success',
                'Data import berhasil dihapus.'
            );
    }
}