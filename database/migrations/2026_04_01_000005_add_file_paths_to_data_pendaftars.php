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
            $table->string('path_kk')->nullable();
            $table->string('path_ktp')->nullable();
            $table->string('path_foto')->nullable();
            $table->string('path_raport')->nullable();
            $table->string('path_surat_aktif')->nullable();
            $table->string('path_nisn')->nullable();
            $table->string('path_sertifikat')->nullable();
            $table->boolean('is_final_submit')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'path_kk', 'path_ktp', 'path_foto', 'path_raport', 
                'path_surat_aktif', 'path_nisn', 'path_sertifikat',
                'is_final_submit'
            ]);
        });
    }
};
