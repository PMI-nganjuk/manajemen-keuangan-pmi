<?php

namespace App\Http\Controllers;

use App\Models\Penyesuaian;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenyesuaianController extends Controller
{
    // Display all Penyesuaian
    public function index()
    {
        return response()->json(
            Penyesuaian::with('coa')->get()
        );
    }

    public function create()
    {
        return response()->json(['message' => 'Form create Penyesuaian']);
    }

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

    public function show(Penyesuaian $penyesuaian)
    {
        return response()->json(
            $penyesuaian->load(['coa', 'laporan'])
        );
    }

    public function edit(Penyesuaian $penyesuaian)
    {
        return response()->json([
            'message' => 'Form edit Penyesuaian',
            'data' => $penyesuaian
        ]);
    }

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

    public function destroy(Penyesuaian $penyesuaian)
    {
        $penyesuaian->delete();
        return response()->json([
            'message' => 'Penyesuaian berhasil dihapus'
        ]);
    }

    // IMPORT PENYESUAIAN
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $rows = Excel::toArray([], $request->file('file'))[0];

        foreach ($rows as $i => $row) {
            if ($i == 0) continue; // skip header

            Penyesuaian::create([
                'tanggal'          => $row[1],
                'no_dokumen'       => $row[2],
                'id_program_kerja' => $row[3],
                'referensi'        => $row[4],
                'id_coa'           => $row[5],
                'debit'            => $row[6],
                'kredit'           => $row[7],
                'keterangan'       => $row[8],
                'saldo_awal'       => $row[9],
            ]);
        }

        return response()->json(['message' => 'Import penyesuaian berhasil']);
    }

    public function exportExcel()
    {
        $data = Penyesuaian::with(['coa', 'laporan'])
            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal'        => $item->tanggal,
                    'No Dokumen'     => $item->no_dokumen,
                    'Program Kerja'  => $item->id_program_kerja,
                    'Referensi'      => $item->referensi,
                    'COA Transaksi'  => $item->id_coa,
                    'Debit'          => $item->debit,
                    'Kredit'         => $item->kredit,
                    'Keterangan'     => $item->keterangan,
                    'Saldo Awal'     => $item->saldo_awal,
                    'ID Laporan'     => $item->id_laporan,
                ];
            });

        // Anonymous class — no extra file created
        $export = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
        };

        return Excel::download($export, 'Penyesuaian.xlsx');
    }
}
