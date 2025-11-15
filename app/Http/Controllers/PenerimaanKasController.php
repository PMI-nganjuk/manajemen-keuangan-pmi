<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanKas;
use Illuminate\Http\Request;

class PenerimaanKasController extends Controller
{
    // Tampilkan daftar penerimaan kas
    public function index()
    {
        $penerimaan = PenerimaanKas::latest()->get();
        return response()->json($penerimaan);
    }

    // Simpan data penerimaan kas
    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'sumber_dana' => 'required|string|max:100',
        ]);

        $data = PenerimaanKas::create($validated);

        return response()->json([
            'message' => 'Data penerimaan berhasil ditambahkan',
            'data' => $data
        ], 201);
    }

    // Tampilkan detail penerimaan kas berdasarkan ID
    public function show($id)
    {
        $penerimaan = PenerimaanKas::findOrFail($id);
        return response()->json($penerimaan);
    }

    // Update data penerimaan kas
    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'tanggal' => 'sometimes|date',
            'deskripsi' => 'sometimes|string|max:255',
            'jumlah' => 'sometimes|numeric|min:0',
            'sumber_dana' => 'sometimes|string|max:100',
        ]);

        $penerimaan = PenerimaanKas::findOrFail($id);
        $penerimaan->update($validated);

        return response()->json([
            'message' => 'Data penerimaan berhasil diperbarui',
            'data' => $penerimaan
        ]);
    }

    // Hapus data penerimaan kas
    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $penerimaan = PenerimaanKas::findOrFail($id);
        $penerimaan->delete();

        return response()->json(['message' => 'Data penerimaan berhasil dihapus']);
    }
}
