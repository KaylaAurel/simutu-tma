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
        Schema::table('pemberitahuan_details', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | PTA
            |--------------------------------------------------------------------------
            */

            $table->foreignId('pta_id')
                ->nullable()
                ->after('pemberitahuan_id')
                ->constrained('ptas')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | STATUS PENGIRIMAN
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'pending',
                'sent',
                'delivered',
                'failed',
            ])
                ->default('pending')
                ->after('mutu_d_id');


            /*
            |--------------------------------------------------------------------------
            | WAKTU TERKIRIM
            |--------------------------------------------------------------------------
            */

            $table->timestamp('sent_at')
                ->nullable()
                ->after('status');


            /*
            |--------------------------------------------------------------------------
            | RESPONSE FONNTE
            |--------------------------------------------------------------------------
            */

            $table->text('response')
                ->nullable()
                ->after('sent_at');


            /*
            |--------------------------------------------------------------------------
            | MESSAGE ID
            |--------------------------------------------------------------------------
            */

            $table->string('message_id')
                ->nullable()
                ->after('response');


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            $table->text('error_message')
                ->nullable()
                ->after('message_id');


            /*
            |--------------------------------------------------------------------------
            | INDEX
            |--------------------------------------------------------------------------
            */

            $table->index('pta_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemberitahuan_details', function (Blueprint $table) {

            $table->dropForeign([
                'pta_id',
            ]);

            $table->dropIndex([
                'pta_id',
            ]);

            $table->dropIndex([
                'status',
            ]);

            $table->dropColumn([
                'pta_id',
                'status',
                'sent_at',
                'response',
                'message_id',
                'error_message',
            ]);
        });
    }
};