<?php

namespace App\Exports\Sheets;

use App\Models\PenerimaanKas;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithTitle,
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

class PenerimaanKasExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithEvents
{
    public function title(): string
    {
        return '2.3 Penerimaan Kas';
    }

    public function collection()
    {
        return PenerimaanKas::all()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->tanggal,
                $item->program_kerja,
                $item->no_document,
                $item->terima_dari,
                $item->referensi,
                $item->rekening_kas,
                $item->kode_transaksi,
                $item->rupiah,
                $item->keterangan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],                      // Row 1
            ['PENERIMAAN KAS'],                                               // Row 2
            ['01 JANUARY 2025 S.D 31 DECEMBER 2025'],                          // Row 3 (date range)
            ['Transaksi Penerimaan Kas'],                                      // Row 4 (sub title)
            ['No', 'Tanggal', 'Program Kerja', 'No Document', 'Terima Dari', 'Referensi', 'Rekening Kas', 'Kode Transaksi', 'Rupiah', 'Keterangan'], // Row 5: headers
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '5A7ACD'],
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

                // Merge title rows across header columns (A:J)
                $sheet->mergeCells('A1:J1');
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');
                $sheet->mergeCells('A4:J4');

                // Alignment for title rows (left for rows 1-4 as in PengeluaranKasExport)
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->getStyle("A{$row}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Column widths (A..J)
                $widths = [5, 15, 30, 15, 25, 15, 18, 22, 15, 35];
                foreach (range('A', 'J') as $i => $col) {
                    $sheet->getColumnDimension($col)->setWidth($widths[$i]);
                }

                // Apply border to the data table starting from header row (row 5) to last row
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A5:J{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Format Rupiah column (I) with comma separated number format
                if ($lastRow >= 6) {
                    $sheet->getStyle("I6:I{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                }

                // Center the "No" column (A) for data rows
                if ($lastRow >= 6) {
                    $sheet->getStyle("A6:A{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
