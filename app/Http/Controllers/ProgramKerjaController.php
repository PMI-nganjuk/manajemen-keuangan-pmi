<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use Illuminate\Http\Request;

class ProgramKerjaController extends Controller
{
    // Show semua program kerja.
    public function index()
    {
        $programKerjas = ProgramKerja::with('kategori')->get();
        return response()->json($programKerjas);
    }

    // Save program kerja baru.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_program'     => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'anggaran'         => 'required|numeric|min:0',
            'kategori_id'      => 'required|exists:kategori_dua,id',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $programKerja = ProgramKerja::create($validated);

        return response()->json([
            'message' => 'Program kerja berhasil ditambahkan',
            'data' => $programKerja
        ], 201);
    }

    // Update program kerja.
    public function update(Request $request, $id)
    {
        $programKerja = ProgramKerja::findOrFail($id);

        $validated = $request->validate([
            'nama_program'     => 'sometimes|required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'anggaran'         => 'sometimes|required|numeric|min:0',
            'kategori_id'      => 'sometimes|required|exists:kategori_dua,id',
            'tanggal_mulai'    => 'sometimes|required|date',
            'tanggal_selesai'  => 'sometimes|required|date|after_or_equal:tanggal_mulai',
        ]);

        $programKerja->update($validated);

        return response()->json([
            'message' => 'Program kerja berhasil diperbarui',
            'data' => $programKerja
        ]);
    }
}
