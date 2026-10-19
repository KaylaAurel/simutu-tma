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
        Schema::create('memo_prodis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('import_prodis_id')
                ->constrained('import_prodis')
                ->cascadeOnDelete();

            $table->string('nomor_memo');
            $table->date('tanggal_memo');

            $table->enum('status_proses', [
                'pending',
                'processing',
                'completed',
                'failed',
            ])->default('pending');

            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->unique([
                'import_prodis_id',
                'nomor_memo',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_prodis');
    }
};