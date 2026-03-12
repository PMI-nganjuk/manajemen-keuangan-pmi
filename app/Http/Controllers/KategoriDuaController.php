<?php

namespace App\Http\Controllers;

use App\Models\KategoriDua;
use Illuminate\Http\Request;

class KategoriDuaController extends Controller
{
    // Tampilkan semua kategori dua beserta kategori satu terkait
    public function index()
    {
        $kategoriDua = KategoriDua::with('kategoriSatu')->get();
        return response()->json($kategoriDua);
    }

    // Simpan kategori dua baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_kategori1'  => 'required|exists:kategori_satu,id_kategori1',
        ]);

        $kategori = KategoriDua::create($validated);

        return response()->json(['message' => 'Kategori dua berhasil ditambahkan.', 'data' => $kategori], 201);
    }

    // Update kategori dua
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_kategori1'  => 'required|exists:kategori_satu,id_kategori1',
        ]);

        $kategori = KategoriDua::findOrFail($id);
        $kategori->update($validated);

        return response()->json(['message' => 'Kategori dua berhasil diperbarui.', 'data' => $kategori]);
    }
}
