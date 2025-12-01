<?php

namespace App\Exports\Sheets;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProgramKerjaSheet implements FromCollection
{
    public function collection()
    {
        return ProgramKerja::all();
    }
}
