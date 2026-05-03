<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\AdminAuthController;
use App\Http\Controllers\Api\AdminPasswordResetController;
use App\Http\Controllers\Api\AdminPendaftarController;
use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\StrukturalController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\JadwalUjianController;

// Public Berita Routes
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);

// Staff (Struktural) Routes
Route::get('/staff', [StrukturalController::class, 'index']);
Route::get('/staff/{id}', [StrukturalController::class, 'show']);
Route::post('/staff', [StrukturalController::class, 'store']);
Route::put('/staff/{id}', [StrukturalController::class, 'update']);
Route::delete('/staff/{id}', [StrukturalController::class, 'destroy']);

// Storage Proxy for CORS (Development)
Route::get('/storage-proxy/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) return abort(404);
    return response()->file($fullPath);
})->where('path', '.*');

// Hero Routes
Route::get('/heroes', [HeroController::class, 'index']);
Route::get('/heroes/{id}', [HeroController::class, 'show']);
Route::post('/heroes', [HeroController::class, 'store']);
Route::put('/heroes/{id}', [HeroController::class, 'update']);
Route::delete('/heroes/{id}', [HeroController::class, 'destroy']);

// Admin Authentication & Password Reset
Route::post('/admin/login',           [AdminAuthController::class, 'login']);
Route::post('/admin/forgot-password', [AdminPasswordResetController::class, 'sendResetLink']);
Route::post('/admin/reset-password',  [AdminPasswordResetController::class, 'resetPassword']);

Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Selamat datang di dashboard admin']);
    });

    // Admin Pendaftar Routes
    Route::apiResource('/admin/jadwal-ujian', JadwalUjianController::class);
    Route::get('/admin/stats', [AdminPendaftarController::class, 'stats']);
    Route::get('/admin/pendaftar', [AdminPendaftarController::class, 'index']);
    Route::get('/admin/pendaftar/{id}', [AdminPendaftarController::class, 'show']);
    Route::put('/admin/pendaftar/{id}/status', [AdminPendaftarController::class, 'updateStatus']);
    Route::put('/admin/pendaftar/{id}/status-ujian', [AdminPendaftarController::class, 'updateStatusUjian']);
    Route::put('/admin/pendaftar/{id}/status-kelulusan', [AdminPendaftarController::class, 'updateStatusKelulusan']);
    // Admin Berita Routes
    Route::post('/admin/berita', [BeritaController::class, 'store']);
    Route::put('/admin/berita/{id}', [BeritaController::class, 'update']);
    Route::delete('/admin/berita/{id}', [BeritaController::class, 'destroy']);

    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);
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

    Route::post('/pilihan-gedung', [AuthController::class, 'updatePilihanGedung']);
    
    Route::get('/pendaftaran-data', [AuthController::class, 'getDataPendaftar']);
    Route::post('/pendaftaran-data', [AuthController::class, 'saveDataPendaftar']);
    Route::post('/upload-berkas', [AuthController::class, 'uploadBerkas']);
    
    // User Jadwal Ujian
    Route::get('/jadwal-ujian', [JadwalUjianController::class, 'index']);
});
