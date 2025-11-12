<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranKas;
use Illuminate\Http\Request;

class PengeluaranKasController extends Controller
{
    public function __construct()
    {
        // Role-based access
        $this->middleware('role:admin,staf_keuangan,manajer_keuangan,pegawai');
    }

    public function index()
    {
        $pengeluaran = PengeluaranKas::latest()->get();
        return response()->json($pengeluaran);
    }

    public function store(Request $request)
    {
        // Hanya admin, staf keuangan, dan manajer keuangan yang boleh menambah data
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

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

    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['admin', 'staf_keuangan', 'manajer_keuangan'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pengeluaran = PengeluaranKas::findOrFail($id);
        $pengeluaran->delete();

        return response()->json(['message' => 'Data pengeluaran berhasil dihapus']);
    }
}
