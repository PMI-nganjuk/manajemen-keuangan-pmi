<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\UserSheet;
use App\Exports\Sheets\ProgramKerjaSheet;
use App\Exports\Sheets\PenerimaanKasSheet;
use App\Exports\Sheets\PengeluaranKasSheet;
use App\Exports\Sheets\PenyesuaianSheet;
use App\Exports\Sheets\ProfilPmiSheet;
use App\Exports\Sheets\CoaSheet;

class LaporanKeuanganExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new UserSheet(),
            new ProgramKerjaSheet(),
            new PenerimaanKasSheet(),
            new PengeluaranKasSheet(),
            new PenyesuaianSheet(),
            new ProfilPmiSheet(),
            new CoaSheet(),
        ];
    }
}
