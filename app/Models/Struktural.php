<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktural extends Model
{
    /** @use HasFactory<\Database\Factories\StrukturalFactory> */
    use HasFactory;

    protected $fillable = [
        'nama',
        'jabatan',
        'kategori',
        'foto',
        'urutan',
    ];
}
