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
        $items = Penyesuaian::latest()->get();
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['template' => ['tanggal' => null, 'keterangan' => null, 'jumlah' => null]]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'   => 'required|date',
            'keterangan'=> 'nullable|string',
            'jumlah'    => 'required|numeric',
        ]);

        $item = Penyesuaian::create($validated);

        return response()->json(['message' => 'Penyesuaian berhasil dibuat.', 'data' => $item], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Penyesuaian $penyesuaian)
    {
        return response()->json($penyesuaian);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyesuaian $penyesuaian)
    {
        return response()->json($penyesuaian);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyesuaian $penyesuaian)
    {
        $validated = $request->validate([
            'tanggal'   => 'sometimes|required|date',
            'keterangan'=> 'nullable|string',
            'jumlah'    => 'sometimes|required|numeric',
        ]);

        $penyesuaian->update($validated);

        return response()->json(['message' => 'Penyesuaian berhasil diperbarui.', 'data' => $penyesuaian]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyesuaian $penyesuaian)
    {
        $penyesuaian->delete();

        return response()->json(['message' => 'Penyesuaian berhasil dihapus.']);
    }
}
