<?php

namespace App\Exports\Sheets;

use App\Models\ProgramKerja;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithTitle,
    WithHeadings,
    WithStyles,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Alignment,
    Border,
    Fill
};

class ProgramKerjaExport implements FromCollection, WithTitle, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return ProgramKerja::with('pegawai')->get()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->nama_program,
                $item->pegawai->nama ?? '-',
                $item->keterangan,
            ];
        });

    }

    public function title(): string
    {
        return '3.4 Program Kerja';
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],
            ['PROGRAM KERJA'],
            [],
            ['No', 'Nama Program', 'PIC', 'Keterangan'],
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
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            4 => [
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

                // Merge title rows across all header columns (A:D)
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');

                // Column widths
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(30);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(40);

                // Border for table starting at header row (row 4)
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A4:D{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Center the "No" column (A) for data rows (row 5 onward)
                if ($lastRow >= 5) {
                    $sheet->getStyle("A5:A{$lastRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            },
        ];
    }
}
