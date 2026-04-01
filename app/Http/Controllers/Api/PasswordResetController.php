<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Step 1: Kirim link / token reset password ke email.
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:pendaftars,email'],
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        // Gunakan broker 'pendaftars' yang kita definisikan di config/auth.php
        $status = Password::broker('pendaftars')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link reset password telah dikirim ke email Anda.',
            ]);
        }

        // Jika gagal (misalnya throttle)
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }

    /**
     * Step 2: Reset password menggunakan token dari email.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email', 'exists:pendaftars,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(6)],
        ], [
            'email.exists'       => 'Email tidak terdaftar dalam sistem.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::broker('pendaftars')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Pendaftar $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Cabut semua token Sanctum lama agar user login ulang
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password berhasil direset. Silakan login kembali.',
            ]);
        }

        // Token tidak valid / kedaluwarsa
        throw ValidationException::withMessages([
            'token' => [__($status)],
        ]);
    }
}
