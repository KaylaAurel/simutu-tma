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
        Schema::create('import_prodis', function (Blueprint $table) {
            $table->id();

            $table->string('nama_file');
            $table->date('tanggal_import');

            $table->unsignedInteger('jumlah_data')->default(0);
            $table->unsignedInteger('jumlah_memo')->default(0);

            $table->enum('status', [
                'uploaded',
                'processing',
                'success',
                'failed',
            ])->default('uploaded');

            $table->foreignId('imported_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_prodis');
    }
};