<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function laporanKeuangan()
    {
        return Excel::download(
            new LaporanKeuanganExport,
            'laporan-keuangan.xlsx'
        );
    }
}
