<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\UserExport;
use App\Exports\Sheets\ProgramKerjaExport;
use App\Exports\Sheets\PenerimaanKasSheet;
use App\Exports\Sheets\PengeluaranKasExport;
use App\Exports\Sheets\PenyesuaianExport;
use App\Exports\Sheets\ProfilPmiExport;
use App\Exports\Sheets\CoaExport;

class LaporanKeuanganExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new UserExport(),
            new ProgramKerjaExport(),
            new PenerimaanKasSheet(),
            new PengeluaranKasExport(),
            new PenyesuaianExport(),
            new ProfilPmiExport(),
            new CoaExport(),
        ];
    }
}
