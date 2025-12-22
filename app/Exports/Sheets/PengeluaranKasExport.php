<?php

namespace App\Exports\Sheets;

use App\Models\PengeluaranKas;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithTitle,
    WithStyles,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Alignment,
    Border,
    Fill,
    NumberFormat
};

class PengeluaranKasExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents
{
    public function collection()
    {
        return PengeluaranKas::all()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->tanggal,
                $item->program_kerja,
                $item->no_document,
                $item->dibayarkan_kepada,
                $item->referensi,
                $item->rekening_kas,
                $item->kode_transaksi,
                $item->rupiah,
                $item->keterangan,
            ];
        });
    }

    public function title(): string
    {
        return '2.4 Pengeluaran Kas';
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],
            ['PENGELUARAN KAS'],
            ['01 JANUARY 2025 S.D 31 DECEMBER 2025'],
            ['Transaksi Pengeluaran Kas'],
            ['No', 'Tanggal', 'Program Kerja', 'No Dokumen', 'Dibayarkan Kepada', 'Referensi', 'Rekening Kas', 'Kode Transaksi', 'Rupiah', 'Keterangan'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E9762B'],
                ],
            ],
            5 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EBF4DD'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Merge judul
                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');
                $sheet->mergeCells('A4:J4');

                // Alignment judul
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->getStyle("A{$row}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Lebar kolom
                $widths = [5, 15, 30, 15, 25, 15, 18, 22, 15, 35];
                foreach (range('A', 'J') as $i => $col) {
                    $sheet->getColumnDimension($col)->setWidth($widths[$i]);
                }

                // Border tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A5:J{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Format Rupiah
                $sheet->getStyle("I6:I{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                // Center kolom No
                $sheet->getStyle("A6:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
