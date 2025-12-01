<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserSheet implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}
