<?php

namespace App\Exports\Sheets;

use App\Models\Coa;
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
    Fill
};

class CoaExport implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return Coa::all()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->kode_coa,
                $item->kategori_1,
                $item->kategori_2,
                $item->nama_akun,
                $item->pos_saldo,
                $item->pos_laporan,
            ];
        });
    }

    public function title(): string
    {
        return 'COA';
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'], // Row 1: main title
            ['COA'],                                     // Row 2: subtitle
            ['01 JANUARY 2025 S.D 31 DECEMBER 2025'],    // Row 3: date range (optional)
            ['Daftar COA'],                              // Row 4: sub title
            ['No', 'COA', 'Kategori 1', 'Kategori 2', 'Nama Akun', 'Pos Saldo', 'Pos Laporan'], // Row 5: headers
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [ // Row 1: Report title
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
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
                    'startColor' => ['rgb' => 'E9762B'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge title rows across header columns (A:G)
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('A3:G3');
                $sheet->mergeCells('A4:G4');

                // Column widths (A..G)
                $widths = [5, 12, 18, 18, 30, 12, 20];
                foreach (range('A', 'G') as $i => $col) {
                    $sheet->getColumnDimension($col)->setWidth($widths[$i]);
                }

                // Apply border to the data table starting from header row (row 5) to last row
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A5:G{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

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
