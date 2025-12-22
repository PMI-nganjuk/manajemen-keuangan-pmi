<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithTitle,
    WithStyles,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class UserExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithEvents
{
    public function collection()
    {
        return User::all()->map(function ($item, $index) {
            return [
                $index + 1,
                $item->kategori ?? '-',
                $item->name,
                $item->phone,
                $item->email,
                $item->alamat ?? '-',
            ];
        });
    }

    public function title(): string
    {
        return '3.3 Nama Anggota';
    }

    public function headings(): array
    {
        return [
            ['PALANG MERAH INDONESIA KABUPATEN NGANJUK'],
            ['NAMA ANGGOTA'],
            [],
            ['No', 'Kategori', 'Nama Rekanan', 'Nomor WA', 'Email', 'Alamat'],
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

                // Merge judul
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');

                // Lebar kolom
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(18);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(18);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(20);

                // Border tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A4:F{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Center kolom No
                $sheet->getStyle("A5:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
