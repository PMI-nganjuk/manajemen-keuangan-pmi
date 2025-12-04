<?php

namespace App\Imports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        if (!isset($r['email'])) return;

        User::updateOrCreate(
            ['email' => $r['email']],
            [
                'name' => $r['name'] ?? null,
                'role' => $r['role'] ?? null,
            ]
        );
    }
}
