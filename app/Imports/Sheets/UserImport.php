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
                'nama' => $r['nama'] ?? null,
                'nomer_wa' => $r['nomer_wa'] ?? null,
                'alamat' => $r['alamat'] ?? null,
                'kategori' => $r['kategori'] ?? null,
                'role' => $r['role'] ?? 'pegawai',
                'password' => $r['password'] ?? 'password',
            ]
        );
    }
}
