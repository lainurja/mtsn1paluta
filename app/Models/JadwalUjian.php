<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    protected $fillable = [
        'jenis_ujian',
        'lokasi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];
}
