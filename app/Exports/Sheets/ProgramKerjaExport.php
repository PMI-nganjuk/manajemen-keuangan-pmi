<?php

namespace App\Exports\Sheets;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProgramKerjaExport implements FromCollection
{
    public function collection()
    {
        return ProgramKerja::all();
    }
}
