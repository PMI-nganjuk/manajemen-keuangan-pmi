<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(LaporanKeuangan::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Form create laporan keuangan']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'kas_tahun1' => 'required|integer|min:0',
            'kas_tahun2' => 'required|integer|min:0',
        ]);

        $input = $request->only(['kas_tahun1', 'kas_tahun2']);
        $input['saldo_akhir'] = $input['kas_tahun2'] - $input['kas_tahun1'];

        $data = LaporanKeuangan::create($input);

        return response()->json([
            'message' => 'Laporan keuangan berhasil ditambahkan',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(LaporanKeuangan $laporanKeuangan)
    {
        return response()->json($laporanKeuangan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaporanKeuangan $laporanKeuangan)
    {
        return response()->json([
            'message' => 'Form edit laporan keuangan',
            'data' => $laporanKeuangan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        $request->validate([
            'kas_tahun1' => 'integer|min:0',
            'kas_tahun2' => 'integer|min:0',
        ]);

        $laporanKeuangan->update([
            'kas_tahun1' => $request->kas_tahun1 ?? $laporanKeuangan->kas_tahun1,
            'kas_tahun2' => $request->kas_tahun2 ?? $laporanKeuangan->kas_tahun2,
            'saldo_akhir' => ($request->kas_tahun2 ?? $laporanKeuangan->kas_tahun2)
                             - ($request->kas_tahun1 ?? $laporanKeuangan->kas_tahun1),
        ]);

        return response()->json([
            'message' => 'Laporan keuangan berhasil diperbarui',
            'data' => $laporanKeuangan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaporanKeuangan $laporanKeuangan)
    {
        $laporanKeuangan->delete();

        return response()->json([
            'message' => 'Laporan keuangan berhasil dihapus'
        ]);
    }
}
