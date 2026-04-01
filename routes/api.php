<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminPasswordResetController;

// Admin Authentication & Password Reset
Route::post('/admin/login',           [AdminAuthController::class, 'login']);
Route::post('/admin/forgot-password', [AdminPasswordResetController::class, 'sendResetLink']);
Route::post('/admin/reset-password',  [AdminPasswordResetController::class, 'resetPassword']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Selamat datang di dashboard admin']);
    });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ─── Password Reset (tidak perlu auth) ────────────────────────────────────────
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
