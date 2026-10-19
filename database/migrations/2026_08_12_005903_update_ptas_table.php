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
        Schema::table('ptas', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Tambahkan kode PTA
            |--------------------------------------------------------------------------
            */

            $table->string('kode_pta', 50)
                ->unique()
                ->after('id');


            /*
            |--------------------------------------------------------------------------
            | Ganti nama nomor_whatsapp
            |--------------------------------------------------------------------------
            */

            $table->renameColumn(
                'nomor_whatsapp',
                'no_whatsapp'
            );

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ptas', function (Blueprint $table) {

            $table->dropUnique([
                'ptas_kode_pta_unique'
            ]);

            $table->dropColumn('kode_pta');

            $table->renameColumn(
                'no_whatsapp',
                'nomor_whatsapp'
            );

        });
    }
};