<?php

namespace App\Http\Controllers;

use App\Models\ProfilPmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilPmiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Menampilkan profil PMI
    public function index()
    {
        $profil = ProfilPmi::first();
        return view('profil_pmi.index', compact('profil'));
    }

    // Menampilkan form edit profil PMI (hanya admin)
    public function edit()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $profil = ProfilPmi::first();
        return view('profil_pmi.edit', compact('profil'));
    }

    // Update profil PMI (hanya admin)
    public function update(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nama_lembaga' => 'required|string|max:255',
            'alamat'       => 'required|string',
            'email'        => 'nullable|email',
            'telepon'      => 'nullable|string|max:20',
            'deskripsi'    => 'nullable|string',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $profil = ProfilPmi::first();

        // Jika belum ada data profil, buat baru
        if (!$profil) {
            $profil = new ProfilPmi();
        }

        // Update field
        $profil->nama_lembaga = $request->nama_lembaga;
        $profil->alamat       = $request->alamat;
        $profil->email        = $request->email;
        $profil->telepon      = $request->telepon;
        $profil->deskripsi    = $request->deskripsi;

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            if ($profil->logo) {
                Storage::delete('public/' . $profil->logo);
            }
            $profil->logo = $request->file('logo')->store('profil_pmi', 'public');
        }

        $profil->save();

        return redirect()->route('profil_pmi.index')->with('success', 'Profil PMI berhasil diperbarui.');
    }
}
