<?php

namespace App\Exports\Sheets;

use App\Models\ProfilPmi;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProfilPmiSheet implements FromCollection
{
    public function collection()
    {
        return ProfilPmi::all();
    }
}
