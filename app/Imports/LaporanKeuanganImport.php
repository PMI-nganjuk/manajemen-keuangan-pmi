<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\Sheets\UserImport;
use App\Imports\Sheets\ProgramKerjaImport;
use App\Imports\Sheets\PenerimaanKasImport;
use App\Imports\Sheets\PengeluaranKasImport;
use App\Imports\Sheets\CoaImport;
use App\Imports\Sheets\PenyesuaianImport;
use App\Imports\Sheets\ProfileImport;

class LaporanKeuanganImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Users'            => new UserImport(),
            'Program Kerja'    => new ProgramKerjaImport(),
            'Penerimaan Kas'   => new PenerimaanKasImport(),
            'Pengeluaran Kas'  => new PengeluaranKasImport(),
            'COA'              => new CoaImport(),
            'Penyesuaian'      => new PenyesuaianImport(),
            'Profile'          => new ProfileImport(),
        ];
    }
}
