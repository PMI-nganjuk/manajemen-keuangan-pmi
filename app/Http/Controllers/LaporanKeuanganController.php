<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use App\Models\Coa;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanKeuanganController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        // Ambil COA untuk membentuk laporan neraca
        $coa = Coa::select('id_coa', 'kategori_1', 'kategori_2', 'nama_akun', 'pos_saldo')
            ->orderBy('kategori_1')
            ->orderBy('kategori_2')
            ->get();

        $grouped = [
            'Aset Lancar'           => [],
            'Aset Tidak Lancar'     => [],
            'Liabilitas Lancar'     => [],
            'Liabilitas Tidak Lancar' => [],
            'Aset Netto'            => [],
        ];

        foreach ($coa as $row) {
            $main = strtolower($row->kategori_1 ?? '');

            if (str_contains($main, 'aset lancar')) {
                $grouped['Aset Lancar'][] = [
                    'coa'        => $row->id_coa,
                    'kategori_2' => $row->kategori_2,
                    'nama_akun'  => $row->nama_akun,
                    'pos_saldo'  => $row->pos_saldo,
                ];
            } elseif (str_contains($main, 'aset tidak lancar')) {
                $grouped['Aset Tidak Lancar'][] = [
                    'coa'        => $row->id_coa,
                    'kategori_2' => $row->kategori_2,
                    'nama_akun'  => $row->nama_akun,
                    'pos_saldo'  => $row->pos_saldo,
                ];
            } elseif (str_contains($main, 'liabilitas lancar')) {
                $grouped['Liabilitas Lancar'][] = [
                    'coa'        => $row->id_coa,
                    'kategori_2' => $row->kategori_2,
                    'nama_akun'  => $row->nama_akun,
                    'pos_saldo'  => $row->pos_saldo,
                ];
            } elseif (str_contains($main, 'liabilitas tidak lancar')) {
                $grouped['Liabilitas Tidak Lancar'][] = [
                    'coa'        => $row->id_coa,
                    'kategori_2' => $row->kategori_2,
                    'nama_akun'  => $row->nama_akun,
                    'pos_saldo'  => $row->pos_saldo,
                ];
            } elseif (str_contains($main, 'aset netto')) {
                $grouped['Aset Netto'][] = [
                    'coa'        => $row->id_coa,
                    'kategori_2' => $row->kategori_2,
                    'nama_akun'  => $row->nama_akun,
                    'pos_saldo'  => $row->pos_saldo,
                ];
            }
        }

        return response()->json([
            'laporan_neraca' => $grouped
        ]);
    }

    public function create()
    {
        return response()->json(['message' => 'Form create laporan keuangan']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kas_tahun1' => 'required|integer|min:0',
            'kas_tahun2' => 'required|integer|min:0',
        ]);

        $input = $request->only(['kas_tahun1', 'kas_tahun2']);
        $input['saldo_akhir'] = $input['kas_tahun2'] - $input['kas_tahun1'];

        $data = LaporanKeuangan::create($input);

        return response()->json([
            'message' => 'Laporan keuangan berhasil ditambahkan',
            'data'    => $data
        ]);
    }

    public function show(LaporanKeuangan $laporanKeuangan)
    {
        return response()->json($laporanKeuangan);
    }

    public function edit(LaporanKeuangan $laporanKeuangan)
    {
        return response()->json([
            'message' => 'Form edit laporan keuangan',
            'data'    => $laporanKeuangan
        ]);
    }

    public function update(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        $request->validate([
            'kas_tahun1' => 'integer|min:0',
            'kas_tahun2' => 'integer|min:0',
        ]);

        $laporanKeuangan->update([
            'kas_tahun1'  => $request->kas_tahun1 ?? $laporanKeuangan->kas_tahun1,
            'kas_tahun2'  => $request->kas_tahun2 ?? $laporanKeuangan->kas_tahun2,
            'saldo_akhir' => ($request->kas_tahun2 ?? $laporanKeuangan->kas_tahun2)
                           - ($request->kas_tahun1 ?? $laporanKeuangan->kas_tahun1),
        ]);

        return response()->json([
            'message' => 'Laporan keuangan berhasil diperbarui',
            'data'    => $laporanKeuangan
        ]);
    }

    public function destroy(LaporanKeuangan $laporanKeuangan)
    {
        $laporanKeuangan->delete();

        return response()->json([
            'message' => 'Laporan keuangan berhasil dihapus'
        ]);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $rows = Excel::toArray([], $request->file('file'))[0];

        foreach ($rows as $i => $row) {
            if ($i === 0) continue; // skip header
            if (!$row || !isset($row[0]) || !isset($row[1])) continue;

            $kas1 = (int) $row[0];
            $kas2 = (int) $row[1];

            LaporanKeuangan::create([
                'kas_tahun1'  => $kas1,
                'kas_tahun2'  => $kas2,
                'saldo_akhir' => $kas2 - $kas1
            ]);
        }

        return response()->json([
            'message' => 'Import laporan keuangan berhasil'
        ]);
    }

    public function exportExcel()
    {
        $data = LaporanKeuangan::all()->map(function ($item) {
            return [
                'Kas Tahun 1' => $item->kas_tahun1,
                'Kas Tahun 2' => $item->kas_tahun2,
                'Saldo Akhir' => $item->saldo_akhir,
            ];
        });

        $export = new class($data) implements FromCollection {
            protected $data;
            public function __construct($data) { $this->data = $data; }
            public function collection() { return $this->data; }
        };

        return Excel::download($export, 'Laporan_Keuangan.xlsx');
    }
}
