<?php

namespace App\Exports\Sheets;

use App\Models\Penyesuaian;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenyesuaianExport implements FromCollection
{
    public function collection()
    {
        return Penyesuaian::all();
    }
}
