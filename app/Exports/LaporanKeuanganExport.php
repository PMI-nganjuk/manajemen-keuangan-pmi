<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromArray, WithHeadings, WithStyles
{
    protected $tahun;
    protected $laporan;

    public function __construct($tahun, array $laporan)
    {
        $this->tahun = $tahun;
        $this->laporan = $laporan;
    }

    public function array(): array
    {
        $data = [];
        foreach ($this->laporan as $row) {
            $data[] = [
                $row->nama_coa ?? '-',
                $row->saldo_awal ? number_format($row->saldo_awal, 2, ',', '.') : '0,00',
                $row->penerimaan ? number_format($row->penerimaan, 2, ',', '.') : '0,00',
                $row->pengeluaran ? number_format($row->pengeluaran, 2, ',', '.') : '0,00',
                $row->saldo_akhir ? number_format($row->saldo_akhir, 2, ',', '.') : '0,00',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Laporan Keuangan Tahun ' . $this->tahun],
            ['Uraian', 'Saldo Awal', 'Penerimaan', 'Pengeluaran', 'Saldo Akhir']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
