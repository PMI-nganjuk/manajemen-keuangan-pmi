<?php

namespace App\Exports\Sheets;

use App\Models\Coa;
use Maatwebsite\Excel\Concerns\FromCollection;

class CoaSheet implements FromCollection
{
    public function collection()
    {
        return Coa::all();
    }
}
