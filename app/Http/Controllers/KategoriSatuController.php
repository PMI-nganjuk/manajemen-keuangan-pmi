<?php

namespace App\Http\Controllers;

use App\Models\KategoriSatu;
use Illuminate\Http\Request;

class KategoriSatuController extends Controller
{
    // Show all kategori satu beserta kategori dua terkait
    public function index()
    {
        $kategoriSatu = KategoriSatu::with('kategoriDua')->get();
        return response()->json($kategoriSatu);
    }

    // Save kategori satu baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_coa' => 'required|exists:coa,id_coa',
        ]);

        $kategori = KategoriSatu::create($validated);
        return response()->json(['message' => 'Kategori satu berhasil ditambahkan.', 'data' => $kategori], 201);
    }

    // Update kategori satu
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_coa' => 'required|exists:coa,id_coa',
        ]);

        $kategori = KategoriSatu::findOrFail($id);
        $kategori->update($validated);

        return response()->json(['message' => 'Kategori satu berhasil diperbarui.', 'data' => $kategori]);
    }
}
