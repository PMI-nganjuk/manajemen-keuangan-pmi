<?php

namespace App\Exports\Sheets;

use App\Models\Penyesuaian;
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

class PenyesuaianExport implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Penyesuaian::all()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->tanggal,
                $item->no_document,
                $item->program_kerja,
                $item->referensi,
                $item->coa_transaksi,
                $item->debit,
                $item->kredit,
                $item->keterangan,
                $item->saldo_awal,
            ];
        });
    }

    public function title(): string
    {
        return '2.5 Penyesuaian';
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],                      // Row 1
            ['PENYESUAIAN'],                                                   // Row 2
            ['01 JANUARY 2025 S.D 31 DECEMBER 2025'],                          // Row 3 (date range)
            ['Daftar Penyesuaian'],                                            // Row 4 (sub title)
            ['No', 'Tanggal', 'No Document', 'Program Kerja', 'Referensi', 'COA Transaksi', 'Debit', 'Kredit', 'Keterangan', 'Saldo Awal'], // Row 5: headers
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
                    'startColor' => ['rgb' => '90AB8B'],
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

                // Alignment for title rows
                foreach ([1, 2, 3, 4] as $row) {
                    $sheet->getStyle("A{$row}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
                }

                // Column widths (A..J)
                $widths = [5, 15, 20, 30, 20, 25, 15, 15, 30, 18];
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

                // Format Debit and Kredit columns (G,H) with comma separated number format
                if ($lastRow >= 6) {
                    $sheet->getStyle("G6:G{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $sheet->getStyle("H6:H{$lastRow}")
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
