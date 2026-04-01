<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|string|email|unique:pendaftars,email',
            'nisn'         => 'required|string|unique:pendaftars,nisn',
            'password'     => 'required|string|min:6',
        ]);

        $user = Pendaftar::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'nisn'         => $request->nisn,
            'password'     => $request->password, // automatically hashed by casts
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user'    => $user,
            'token'   => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn'     => 'required|string',
            'password' => 'required|string',
        ]);

        $user = Pendaftar::where('nisn', $request->nisn)->first();

        // Check password. Laravel 11 automatically hashes on assignment if cast to hashed.
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nisn' => ['NISN atau password salah'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
