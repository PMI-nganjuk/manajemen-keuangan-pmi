<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKeuanganPdfController extends Controller
{
    public function generate($tahun)
    {
        $data = DB::table('laporan_keuangan')
            ->whereYear('tanggal', $tahun)
            ->get();

        $pdf = Pdf::loadView('pdf.laporan-keuangan', [
            'tahun' => $tahun,
            'data' => $data,
        ])->setPaper('A4', 'portrait');

        return $pdf->download("Laporan-Keuangan-$tahun.pdf");
    }
}
