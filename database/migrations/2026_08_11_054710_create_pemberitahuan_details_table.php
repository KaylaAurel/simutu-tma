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
        Schema::create('pemberitahuan_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pemberitahuan_id')
                ->constrained('pemberitahuan')
                ->cascadeOnDelete();

            $table->foreignId('mutu_d_id')
                ->constrained('mutu_d')
                ->cascadeOnDelete();

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Mencegah satu Mutu D masuk dua kali
            | dalam pemberitahuan yang sama
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'pemberitahuan_id',
                'mutu_d_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemberitahuan_details');
    }
};