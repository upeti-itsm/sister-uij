<?php

namespace App\Exports\Organisasi;

use App\Models\Organisasi\Golongan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TemplateGolonganExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithColumnFormatting
{
    private $totalRows = 0;

    public function array(): array
    {
        $golonganList = Golongan::get_golongan(0, 10000, '', null);
        $data = [];

        foreach ($golonganList as $item) {
            $gaji = (isset($item->gaji_pokok) && $item->gaji_pokok !== '' && $item->gaji_pokok !== null) ? (int)$item->gaji_pokok : 0;
            $masaVal = (isset($item->masa_kerja) && $item->masa_kerja !== '' && $item->masa_kerja !== null) ? (int)$item->masa_kerja : 0;

            $data[] = [
                (string)($item->golongan ?? ''),
                (string)$masaVal,
                $gaji
            ];
        }

        $this->totalRows = count($data);

        // Fallback if database has no data
        if ($this->totalRows === 0) {
            $data = [
                ['III/A', '0', 3000000],
                ['III/A', '4', 3500000],
            ];
            $this->totalRows = count($data);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Golongan',
            'Masa Kerja (Tahun)',
            'Gaji Pokok (Rp)'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 25,
            'C' => 25,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '@',
            'C' => '#,##0',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->totalRows + 1;

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(30);
        for ($i = 2; $i <= $lastRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(22);
        }

        // Header Styling (Row 1) - Theme Dark Green UIJ
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Calibri'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '0C6E3D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['argb' => '084828'],
                ],
            ],
        ]);

        // Alignment for data cells
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Vertical alignment for all data cells
        $sheet->getStyle('A2:C' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Grid borders for data
        $sheet->getStyle('A1:C' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'CCCCCC'],
                ],
            ],
        ]);

        return [];
    }
}
