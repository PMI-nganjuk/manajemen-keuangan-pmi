<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramKerjaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // pastikan user login
    }

    /**
     * Tampilkan semua program kerja.
     */
    public function index()
    {
        $programKerjas = ProgramKerja::with('kategori')->get();
        return response()->json($programKerjas);
    }

    /**
     * Simpan program kerja baru (hanya Manager Keuangan & Admin).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager_keuangan'])) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

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

    /**
     * Menampilkan detail program kerja berdasarkan ID.
     */
    public function show($id)
    {
        $programKerja = ProgramKerja::with('kategori')->findOrFail($id);
        return response()->json($programKerja);
    }

    /**
     * Update program kerja (hanya Manager Keuangan & Admin).
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'manager_keuangan'])) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

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

    /**
     * Hapus program kerja (hanya Admin).
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $programKerja = ProgramKerja::findOrFail($id);
        $programKerja->delete();

        return response()->json(['message' => 'Program kerja berhasil dihapus']);
    }
}
