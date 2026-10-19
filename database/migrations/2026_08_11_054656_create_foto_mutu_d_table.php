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
        Schema::create('foto_mutu_d', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mutu_d_id')
                ->constrained('mutu_d')
                ->cascadeOnDelete();

            $table->string('nama_file');
            $table->string('path_file');

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_mutu_d');
    }
};