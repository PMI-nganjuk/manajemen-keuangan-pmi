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
        
        $query = \Illuminate\Support\Facades\DB::table('laporan_keuangan');
        
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
