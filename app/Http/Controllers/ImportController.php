<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LaporanKeuanganImport;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new LaporanKeuanganImport, $request->file('file'));

            return response()->json([
                'message' => 'Import berhasil'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Excel Import Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal melakukan import data. Periksa format Excel anda.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
