<?php

namespace App\Http\Controllers;

use App\Models\Penyesuaian;
use Illuminate\Http\Request;

class PenyesuaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Penyesuaian::with('coa')->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Form create Penyesuaian']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'no_dokumen' => 'nullable|string',
            'referensi' => 'nullable|string',
            'debit' => 'required|integer|min:0',
            'kredit' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'saldo_awal' => 'required|integer|min:0',

            // foreign keys
            'id_coa' => 'required|exists:coa,id_coa',
            'id_program_kerja' => 'required|exists:program_kerja,id_program_kerja',
            'id_laporan' => 'nullable|exists:laporan_keuangan,id_laporan',
        ]);

        $data = Penyesuaian::create($request->all());

        return response()->json([
            'message' => 'Penyesuaian berhasil ditambahkan',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyesuaian $penyesuaian)
    {
        return response()->json(
            $penyesuaian->load(['coa', 'laporan'])
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyesuaian $penyesuaian)
    {
        return response()->json([
            'message' => 'Form edit Penyesuaian',
            'data' => $penyesuaian
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyesuaian $penyesuaian)
    {
        $request->validate([
            'tanggal' => 'date',
            'no_dokumen' => 'string',
            'referensi' => 'string',
            'debit' => 'integer|min:0',
            'kredit' => 'integer|min:0',
            'keterangan' => 'string',
            'saldo_awal' => 'integer|min:0',

            'id_coa' => 'exists:coa,id_coa',
            'id_program_kerja' => 'exists:program_kerja,id_program_kerja',
            'id_laporan' => 'nullable|exists:laporan_keuangan,id_laporan',
        ]);

        $penyesuaian->update($request->all());

        return response()->json([
            'message' => 'Penyesuaian berhasil diperbarui',
            'data' => $penyesuaian
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyesuaian $penyesuaian)
    {
        $penyesuaian->delete();
        return response()->json([
            'message' => 'Penyesuaian berhasil dihapus'
        ]);
    }
}
