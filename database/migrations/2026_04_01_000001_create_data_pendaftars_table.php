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
        Schema::create('data_pendaftars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftar_id')->constrained('pendaftars')->onDelete('cascade');
            
            // Data Siswa
            $table->string('npsn_sd_mi');
            $table->string('asal_sd_mi');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('ttl_siswa');
            $table->string('agama_siswa');
            $table->integer('anak_ke');
            $table->integer('jml_saudara_kandung');
            $table->integer('jml_saudara_tiri');
            $table->text('alamat_siswa');
            $table->string('kec_siswa');
            $table->string('kab_siswa');

            // Data Ortu
            $table->string('nama_ayah');
            $table->string('ttl_ayah');
            $table->string('pendidikan_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('agama_ayah');
            $table->string('nama_ibu');
            $table->string('ttl_ibu');
            $table->string('pendidikan_ibu');
            $table->string('pekerjaan_ibu');
            $table->text('alamat_ortu');
            $table->decimal('penghasilan_ortu', 15, 2);
            $table->string('no_hp_ortu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pendaftars');
    }
};
