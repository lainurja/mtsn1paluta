<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class Admin extends Authenticatable implements CanResetPasswordContract
{
    use HasApiTokens, Notifiable, CanResetPassword;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        // Custom URL for admin password reset if needed, otherwise use default
        $url = env('FRONTEND_URL', 'http://localhost:3000') . '/admin/reset-password?token=' . $token . '&email=' . urlencode($this->email);

        \Illuminate\Auth\Notifications\ResetPassword::createUrlUsing(fn() => $url);

        $this->notify(new \Illuminate\Auth\Notifications\ResetPassword($token));
    }
}
