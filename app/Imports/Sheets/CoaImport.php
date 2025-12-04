<?php

namespace App\Imports\Sheets;

use App\Models\Coa;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoaImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Coa::updateOrCreate(
            ['kode_akun' => $r['kode_akun'] ?? null],
            [
                'nama_akun' => $r['nama_akun'] ?? null,
                'kategori' => $r['kategori'] ?? null,
            ]
        );
    }
}
