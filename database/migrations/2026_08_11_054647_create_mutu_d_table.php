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
        Schema::create('mutu_d', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | RELATION
            |--------------------------------------------------------------------------
            */

            $table->foreignId('memo_prodis_id')
                ->constrained('memo_prodis')
                ->cascadeOnDelete();

            $table->foreignId('pta_id')
                ->nullable()
                ->constrained('ptas')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | DATA SPTA / PETANI
            |--------------------------------------------------------------------------
            */

            $table->string('no_spta')->nullable();
            $table->string('no_kontrak')->nullable();

            $table->string('kode_afdeling')->nullable();
            $table->string('nama_asman')->nullable();

            $table->string('no_petani')->nullable();
            $table->text('deskripsi_blok')->nullable();

            $table->string('nama_petani')->nullable();


            /*
            |--------------------------------------------------------------------------
            | DATA KENDARAAN & KUALITAS
            |--------------------------------------------------------------------------
            */

            $table->string('nopol')->nullable();

            $table->string('kualitas')->default('D');


            /*
            |--------------------------------------------------------------------------
            | SELEKTOR
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal_selektor')->nullable();
            $table->time('jam_selektor')->nullable();


            /*
            |--------------------------------------------------------------------------
            | TIMBANG
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal_timbang')->nullable();
            $table->time('jam_timbang')->nullable();


            /*
            |--------------------------------------------------------------------------
            | MEJA TEBU
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal_meja_tebu')->nullable();
            $table->time('jam_meja_tebu')->nullable();


            /*
            |--------------------------------------------------------------------------
            | SKORSING
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('durasi_skorsing')
                ->default(3);

            $table->date('tanggal_skorsing_mulai')->nullable();
            $table->date('tanggal_skorsing_selesai')->nullable();


            /*
            |--------------------------------------------------------------------------
            | STATUS PEMBERITAHUAN
            |--------------------------------------------------------------------------
            */

            $table->enum('status_pemberitahuan', [
                'belum_diberitahu',
                'draft',
                'terkirim',
            ])->default('belum_diberitahu');


            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('no_spta');
            $table->index('no_kontrak');
            $table->index('nama_petani');
            $table->index('kualitas');
            $table->index('status_pemberitahuan');
            $table->index('tanggal_meja_tebu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutu_d');
    }
};