<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        return response()->json(Berita::latest()->get());
    }

    public function show($id)
    {
        $berita = Berita::find($id);
        if (!$berita) return response()->json(['message' => 'Berita tidak ditemukan'], 404);
        return response()->json($berita);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'konten' => 'required|string',
            'kategori' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $berita = new Berita();
        $berita->judul = $request->judul;
        $berita->slug = \Illuminate\Support\Str::slug($request->judul) . '-' . time();
        $berita->desc = $request->desc ?? '';
        $berita->konten = $request->konten;
        $berita->kategori = $request->kategori ?? 'Umum';

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('berita', 'public');
            $berita->gambar = $path;
        }

        $berita->save();
        return response()->json(['message' => 'Berita berhasil dibuat', 'data' => $berita], 201);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);
        if (!$berita) return response()->json(['message' => 'Berita tidak ditemukan'], 404);

        $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'desc' => 'nullable|string',
            'konten' => 'sometimes|required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->judul) {
            $berita->judul = $request->judul;
            $berita->slug = \Illuminate\Support\Str::slug($request->judul) . '-' . time();
        }
        
        if ($request->desc) $berita->desc = $request->desc;
        if ($request->konten) $berita->konten = $request->konten;
        if ($request->kategori) $berita->kategori = $request->kategori;

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($berita->gambar) \Illuminate\Support\Facades\Storage::disk('public')->delete($berita->gambar);
            
            $path = $request->file('gambar')->store('berita', 'public');
            $berita->gambar = $path;
        }

        $berita->save();
        return response()->json(['message' => 'Berita berhasil diperbarui', 'data' => $berita]);
    }

    public function destroy($id)
    {
        $berita = Berita::find($id);
        if (!$berita) return response()->json(['message' => 'Berita tidak ditemukan'], 404);

        if ($berita->gambar) \Illuminate\Support\Facades\Storage::disk('public')->delete($berita->gambar);
        
        $berita->delete();
        return response()->json(['message' => 'Berita berhasil dihapus']);
    }
}
