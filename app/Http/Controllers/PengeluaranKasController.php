<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranKas;
use Illuminate\Http\Request;

// Controller untuk mengelola pengeluaran kas
class PengeluaranKasController extends Controller
{
    // Tampilkan daftar pengeluaran kas
    public function index()
    {
        $pengeluaran = PengeluaranKas::latest()->get();
        return response()->json($pengeluaran);
    }

    // Simpan data pengeluaran kas
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'kategori_id' => 'required|integer',
        ]);

        $data = PengeluaranKas::create($validated);

        return response()->json([
            'message' => 'Data pengeluaran berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    public function show($id)
    {
        $pengeluaran = PengeluaranKas::findOrFail($id);
        return response()->json($pengeluaran);
    }

    // Update data pengeluaran kas
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'sometimes|date',
            'deskripsi' => 'sometimes|string|max:255',
            'jumlah' => 'sometimes|numeric|min:0',
            'kategori_id' => 'sometimes|integer',
        ]);

        $pengeluaran = PengeluaranKas::findOrFail($id);
        $pengeluaran->update($validated);

        return response()->json([
            'message' => 'Data pengeluaran berhasil diperbarui',
            'data' => $pengeluaran
        ]);
    }

    // Hapus data pengeluaran kas
    public function destroy($id)
    {
        $pengeluaran = PengeluaranKas::findOrFail($id);
        $pengeluaran->delete();

        return response()->json(['message' => 'Data pengeluaran berhasil dihapus']);
    }
}
