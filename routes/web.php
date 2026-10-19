<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportProdisController;
use App\Http\Controllers\MutuDController;
use App\Http\Controllers\PTAController;
use App\Http\Controllers\PemberitahuanController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\WhatsAppController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| SIMUTU-TMA
| Sistem Monitoring Mutu Tebu - Tebang, Muat, Angkut
| PT Sinergi Gula Nusantara
|
*/


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.process');

});


Route::post('/logout', [
    AuthController::class,
    'logout'
])
->middleware('auth')
->name('logout');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ])->name('dashboard');


  /*
|--------------------------------------------------------------------------
| IMPORT PRODIS
|--------------------------------------------------------------------------
*/

Route::prefix('import')->name('import.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Halaman Import Prodis
    |--------------------------------------------------------------------------
    */

    Route::get('/prodis', [
        ImportProdisController::class,
        'index'
    ])->name('index');


    /*
    |--------------------------------------------------------------------------
    | Upload File Prodis
    |--------------------------------------------------------------------------
    */

    Route::post('/prodis', [
        ImportProdisController::class,
        'store'
    ])->name('store');


    /*
    |--------------------------------------------------------------------------
    | Preview File Prodis
    |--------------------------------------------------------------------------
    */

    Route::get('/prodis/preview/{import}', [
        ImportProdisController::class,
        'preview'
    ])->name('preview');


    /*
    |--------------------------------------------------------------------------
    | Proses Data Prodis
    |--------------------------------------------------------------------------
    */

    Route::post('/prodis/process/{import}', [
        ImportProdisController::class,
        'process'
    ])->name('process');


    /*
    |--------------------------------------------------------------------------
    | Download File
    |--------------------------------------------------------------------------
    */

    Route::get('/prodis/download/{import}', [
        ImportProdisController::class,
        'download'
    ])->name('download');


    /*
    |--------------------------------------------------------------------------
    | Hapus Import
    |--------------------------------------------------------------------------
    */

    Route::delete('/prodis/{import}', [
        ImportProdisController::class,
        'destroy'
    ])->name('destroy');

});
    /*
    |--------------------------------------------------------------------------
    | MUTU D
    |--------------------------------------------------------------------------
    */

    /*
|--------------------------------------------------------------------------
| MUTU D
|--------------------------------------------------------------------------
*/

Route::prefix('mutu-d')
    ->name('mutu-d.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DAFTAR
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            MutuDController::class,
            'index'
        ])->name('index');


        /*
        |--------------------------------------------------------------------------
        | TERAPKAN SKORSING
        |--------------------------------------------------------------------------
        */

        Route::post('/{mutuD}/skorsing', [
            MutuDController::class,
            'applySkorsing'
        ])->name('skorsing');


        /*
        |--------------------------------------------------------------------------
        | FOTO
        |--------------------------------------------------------------------------
        |
        | Kalau method photos() belum ada di controller,
        | route ini jangan dipakai.
        |
        */

        // Route::get('/{mutuD}/photos', [
        //     MutuDController::class,
        //     'photos'
        // ])->name('photos');


        /*
        |--------------------------------------------------------------------------
        | DETAIL
        |--------------------------------------------------------------------------
        */

        Route::get('/{mutuD}', [
            MutuDController::class,
            'show'
        ])->name('detail');

    });


    /*
    |--------------------------------------------------------------------------
    | PTA
    |--------------------------------------------------------------------------
    |
    | Pengelolaan data PTA / Asisten Wilayah.
    |
    */

    Route::prefix('pta')->name('pta.')->group(function () {

        Route::get('/', [
            PTAController::class,
            'index'
        ])->name('index');

        Route::get('/create', [
            PTAController::class,
            'create'
        ])->name('create');

        Route::post('/', [
            PTAController::class,
            'store'
        ])->name('store');

        Route::get('/{pta}/edit', [
            PTAController::class,
            'edit'
        ])->name('edit');

        Route::put('/{pta}', [
            PTAController::class,
            'update'
        ])->name('update');

        Route::delete('/{pta}', [
            PTAController::class,
            'destroy'
        ])->name('destroy');

        Route::get('/{pta}', [
            PTAController::class,
            'show'
        ])->name('detail');

    });


    /*
    |--------------------------------------------------------------------------
    | PEMBERITAHUAN
    |--------------------------------------------------------------------------
    */

    Route::prefix('pemberitahuan')
    ->name('pemberitahuan.')
    ->group(function () {

        // =========================================================
        // DAFTAR PEMBERITAHUAN
        // =========================================================
        Route::get('/', [
            PemberitahuanController::class,
            'index'
        ])->name('index');


        // =========================================================
        // FORM BUAT PEMBERITAHUAN
        // =========================================================
        Route::get('/create', [
            PemberitahuanController::class,
            'create'
        ])->name('create');


        // =========================================================
        // SIMPAN DRAFT
        // =========================================================
        Route::post('/', [
            PemberitahuanController::class,
            'store'
        ])->name('store');


        // =========================================================
        // PREVIEW
        // =========================================================
        Route::get('/{pemberitahuan}/preview', [
            PemberitahuanController::class,
            'preview'
        ])->name('preview');


        // =========================================================
        // KIRIM WHATSAPP
        // =========================================================
        Route::post('/{pemberitahuan}/send', [
            PemberitahuanController::class,
            'send'
        ])->name('send');


        // =========================================================
        // GENERATE PDF
        // =========================================================
        Route::post('/{pemberitahuan}/generate-pdf', [
            PemberitahuanController::class,
            'generatePdf'
        ])->name('generate-pdf');


        // =========================================================
        // DETAIL
        // =========================================================
        Route::get('/{pemberitahuan}', [
            PemberitahuanController::class,
            'show'
        ])->name('detail');


        // =========================================================
        // HAPUS
        // =========================================================
        Route::delete('/{pemberitahuan}', [
            PemberitahuanController::class,
            'destroy'
        ])->name('destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | DOKUMENTASI
    |--------------------------------------------------------------------------
    */

    Route::prefix('dokumentasi')
        ->name('dokumentasi.')
        ->group(function () {

        Route::post('/mutu-d/{mutuD}', [
            DokumentasiController::class,
            'store'
        ])->name('store');

        Route::get('/{foto}', [
            DokumentasiController::class,
            'show'
        ])->name('show');

        Route::delete('/{foto}', [
            DokumentasiController::class,
            'destroy'
        ])->name('destroy');

        Route::get('/{foto}/download', [
            DokumentasiController::class,
            'download'
        ])->name('download');

    });


    /*
    |--------------------------------------------------------------------------
    | WHATSAPP / FONNTE
    |--------------------------------------------------------------------------
    */

    Route::prefix('whatsapp')
        ->name('whatsapp.')
        ->group(function () {

        Route::post('/send/{pemberitahuan}', [
            WhatsAppController::class,
            'send'
        ])->name('send');

        Route::post('/test', [
            WhatsAppController::class,
            'test'
        ])->name('test');

        Route::get('/status/{pemberitahuan}', [
            WhatsAppController::class,
            'status'
        ])->name('status');

    });


    /*
    |--------------------------------------------------------------------------
    | MONITORING
    |--------------------------------------------------------------------------
    */

    Route::get('/monitoring', [PemberitahuanController::class, 'monitoring'])
    ->name('monitoring.index');

Route::get('/monitoring/{pemberitahuan}', [PemberitahuanController::class, 'monitoringDetail'])
    ->name('monitoring.detail');


});