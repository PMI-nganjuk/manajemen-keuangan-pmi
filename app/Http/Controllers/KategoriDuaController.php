<?php

namespace App\Http\Controllers;

use App\Models\KategoriDua;
use App\Models\KategoriSatu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriDuaController extends Controller
{
    public function index()
    {
        $kategoriDua = KategoriDua::with('kategoriSatu')->get();
        return response()->json($kategoriDua);
    }

    public function show($id)
    {
        $kategori = KategoriDua::with('kategoriSatu')->findOrFail($id);
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_kategori1'  => 'required|exists:kategori_satu,id_kategori1',
        ]);

        $kategori = KategoriDua::create($validated);

        return response()->json(['message' => 'Kategori dua berhasil ditambahkan.', 'data' => $kategori]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'id_kategori1'  => 'required|exists:kategori_satu,id_kategori1',
        ]);

        $kategori = KategoriDua::findOrFail($id);
        $kategori->update($validated);

        return response()->json(['message' => 'Kategori dua berhasil diperbarui.', 'data' => $kategori]);
    }

    public function destroy($id)
    {
        $this->authorizeAccess(['admin', 'manager_keuangan']);

        $kategori = KategoriDua::findOrFail($id);
        $kategori->delete();

        return response()->json(['message' => 'Kategori dua berhasil dihapus.']);
    }

    private function authorizeAccess(array $allowedRoles)
    {
        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }
    }
}
