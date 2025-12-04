<?php

namespace App\Exports\Sheets;

use App\Models\PenerimaanKas;
use Maatwebsite\Excel\Concerns\FromCollection;

class PenerimaanKasExport implements FromCollection
{
    public function collection()
    {
        return PenerimaanKas::all();
    }
}
