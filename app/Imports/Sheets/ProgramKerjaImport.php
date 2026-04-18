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

        ProgramKerja::create([
            'nama_program' => $r['nama_program'] ?? null,
            'keterangan' => $r['keterangan'] ?? null,
            'id_pegawai' => $r['id_pegawai'] ?? null,
        ]);
    }
}
