<?php

namespace App\Imports\Sheets;

use App\Models\Penyesuaian;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenyesuaianImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        Penyesuaian::create([
            'tanggal' => $r['tanggal'] ?? null,
            'no_dokumen' => $r['no_dokumen'] ?? null,
            'referensi' => $r['referensi'] ?? null,
            'debit' => $r['debit'] ?? 0,
            'kredit' => $r['kredit'] ?? 0,
            'saldo_awal' => $r['saldo_awal'] ?? 0,
            'keterangan' => $r['keterangan'] ?? null,
        ]);
    }
}
