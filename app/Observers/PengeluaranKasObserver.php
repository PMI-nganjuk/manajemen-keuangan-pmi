<?php

namespace App\Observers;

use App\Models\Gl;
use App\Models\PengeluaranKas;

class PengeluaranKasObserver
{
    /**
     * Handle the PengeluaranKas "created" event.
     */
    public function created(PengeluaranKas $pengeluaranKas): void
    {
        $this->createEntries($pengeluaranKas);
    }

    /**
     * Handle the PengeluaranKas "updated" event.
     */
    public function updated(PengeluaranKas $pengeluaranKas): void
    {
        //
    }

    /**
     * Handle the PengeluaranKas "deleted" event.
     */
    public function deleted(PengeluaranKas $pengeluaranKas): void
    {
        //
    }

    /**
     * Handle the PengeluaranKas "restored" event.
     */
    public function restored(PengeluaranKas $pengeluaranKas): void
    {
        //
    }

    /**
     * Handle the PengeluaranKas "force deleted" event.
     */
    public function forceDeleted(PengeluaranKas $pengeluaranKas): void
    {
        //
    }

    /**
     * Helper function untuk membuat Double Entry
     */
    private function createEntries(PengeluaranKas $data)
    {
        // ENTRY 1: DEBIT (Akun Beban/Lawan)
        Gl::create([
            'tanggal'            => $data->tanggal,
            'no_dokumen'         => $data->no_dokumen,
            'keterangan'         => $data->keterangan,
            'id_pengeluaran_kas' => $data->id_pengeluaran,
            'id_coa'             => $data->id_coa,
            'id_program_kerja'   => $data->id_program_kerja,
            'id_laporan'         => $data->id_laporan,
            'debit'              => $data->rupiah,
            'kredit'             => 0,
            'rupiah'             => $data->rupiah,
        ]);

        // ENTRY 2: KREDIT (Akun Kas/Bank - Sumber Dana)
        // TODO: Idealnya entry kredit menggunakan COA kas/bank yang berbeda
        //       dari COA beban di atas. Saat ini tabel pengeluaran_kas hanya
        //       menyimpan 1 id_coa. Jika perlu double-entry yang benar,
        //       tambahkan kolom 'id_coa_kas' di tabel pengeluaran_kas.
        Gl::create([
            'tanggal'            => $data->tanggal,
            'no_dokumen'         => $data->no_dokumen,
            'keterangan'         => 'Pembayaran: ' . $data->keterangan,
            'id_pengeluaran_kas' => $data->id_pengeluaran,
            'id_coa'             => $data->id_coa,
            'id_program_kerja'   => $data->id_program_kerja,
            'id_laporan'         => $data->id_laporan,
            'debit'              => 0,
            'kredit'             => $data->rupiah,
            'rupiah'             => $data->rupiah,
        ]);
    }
}
