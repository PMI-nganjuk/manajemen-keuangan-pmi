<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKeuanganExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    // export laporan keuangan
    public function export()
    {
        return Excel::download(new LaporanKeuanganExport, 'laporan-keuangan.xlsx');
    }

    // import laporan keuangan
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new MainImport, $request->file('file'));

        return back()->with('success', 'Berhasil import data!');
    }

}


