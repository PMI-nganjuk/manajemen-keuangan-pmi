<?php

namespace App\Exports\Sheets;

use App\Models\PengeluaranKas;
use Maatwebsite\Excel\Concerns\FromCollection;

class PengeluaranKasExport implements FromCollection
{
    public function collection()
    {
        return PengeluaranKas::all();
    }
}
