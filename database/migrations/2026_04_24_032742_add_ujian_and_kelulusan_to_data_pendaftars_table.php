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
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->string('status_ujian')->nullable()->default('Belum Ujian');
            $table->string('status_kelulusan')->nullable()->default('Belum Ditentukan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn(['status_ujian', 'status_kelulusan']);
        });
    }
};
