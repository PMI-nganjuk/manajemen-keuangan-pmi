<?php

namespace App\Imports\Sheets;

use App\Models\ProfilPmi;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProfileImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $r = $row->toArray();

        $profil = ProfilPmi::first();

        $data = [
            'nama_pmi' => $r['nama_pmi'] ?? $r['nama_lembaga'] ?? null,
            'alamat' => $r['alamat'] ?? null,
            'ketua' => $r['ketua'] ?? null,
            'kepala_markas' => $r['kepala_markas'] ?? null,
            'kepala_uud' => $r['kepala_uud'] ?? null,
            'bendahara_markas' => $r['bendahara_markas'] ?? null,
            'bendahara_uud' => $r['bendahara_uud'] ?? null,
            'periode_buku_awal' => $r['periode_buku_awal'] ?? null,
            'periode_buku_akhir' => $r['periode_buku_akhir'] ?? null,
            'tahun_buku' => $r['tahun_buku'] ?? null,
        ];

        if ($profil) {
            $profil->update($data);
        } else {
            ProfilPmi::create($data);
        }
    }
}
