<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LaporanKeuanganPdfController extends Controller
{
    public function generate($tahun)
    {
        $data = DB::table('laporan_keuangan')
            ->where('tahun', $tahun)
            ->get();

        // Jika barryvdh/laravel-dompdf belum terinstall, gunakan fallback
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan-keuangan', [
                'tahun' => $tahun,
                'data' => $data,
            ])->setPaper('A4', 'portrait');

            return $pdf->download("Laporan-Keuangan-$tahun.pdf");
        }

        // Fallback: return JSON jika dompdf belum diinstall
        return response()->json([
            'message' => 'Package barryvdh/laravel-dompdf belum terinstall. Jalankan: composer require barryvdh/laravel-dompdf',
            'data' => $data,
        ], 501);
    }
}
