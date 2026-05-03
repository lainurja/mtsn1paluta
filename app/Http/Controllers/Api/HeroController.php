<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hero;

class HeroController extends Controller
{
    public function index()
    {
        return response()->json(Hero::orderBy('urutan')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'gambar' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $hero = Hero::create($validated);
        return response()->json($hero, 201);
    }

    public function show($id)
    {
        $hero = Hero::findOrFail($id);
        return response()->json($hero);
    }

    public function update(Request $request, $id)
    {
        $hero = Hero::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'tagline' => 'sometimes|required|string|max:255',
            'gambar' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $hero->update($validated);
        return response()->json($hero);
    }

    public function destroy($id)
    {
        $hero = Hero::findOrFail($id);
        $hero->delete();
        return response()->json(null, 204);
    }
}
