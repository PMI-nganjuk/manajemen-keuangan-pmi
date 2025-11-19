<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
       return response()->json(Coa::all());
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return response()->json(['message' => 'Form create COA']);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'id_coa' => 'required|string|unique:coa,id_coa',
            'nama_akun' => 'required|string',
            'pos_saldo' => 'required|string',
            'pos_laporan' => 'required|string',
        ]);

        $data = Coa::create($request->only([
            'id_coa', 'nama_akun', 'pos_saldo', 'pos_laporan'
        ]));

        return response()->json([
            'message' => 'COA berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // Display the specified resource.
    public function show(Coa $coa)
    {
        return response()->json($coa);
    }

    // Show the form for editing the specified resource.
    public function edit(Coa $coa)
    {
        return response()->json([
            'message' => 'Form edit COA',
            'data' => $coa
        ]);
    }

    // Update the specified resource in storage.
    public function update(Request $request, Coa $coa)
    {
        $request ->validate([
            'nama_akun' => 'sometimes|required|string',
            'pos_saldo' => 'sometimes|required|string',
            'pos_laporan' => 'sometimes|required|string',
        ]);

        $coa->update($request->only([
            'nama_akun', 'pos_saldo', 'pos_laporan'
        ]));

        return response()->json([
            'message' => 'COA berhasil diupdate',
            'data' => $coa
        ]);
    }

    // Remove the specified resource from storage.
    public function destroy(Coa $coa)
    {
        $coa->delete();

        return response()->json([
            'message' => 'COA berhasil dihapus'
        ]);
    }
}
