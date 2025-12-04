<?php

namespace App\Exports\Sheets;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\FromCollection;

class CoaExport implements FromCollection
{
    public function collection()
    {
        return Coa::all();
    }
}
