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

        Excel::import(new LaporanKeuanganImport, $request->file('file'));

        return response()->json([
            'message' => 'Import berhasil'
        ]);
    }
}
