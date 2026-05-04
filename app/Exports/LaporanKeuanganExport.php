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
                $row->periode ?? '-',
                $row->tahun ?? '-',
                ucfirst($row->status ?? '-'),
                $row->kas_tahun1 ? number_format($row->kas_tahun1, 0, ',', '.') : '0',
                $row->kas_tahun2 ? number_format($row->kas_tahun2, 0, ',', '.') : '0',
                $row->saldo_akhir ? number_format($row->saldo_akhir, 0, ',', '.') : '0',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Laporan Keuangan Tahun ' . $this->tahun],
            ['Periode', 'Tahun', 'Status', 'Kas Tahun 1', 'Kas Tahun 2', 'Saldo Akhir']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        return [
            1    => ['font' => ['bold' => true, 'size' => 14]],
            2    => ['font' => ['bold' => true]],
        ];
    }
}
