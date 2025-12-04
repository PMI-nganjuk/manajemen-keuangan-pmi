<?php

namespace App\Imports\Sheets;

use App\Models\Profile;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProfileImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Profile::updateOrCreate(
            ['nama_lembaga' => $r['nama_lembaga'] ?? null],
            [
                'alamat' => $r['alamat'] ?? null,
                'email' => $r['email'] ?? null,
                'telepon' => $r['telepon'] ?? null,
                'ketua' => $r['ketua'] ?? null,
            ]
        );
    }
}
