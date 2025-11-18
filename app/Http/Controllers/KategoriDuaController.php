<?php

namespace App\Http\Controllers;

use App\Models\KategoriDua;
use App\Models\KategoriSatu;
use Illuminate\Http\Request;

class KategoriDuaController extends Controller
{
    // Tampilkan semua kategori dua beserta kategori satu terkait
    public function index()
    {
        $kategoriDua = KategoriDua::with('kategoriSatu')->get();
        return response()->json($kategoriDua);
    }

    // Tampilkan detail kategori dua berdasarkan ID
    public function show($id)
    {
        $kategori = KategoriDua::with('kategoriSatu')->findOrFail($id);
        return response()->json($kategori);
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

    // Hapus kategori dua
    public function destroy($id)
    {
        $kategori = KategoriDua::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori dua berhasil dihapus.']);
    }
}
