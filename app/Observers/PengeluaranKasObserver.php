<?php

namespace App\Observers;

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
            'id_pengeluaran_kas' => $data->id_pengeluaran_kas,
            
            // Akun Beban
            'id_coa'             => $data->id_coa, 
            'debit'              => $data->nominal, // Masuk Debit
            'kredit'             => 0,
            
            // Field pelengkap
            'rupiah'             => $data->nominal,
            'rekening_kas'       => $data->nama_bank_tujuan, // Opsional, sekedar info string
        ]);

        // ENTRY 2: KREDIT (Akun Kas/Bank - Sumber Dana)
        // Pastikan Anda punya ID COA untuk Kas yang digunakan (misal field: id_sumber_dana)
        Gl::create([
            'tanggal'            => $data->tanggal,
            'no_dokumen'         => $data->no_dokumen,
            'keterangan'         => 'Pembayaran: ' . $data->keterangan,
            'id_pengeluaran_kas' => $data->id_pengeluaran_kas,
            
            // Akun Kas (Kredit karena uang keluar)
            'id_coa'             => $data->id_sumber_dana, // Ganti dengan field yg sesuai di tabel Anda (misal: id_kas)
            'debit'              => 0,
            'kredit'             => $data->nominal, // Masuk Kredit
            
            // Field pelengkap
            'rupiah'             => $data->nominal,
            'rekening_kas'       => $data->nama_bank_tujuan,
        ]);
    }
}
