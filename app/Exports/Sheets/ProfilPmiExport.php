<?php

namespace App\Exports\Sheets;

use App\Models\ProfilPmi;
use Maatwebsite\Excel\Concerns\{
    WithTitle,
    WithEvents
};
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\{
    Alignment,
    Border,
    Fill
};

class ProfilPmiExport implements WithTitle, WithEvents
{
    public function title(): string
    {
        return '4.1 Profil PMI';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $data  = ProfilPmi::first();

                if (!$data) {
                    $data = (object) [
                        'nama_pmi' => '-',
                        'alamat' => '-',
                        'ketua' => '-',
                        'kepala_markas' => '-',
                        'kepala_uud' => '-',
                        'bendahara_markas' => '-',
                        'bendahara_uud' => '-',
                        'periode_buku_awal' => '-',
                        'periode_buku_akhir' => '-',
                        'tahun_buku' => '-',
                    ];
                }


                /** ================= JUDUL ================= */
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'PROFILE ENTITAS');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                ]);

                /** ============ GARIS MERAH ============ */
                $sheet->mergeCells('A3:H3');
                $sheet->getStyle('A3:H3')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => 'B22222'],
                        ],
                    ],
                ]);

                /** ============ ISI DATA ============ */
                $rows = [
                    7  => ['Nama Entitas', $data->nama_pmi],
                    8  => ['Alamat', $data->alamat],
                    9  => ['Ketua', $data->ketua],
                    10 => ['Kepala Markas', $data->kepala_markas],
                    11 => ['Kepala UUD', $data->kepala_uud],
                    12 => ['Bendahara Markas', $data->bendahara_markas],
                    13 => ['Bendahara UUD', $data->bendahara_uud],
                    15 => ['Periode Buku Awal', $data->periode_buku_awal],
                    16 => ['Periode Buku Akhir', $data->periode_buku_akhir],
                    17 => ['Tahun Buku', $data->tahun_buku],
                ];


                foreach ($rows as $row => [$label, $value]) {

                    $sheet->mergeCells("A{$row}:B{$row}");
                    $sheet->mergeCells("D{$row}:H{$row}");

                    $sheet->setCellValue("A{$row}", $label);
                    $sheet->setCellValue("C{$row}", ':');
                    $sheet->setCellValue("D{$row}", $value);

                    $sheet->getStyle("A{$row}")->applyFromArray([
                        'font' => ['bold' => true],
                    ]);
                }

                /** ============ COLUMN WIDTH ============ */
                $sheet->getColumnDimension('A')->setWidth(22);
                $sheet->getColumnDimension('B')->setWidth(5);
                $sheet->getColumnDimension('C')->setWidth(3);
                $sheet->getColumnDimension('D')->setWidth(45);

                /** ============ ALIGNMENT ============ */
                $sheet->getStyle('A7:A17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C7:C17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
