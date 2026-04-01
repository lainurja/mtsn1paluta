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
            // Drop old columns
            $table->dropColumn(['ttl_siswa', 'ttl_ayah', 'ttl_ibu']);
            
            // Add new split columns for Siswa
            $table->string('tempat_lahir_siswa')->after('jenis_kelamin');
            $table->date('tanggal_lahir_siswa')->after('tempat_lahir_siswa');
            
            // Add new split columns for Ayah
            $table->string('tempat_lahir_ayah')->after('nama_ayah');
            $table->date('tanggal_lahir_ayah')->after('tempat_lahir_ayah');
            
            // Add new split columns for Ibu
            $table->string('tempat_lahir_ibu')->after('nama_ibu');
            $table->date('tanggal_lahir_ibu')->after('tempat_lahir_ibu');
            
            // Note: Pendidikan column is already there as string, we'll just handle it as Select in Frontend.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_pendaftars', function (Blueprint $table) {
            $table->dropColumn([
                'tempat_lahir_siswa', 'tanggal_lahir_siswa',
                'tempat_lahir_ayah', 'tanggal_lahir_ayah',
                'tempat_lahir_ibu', 'tanggal_lahir_ibu'
            ]);
            
            $table->string('ttl_siswa')->nullable();
            $table->string('ttl_ayah')->nullable();
            $table->string('ttl_ibu')->nullable();
        });
    }
};
