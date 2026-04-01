<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    /**
     * Handle the admin login attempt using database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email admin wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password admin wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Find admin by email
        $admin = Admin::where('email', $request->email)->first();

        // 3. Check credentials
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Success: Login to session
            \Illuminate\Support\Facades\Auth::guard('admin')->login($admin);

            // Create a Sanctum token (tetap buat token jika sewaktu-waktu butuh kriteria bearer)
            $token = $admin->createToken('admin-token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login admin berhasil.',
                'data' => [
                    'admin_token' => $token,
                    'email' => $admin->email
                ]
            ], 200);
        }

        // 4. Incorrect credentials
        return response()->json([
            'success' => false,
            'message' => 'Email atau password admin salah.',
        ], 401);
    }
}
