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
            ['id_coa' => $r['id_coa'] ?? $r['coa'] ?? null],
            [
                'kategori_1' => $r['kategori_1'] ?? null,
                'kategori_2' => $r['kategori_2'] ?? null,
                'nama_akun' => $r['nama_akun'] ?? null,
                'pos_saldo' => $r['pos_saldo'] ?? null,
                'pos_laporan' => $r['pos_laporan'] ?? null,
            ]
        );
    }
}
