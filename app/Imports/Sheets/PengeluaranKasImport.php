<?php

namespace App\Imports\Sheets;

use App\Models\PengeluaranKas;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengeluaranKasImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        PengeluaranKas::create([
            'tanggal' => $r['tanggal'] ?? null,
            'nominal' => $r['nominal'] ?? 0,
            'keterangan' => $r['keterangan'] ?? null,
        ]);
    }
}
