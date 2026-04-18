<?php

namespace App\Imports\Sheets;

use App\Models\PenerimaanKas;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenerimaanKasImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        PenerimaanKas::create([
            'tanggal' => $r['tanggal'] ?? null,
            'no_dokumen' => $r['no_dokumen'] ?? null,
            'referensi' => $r['referensi'] ?? null,
            'rupiah' => $r['rupiah'] ?? 0,
            'keterangan' => $r['keterangan'] ?? null,
        ]);
    }
}
