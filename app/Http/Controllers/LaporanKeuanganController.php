<?php

namespace App\Http\Controllers;

use App\Models\LaporanKeuangan;
use App\Models\Coa;
use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    // Menampilkan laporan neraca
    public function index()
    {
        $coa = Coa::query()
            ->select('id_coa', 'kategori_1', 'kategori_2', 'nama_akun', 'pos_saldo')
            ->orderBy('kategori_1')
            ->orderBy('kategori_2')
            ->get();

        $categories = [
            'aset lancar' => 'Aset Lancar',
            'aset tidak lancar' => 'Aset Tidak Lancar',
            'liabilitas lancar' => 'Liabilitas Lancar',
            'liabilitas tidak lancar' => 'Liabilitas Tidak Lancar',
            'aset netto' => 'Aset Netto',
        ];

        $grouped = [
            'Aset Lancar' => [],
            'Aset Tidak Lancar' => [],
            'Liabilitas Lancar' => [],
            'Liabilitas Tidak Lancar' => [],
            'Aset Netto' => [],
        ];

        foreach ($coa as $row) {

            $main = strtolower($row->kategori_1 ?? '');

            foreach ($categories as $key => $label) {

                if (str_contains($main, $key)) {

                    $grouped[$label][] = [
                        'coa' => $row->id_coa,
                        'kategori_2' => $row->kategori_2,
                        'nama_akun' => $row->nama_akun,
                        'pos_saldo' => $row->pos_saldo,
                    ];
                }

            }
        }

        return response()->json([
            'laporan_neraca' => $grouped
        ]);
    }

    // Form create
    public function create()
    {
        return response()->json([
            'message' => 'Form create laporan keuangan'
        ]);
    }

    // Simpan data
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kas_tahun1' => 'required|integer|min:0',
            'kas_tahun2' => 'required|integer|min:0',
        ]);

        $validated['saldo_akhir'] =
            $validated['kas_tahun2'] - $validated['kas_tahun1'];

        $data = LaporanKeuangan::create($validated);

        return response()->json([
            'message' => 'Laporan keuangan berhasil ditambahkan',
            'data' => $data
        ]);
    }

    // Form edit
    public function edit(LaporanKeuangan $laporanKeuangan)
    {
        return response()->json([
            'message' => 'Form edit laporan keuangan',
            'data' => $laporanKeuangan
        ]);
    }

    // Update data
    public function update(Request $request, LaporanKeuangan $laporanKeuangan)
    {
        $validated = $request->validate([
            'kas_tahun1' => 'nullable|integer|min:0',
            'kas_tahun2' => 'nullable|integer|min:0',
        ]);

        $kas1 = $validated['kas_tahun1'] ?? $laporanKeuangan->kas_tahun1;
        $kas2 = $validated['kas_tahun2'] ?? $laporanKeuangan->kas_tahun2;

        $laporanKeuangan->update([
            'kas_tahun1' => $kas1,
            'kas_tahun2' => $kas2,
            'saldo_akhir' => $kas2 - $kas1
        ]);

        return response()->json([
            'message' => 'Laporan keuangan berhasil diperbarui',
            'data' => $laporanKeuangan
        ]);
    }
}
