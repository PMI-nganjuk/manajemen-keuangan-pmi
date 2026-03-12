<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    // Display all COA
    public function index()
    {
        return response()->json(Coa::all());
    }

    // Form create
    public function create()
    {
        return response()->json(['message' => 'Form create COA']);
    }

    // Store COA
    public function store(Request $request)
    {
        $request->validate([
            'id_coa'      => 'required|string|unique:coa,id_coa',
            'kategori_1'  => 'nullable|string',
            'kategori_2'  => 'nullable|string',
            'nama_akun'   => 'required|string',
            'pos_saldo'   => 'required|string',
            'pos_laporan' => 'required|string',
        ]);

        $data = Coa::create($request->only([
            'id_coa',
            'kategori_1',
            'kategori_2',
            'nama_akun',
            'pos_saldo',
            'pos_laporan'
        ]));

        return response()->json([
            'message' => 'COA berhasil ditambahkan',
            'data'    => $data
        ]);
    }

    // Edit form
    public function edit(Coa $coa)
    {
        return response()->json([
            'message' => 'Form edit COA',
            'data'    => $coa
        ]);
    }

    // Update COA
    public function update(Request $request, Coa $coa)
    {
        $request->validate([
            'kategori_1'  => 'sometimes|string',
            'kategori_2'  => 'sometimes|string',
            'nama_akun'   => 'sometimes|string',
            'pos_saldo'   => 'sometimes|string',
            'pos_laporan' => 'sometimes|string',
        ]);

        $coa->update($request->only([
            'kategori_1',
            'kategori_2',
            'nama_akun',
            'pos_saldo',
            'pos_laporan'
        ]));

        return response()->json([
            'message' => 'COA berhasil diupdate',
            'data'    => $coa
        ]);
    }
}
