<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanKas;
use Illuminate\Http\Request;

class PenerimaanKasController extends Controller
{
    public function __construct()
    {
        // Role-based access
        $this->middleware('role:admin,staf_keuangan,manajer_keuangan,pegawai');
    }

    public function index()
    {
        $penerimaan = PenerimaanKas::latest()->get();
        return response()->json($penerimaan);
    }

    public function store(Request $request)
    {
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

    public function show($id)
    {
        $penerimaan = PenerimaanKas::findOrFail($id);
        return response()->json($penerimaan);
    }

    public function update(Request $request, $id)
    {
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

    public function destroy(Request $request, $id)
    {
        $penerimaan = PenerimaanKas::findOrFail($id);
        $penerimaan->delete();

        return response()->json(['message' => 'Data penerimaan berhasil dihapus']);
    }
}
