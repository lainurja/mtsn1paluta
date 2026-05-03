<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Struktural;

class StrukturalController extends Controller
{
    public function index(Request $request)
    {
        $query = Struktural::query();

        if ($request->has('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        return response()->json($query->orderBy('urutan')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:struktural,walas,guru',
            'foto' => 'nullable|image|max:2048',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('staff', 'public');
            $validated['foto'] = $path;
        }

        $struktural = Struktural::create($validated);
        return response()->json($struktural, 201);
    }

    public function show($id)
    {
        $struktural = Struktural::findOrFail($id);
        return response()->json($struktural);
    }

    public function update(Request $request, $id)
    {
        $struktural = Struktural::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'jabatan' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|in:struktural,walas,guru',
            'foto' => 'nullable',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists in storage
            if ($struktural->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($struktural->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($struktural->foto);
            }
            $path = $request->file('foto')->store('staff', 'public');
            $validated['foto'] = $path;
        } else {
            unset($validated['foto']);
        }

        $struktural->update($validated);
        return response()->json($struktural);
    }

    public function destroy($id)
    {
        $struktural = Struktural::findOrFail($id);
        $struktural->delete();
        return response()->json(null, 204);
    }
}
