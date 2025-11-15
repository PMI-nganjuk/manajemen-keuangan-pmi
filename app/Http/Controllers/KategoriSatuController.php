<?php

namespace App\Http\Controllers;

use App\Models\KategoriSatu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriSatuController extends Controller
{
    public function index()
    {
        $kategoriSatu = KategoriSatu::with('kategoriDua')->get();
        return response()->json($kategoriSatu);
    }

    public function show($id)
    {
        $kategori = KategoriSatu::with('kategoriDua')->findOrFail($id);
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_coa' => 'required|exists:coa,id_coa',
        ]);

        $kategori = KategoriSatu::create($validated);
        return response()->json(['message' => 'Kategori satu berhasil ditambahkan.', 'data' => $kategori]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_coa' => 'required|exists:coa,id_coa',
        ]);

        $kategori = KategoriSatu::findOrFail($id);
        $kategori->update($validated);

        return response()->json(['message' => 'Kategori satu berhasil diperbarui.', 'data' => $kategori]);
    }

    public function destroy($id)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $kategori = KategoriSatu::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori satu berhasil dihapus.']);
    }

    private function authorizeAccess(array $allowedRoles)
    {
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }
    }
}
