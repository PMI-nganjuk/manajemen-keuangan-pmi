<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKeuanganExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
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
                number_format((float) ($row->kas_tahun1 ?? 0), 0, ',', '.'),
                number_format((float) ($row->kas_tahun2 ?? 0), 0, ',', '.'),
                number_format((float) ($row->total_pemasukan ?? 0), 0, ',', '.'),
                number_format((float) ($row->total_pengeluaran ?? 0), 0, ',', '.'),
                number_format((float) ($row->saldo_akhir ?? 0), 0, ',', '.'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],
            ['Laporan Keuangan'],
            ['Periode 01 Januari ' . $this->tahun . ' sampai dengan 31 Desember ' . $this->tahun],
            [''],
            ['Periode', 'Tahun', 'Status', 'Kas Tahun 1', 'Kas Tahun 2', 'Pemasukan', 'Pengeluaran', 'Saldo Akhir']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        return [
            'A1:H3' => [
                'font' => [
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF3B62A4'],
                ],
            ],
            // Styling khusus baris 1 agar lebih tebal
            'A1:H1' => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
            ],
            // Styling kotak tabel kolom (baris 5)
            'A5:H5' => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF3B62A4'],
                ],
            ],
        ];
    }
}