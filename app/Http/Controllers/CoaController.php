<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class CoaController extends Controller
{
    // Display all COA
    public function index()
    {
        return response()->json(Coa::all());
    }

    // Create form (just info)
    public function create()
    {
        return response()->json(['message' => 'Form create COA']);
    }

    // Store COA
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

    // Show COA by ID
    public function show(Coa $coa)
    {
        return response()->json($coa);
    }

    // Edit form
    public function edit(Coa $coa)
    {
        return response()->json([
            'message' => 'Form edit COA',
            'data' => $coa
        ]);
    }

    // Update COA
    public function update(Request $request, Coa $coa)
    {
        $request->validate([
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

    // Delete COA
    public function destroy(Coa $coa)
    {
        $coa->delete();

        return response()->json([
            'message' => 'COA berhasil dihapus'
        ]);
    }

    // IMPORT COA
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $rows = Excel::toArray([], $request->file('file'))[0];

        foreach ($rows as $i => $row) {
            if ($i == 0) continue; // skip header
            if (!isset($row[0]) || $row[0] == null) continue;

            Coa::updateOrCreate(
                ['id_coa' => $row[0]],
                [
                    'nama_akun'   => $row[3] ?? '',
                    'pos_saldo'   => $row[4] ?? '',
                    'pos_laporan' => $row[5] ?? '',
                ]
            );
        }

        return response()->json([
            'message' => 'Import COA berhasil'
        ]);
    }

    // EXPORT COA
    public function exportExcel()
    {
        $data = Coa::all()->map(function ($item) {
            return [
                'COA'         => $item->id_coa,
                'Kategori 1'  => '',
                'Kategori 2'  => '',
                'Nama Akun'   => $item->nama_akun,
                'Pos Saldo'   => $item->pos_saldo,
                'Pos Laporan' => $item->pos_laporan,
            ];
        });

        $export = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
        };

        return Excel::download($export, 'COA.xlsx');
    }
}
