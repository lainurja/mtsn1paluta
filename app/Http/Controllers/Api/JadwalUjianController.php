<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalUjian;

class JadwalUjianController extends Controller
{
    public function index()
    {
        $jadwals = JadwalUjian::orderBy('tanggal', 'asc')->orderBy('jam_mulai', 'asc')->get();
        return response()->json([
            'success' => true,
            'data' => $jadwals
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_ujian' => 'required|string',
            'lokasi' => 'required|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = JadwalUjian::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal
        ]);
    }

    public function show(string $id)
    {
        $jadwal = JadwalUjian::find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $jadwal]);
    }

    public function update(Request $request, string $id)
    {
        $jadwal = JadwalUjian::find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'jenis_ujian' => 'sometimes|required|string',
            'lokasi' => 'sometimes|required|string',
            'tanggal' => 'sometimes|required|date',
            'jam_mulai' => 'sometimes|required',
            'jam_selesai' => 'sometimes|required',
        ]);

        $jadwal->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $jadwal
        ]);
    }

    public function destroy(string $id)
    {
        $jadwal = JadwalUjian::find($id);
        if (!$jadwal) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }
        $jadwal->delete();
        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }
}
