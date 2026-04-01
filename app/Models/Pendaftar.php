<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;


class Pendaftar extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'nisn',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function dataPendaftar()
    {
        return $this->hasOne(DataPendaftar::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $url = env('FRONTEND_URL') . '/reset-password?token=' . $token . '&email=' . urlencode($this->email);

        ResetPasswordNotification::createUrlUsing(fn() => $url);

        $this->notify(new ResetPasswordNotification($token));
    }
}
