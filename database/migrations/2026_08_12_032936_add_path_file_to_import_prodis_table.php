<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_prodis', function (Blueprint $table) {
            $table->string('path_file')->nullable()->after('nama_file');
        });
    }

    public function down(): void
    {
        Schema::table('import_prodis', function (Blueprint $table) {
            $table->dropColumn('path_file');
        });
    }
};