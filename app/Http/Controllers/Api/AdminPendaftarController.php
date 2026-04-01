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
