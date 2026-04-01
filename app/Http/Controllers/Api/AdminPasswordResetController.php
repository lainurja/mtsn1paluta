<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AdminPasswordResetController extends Controller
{
    /**
     * Step 1: Kirim link / token reset password ke email admin.
     */
    public function sendResetLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:admins,email'],
        ], [
            'email.exists' => 'Email admin tidak terdaftar.',
        ]);

        // Gunakan broker 'admins'
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email admin Anda.',
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
            'email'    => ['required', 'email', 'exists:admins,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(6)],
        ], [
            'email.exists'       => 'Email admin tidak terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Gunakan broker 'admins'
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Cabut semua token lama
                $admin->tokens()->delete();

                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password admin berhasil direset. Silakan login kembali.',
            ]);
        }

        // Token tidak valid / kedaluwarsa
        throw ValidationException::withMessages([
            'token' => [__($status)],
        ]);
    }
}
