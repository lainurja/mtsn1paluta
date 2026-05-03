<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\DataPendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPendaftarController extends Controller
{
    /**
     * Tampilkan semua pendaftar.
     */
    public function index(Request $request)
    {
        $query = Pendaftar::with('dataPendaftar')
            ->orderBy('id', 'desc');

        if ($request->has('status')) {
            $query->whereHas('dataPendaftar', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->has('tahun_ajaran')) {
            $query->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $pendaftars = $query->get();

        return response()->json([
            'success' => true,
            'data' => $pendaftars
        ]);
    }

    /**
     * Tampilkan detail pendaftar.
     */
    public function show($id)
    {
        $pendaftar = Pendaftar::with('dataPendaftar')->find($id);

        if (!$pendaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftar tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pendaftar
        ]);
    }

    /**
     * Update status pendaftaran.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Terverifikasi,Data Kurang,Ditolak',
        ]);

        $data = DataPendaftar::where('pendaftar_id', $id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftar belum diisi.'
            ], 404);
        }

        $data->status = $request->status;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pendaftaran berhasil diperbarui.',
            'data' => $data
        ]);
    }

    public function updateStatusUjian(Request $request, $id)
    {
        $request->validate([
            'status_ujian' => 'required|string',
        ]);

        $data = DataPendaftar::where('pendaftar_id', $id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftar belum diisi.'
            ], 404);
        }

        $data->status_ujian = $request->status_ujian;
        
        // Auto-verify administrative status if exam is already taken
        if ($request->status_ujian === 'Sudah Ujian' && $data->status === 'Pending') {
            $data->status = 'Terverifikasi';
        }
        
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Status ujian berhasil diperbarui.',
            'data' => $data
        ]);
    }

    public function updateStatusKelulusan(Request $request, $id)
    {
        $request->validate([
            'status_kelulusan' => 'required|string',
        ]);

        $data = DataPendaftar::where('pendaftar_id', $id)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftar belum diisi.'
            ], 404);
        }

        $data->status_kelulusan = $request->status_kelulusan;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Status kelulusan berhasil diperbarui.',
            'data' => $data
        ]);
    }

    /**
     * Statistik untuk Dashboard.
     */
    public function stats()
    {
        $total = Pendaftar::count();
        
        // Stats from DataPendaftar
        $stats = DataPendaftar::select(
            DB::raw('count(*) as total'),
            DB::raw('sum(case when status = "Terverifikasi" then 1 else 0 end) as terverifikasi'),
            DB::raw('sum(case when status = "Pending" then 1 else 0 end) as pending'),
            DB::raw('sum(case when status = "Ditolak" then 1 else 0 end) as ditolak')
        )->first();

        return response()->json([
            'success' => true,
            'total_pendaftar' => $total,
            'terverifikasi' => (int) $stats->terverifikasi,
            'pending' => (int) $stats->pending,
            'ditolak' => (int) $stats->ditolak
        ]);
    }
}
