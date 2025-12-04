<?php

namespace App\Exports\Sheets;

use App\Models\ProfilPmi;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProfilPmiExport implements FromCollection
{
    public function collection()
    {
        return ProfilPmi::all();
    }
}
