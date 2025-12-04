<?php

namespace App\Imports\Sheets;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgramKerjaImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        ProgramKerja::updateOrCreate(
            ['kode' => $r['kode'] ?? null],
            [
                'nama_program' => $r['nama_program'] ?? null,
                'anggaran' => $r['anggaran'] ?? 0,
                'keterangan' => $r['keterangan'] ?? null,
            ]
        );
    }
}
