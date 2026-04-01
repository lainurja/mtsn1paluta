<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPendaftar extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'npsn_sd_mi',
        'asal_sd_mi',
        'jenis_kelamin',
        'tempat_lahir_siswa',
        'tanggal_lahir_siswa',
        'agama_siswa',
        'anak_ke',
        'jml_saudara_kandung',
        'jml_saudara_tiri',
        'alamat_siswa',
        'kec_siswa',
        'kab_siswa',
        'nama_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'agama_ayah',
        'nama_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'alamat_ortu',
        'no_hp_ortu',
        'pengembangan_diri',
        'cita_cita',
        'path_kk',
        'path_ktp',
        'path_foto',
        'path_raport',
        'path_surat_aktif',
        'path_nisn',
        'path_sertifikat',
        'status',
        'is_final_submit',
    ];

    protected $casts = [
        'pengembangan_diri' => 'array',
        'is_final_submit'   => 'boolean',
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class);
    }
}
