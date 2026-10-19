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
        Schema::table('pemberitahuan', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | NOMOR PEMBERITAHUAN
            |--------------------------------------------------------------------------
            */

            $table->string('nomor')
                ->nullable()
                ->after('id');


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            |
            | Menyesuaikan dengan Pemberitahuan.php
            | dan PemberitahuanService.php
            |
            */

            $table->date('tanggal')
                ->nullable()
                ->after('judul');


            /*
            |--------------------------------------------------------------------------
            | ISI PESAN
            |--------------------------------------------------------------------------
            */

            $table->text('isi')
                ->nullable()
                ->after('tanggal');


            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'processing',
                'sent',
                'partial',
                'failed',
            ])
                ->default('draft')
                ->after('isi');


            /*
            |--------------------------------------------------------------------------
            | WAKTU PENGIRIMAN
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')
                ->nullable()
                ->after('status');


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('total_pta')
                ->default(0)
                ->after('sent_at');

            $table->unsignedInteger('total_mutu_d')
                ->default(0)
                ->after('total_pta');


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            $table->text('error_message')
                ->nullable()
                ->after('total_mutu_d');


            /*
            |--------------------------------------------------------------------------
            | UNIQUE NOMOR
            |--------------------------------------------------------------------------
            */

            $table->unique(
                'nomor',
                'pemberitahuan_nomor_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemberitahuan', function (Blueprint $table) {

            $table->dropUnique(
                'pemberitahuan_nomor_unique'
            );

            $table->dropColumn([
                'nomor',
                'tanggal',
                'isi',
                'status',
                'sent_at',
                'total_pta',
                'total_mutu_d',
                'error_message',
            ]);
        });
    }
};