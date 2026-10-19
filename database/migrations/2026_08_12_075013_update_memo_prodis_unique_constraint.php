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
        /*
        |--------------------------------------------------------------------------
        | Index import_prodis_id
        |--------------------------------------------------------------------------
        |
        | Foreign key mutu_d.memo_prodis_id membutuhkan index pada
        | kolom import_prodis_id di tabel memo_prodis.
        |
        | Karena unique lama menggunakan:
        |
        | import_prodis_id + nomor_memo
        |
        | kita tidak boleh langsung menghapus index tersebut.
        |
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->index(
                'import_prodis_id',
                'memo_prodis_import_prodis_id_index'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Hapus unique lama
        |--------------------------------------------------------------------------
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->dropUnique(
                'memo_prodis_import_prodis_id_nomor_memo_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Unique nomor memo secara global
        |--------------------------------------------------------------------------
        |
        | Satu nomor memo hanya boleh ada satu kali,
        | walaupun berasal dari import yang berbeda.
        |
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->unique(
                'nomor_memo',
                'memo_prodis_nomor_memo_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Hapus unique nomor memo
        |--------------------------------------------------------------------------
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->dropUnique(
                'memo_prodis_nomor_memo_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Kembalikan unique lama
        |--------------------------------------------------------------------------
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->unique(
                [
                    'import_prodis_id',
                    'nomor_memo',
                ],
                'memo_prodis_import_prodis_id_nomor_memo_unique'
            );
        });

        /*
        |--------------------------------------------------------------------------
        | Hapus index tambahan
        |--------------------------------------------------------------------------
        */

        Schema::table('memo_prodis', function (Blueprint $table) {
            $table->dropIndex(
                'memo_prodis_import_prodis_id_index'
            );
        });
    }
};