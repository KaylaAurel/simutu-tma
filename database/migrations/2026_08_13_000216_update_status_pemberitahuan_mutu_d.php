<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mutu_d', function (Blueprint $table) {
            $table->enum('status_pemberitahuan', [
                'belum_diberitahu',
                'diproses',
                'sudah_diberitahukan',
                'gagal',
            ])
            ->default('belum_diberitahu')
            ->change();
        });
    }

    public function down(): void
    {
        Schema::table('mutu_d', function (Blueprint $table) {
            $table->enum('status_pemberitahuan', [
                'belum_diberitahu',
                'draft',
                'terkirim',
            ])
            ->default('belum_diberitahu')
            ->change();
        });
    }
};