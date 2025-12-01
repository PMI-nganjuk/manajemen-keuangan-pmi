<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function export()
    {
        return Excel::download(new LaporanKeuanganExport, 'laporan-keuangan.xlsx');
    }
}
