<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\DataPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'password'     => 'required|string|min:6|confirmed',
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

        // Check password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'nisn' => ['NISN atau password salah'],
            ]);
        }

        // Login user ke session (untuk stateful cookie)
        \Illuminate\Support\Facades\Auth::guard('pendaftar')->login($user);

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

    public function updatePilihanGedung(Request $request)
    {
        $request->validate([
            'pilihan_gedung' => 'required|string|in:Gedung A,Gedung B',
        ]);

        $user = $request->user();
        $user->pilihan_gedung = $request->pilihan_gedung;
        $user->save();

        return response()->json([
            'message' => 'Pilihan lokasi berhasil diperbarui',
            'pilihan_gedung' => $user->pilihan_gedung
        ]);
    }

    public function getDataPendaftar(Request $request)
    {
        $user = $request->user();
        $data = DataPendaftar::where('pendaftar_id', $user->id)->first();
        
        return response()->json([
            'user' => $user,
            'data_pendaftaran' => $data
        ]);
    }

    public function saveDataPendaftar(Request $request)
    {
        $user = $request->user();

        // Validasi (Menggunakan sometimes agar bisa simpan cicil per halaman)
        $validated = $request->validate([
            'npsn_sd_mi'           => 'sometimes|required|string',
            'asal_sd_mi'           => 'sometimes|required|string',
            'jenis_kelamin'        => 'sometimes|required|in:Laki-laki,Perempuan',
            'tempat_lahir_siswa'   => 'sometimes|required|string',
            'tanggal_lahir_siswa'  => 'sometimes|required|date',
            'agama_siswa'          => 'sometimes|required|string',
            'anak_ke'              => 'sometimes|required|integer',
            'jml_saudara_kandung'  => 'sometimes|required|integer',
            'jml_saudara_tiri'     => 'nullable|integer',
            'alamat_siswa'         => 'sometimes|required|string',
            'kec_siswa'            => 'sometimes|required|string',
            'kab_siswa'            => 'sometimes|required|string',
            'nama_ayah'            => 'sometimes|required|string',
            'tempat_lahir_ayah'    => 'sometimes|required|string',
            'tanggal_lahir_ayah'   => 'sometimes|required|date',
            'pendidikan_ayah'      => 'sometimes|required|string',
            'pekerjaan_ayah'       => 'sometimes|required|string',
            'penghasilan_ayah'     => 'sometimes|required|numeric',
            'agama_ayah'           => 'sometimes|required|string',
            'nama_ibu'             => 'sometimes|required|string',
            'tempat_lahir_ibu'     => 'sometimes|required|string',
            'tanggal_lahir_ibu'    => 'sometimes|required|date',
            'pendidikan_ibu'       => 'sometimes|required|string',
            'pekerjaan_ibu'        => 'sometimes|required|string',
            'penghasilan_ibu'      => 'sometimes|required|numeric',
            'alamat_ortu'          => 'sometimes|required|string',
            'no_hp_ortu'           => 'sometimes|required|string',
            'pengembangan_diri'    => 'nullable|array',
            'cita_cita'            => 'sometimes|required|string',
        ]);

        $data = DataPendaftar::updateOrCreate(
            ['pendaftar_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'Data pendaftaran berhasil disimpan',
            'data' => $data
        ]);
    }

    public function uploadBerkas(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'path_kk'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'path_ktp'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'path_foto'           => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'path_raport'         => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'path_surat_aktif'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'path_nisn'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'path_sertifikat'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_final_submit'     => 'sometimes|boolean',
        ]);

        $data = DataPendaftar::where('pendaftar_id', $user->id)->first();
        if (!$data) {
            $data = new DataPendaftar(['pendaftar_id' => $user->id]);
        }

        $files = [
            'path_kk', 'path_ktp', 'path_foto', 'path_raport', 
            'path_surat_aktif', 'path_nisn', 'path_sertifikat'
        ];

        foreach ($files as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($data->$field) {
                    Storage::disk('public')->delete($data->$field);
                }
                
                $path = $request->file($field)->store("uploads/pendaftar/{$user->id}", 'public');
                $data->$field = $path;
            }
        }

        if ($request->has('is_final_submit')) {
            $data->is_final_submit = $request->is_final_submit;
        }

        $data->save();

        return response()->json([
            'message' => 'Berkas berhasil diupload',
            'data' => $data
        ]);
    }
}
