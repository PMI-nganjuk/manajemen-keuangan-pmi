<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;

class ExportController extends Controller
{
    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function laporanKeuangan(\Illuminate\Http\Request $request)
    {
        $tahun = $request->query('tahun', date('Y'));

        $query = \Illuminate\Support\Facades\DB::table('laporan_keuangan')
            ->select('laporan_keuangan.*');

        if (\Illuminate\Support\Facades\Schema::hasTable('penerimaan_kas')) {
            $query->addSelect(\Illuminate\Support\Facades\DB::raw('(
                SELECT COALESCE(SUM(pk.rupiah), 0)
                FROM penerimaan_kas pk
                WHERE pk.id_laporan = laporan_keuangan.id_laporan
            ) as total_pemasukan'));
        } else {
            $query->addSelect(\Illuminate\Support\Facades\DB::raw('0 as total_pemasukan'));
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('pengeluaran_kas')) {
            $query->addSelect(\Illuminate\Support\Facades\DB::raw('(
                SELECT COALESCE(SUM(pg.rupiah), 0)
                FROM pengeluaran_kas pg
                WHERE pg.id_laporan = laporan_keuangan.id_laporan
            ) as total_pengeluaran'));
        } else {
            $query->addSelect(\Illuminate\Support\Facades\DB::raw('0 as total_pengeluaran'));
        }

        if (\Illuminate\Support\Facades\Schema::hasColumn('laporan_keuangan', 'tahun') && $tahun) {
            $query->where('tahun', $tahun);
        }

        $laporan = $query->get()->toArray();

        return Excel::download(
            new LaporanKeuanganExport($tahun, $laporan),
            'laporan-keuangan.xlsx'
        );
    }
}
