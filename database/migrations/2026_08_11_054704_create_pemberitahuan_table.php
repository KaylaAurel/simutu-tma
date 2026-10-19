<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemberitahuan', function (Blueprint $table) {
            $table->id();


            /*
            |--------------------------------------------------------------------------
            | PTA
            |--------------------------------------------------------------------------
            */

            $table->foreignId('pta_id')
                ->constrained('ptas')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | INFORMASI PEMBERITAHUAN
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal_pemberitahuan');

            $table->string('judul');

            $table->text('isi_pesan');


            /*
            |--------------------------------------------------------------------------
            | PDF
            |--------------------------------------------------------------------------
            */

            $table->string('nama_file_pdf')->nullable();
            $table->string('path_pdf')->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS PESAN
            |--------------------------------------------------------------------------
            */

            $table->enum('status_pesan', [
                'draft',
                'processing',
                'terkirim',
                'gagal',
            ])->default('draft');


            /*
            |--------------------------------------------------------------------------
            | STATUS PDF
            |--------------------------------------------------------------------------
            */

            $table->enum('status_pdf', [
                'belum_dibuat',
                'tersedia',
                'gagal',
            ])->default('belum_dibuat');


            /*
            |--------------------------------------------------------------------------
            | WAKTU PENGIRIMAN
            |--------------------------------------------------------------------------
            */

            $table->timestamp('waktu_kirim_pesan')->nullable();
            $table->timestamp('waktu_kirim_pdf')->nullable();


            /*
            |--------------------------------------------------------------------------
            | USER PEMBUAT
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('tanggal_pemberitahuan');
            $table->index('status_pesan');
            $table->index('status_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemberitahuan');
    }
};