<?php
// filepath: app/Exports/HasilJuaraExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class HasilJuaraExport implements FromArray, ShouldAutoSize, WithEvents
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function registerEvents(): array
    {
        // apply styles after sheet created
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $cellA = $sheet->getCell('A' . $row)->getValue();

                    // If this row is a year header (starts with "Kategori Tahun Lahir")
                    if (is_string($cellA) && str_starts_with(trim($cellA), 'Kategori Tahun Lahir')) {
                        $sheet->getStyle("A{$row}:" . $sheet->getHighestColumn() . "{$row}")
                              ->getFont()->setBold(true);
                        // merge across columns for this row (optional, safe up to column G)
                        $sheet->mergeCells("A{$row}:G{$row}");
                    }

                    // If this row is the table header row (Nama Peserta)
                    if (is_string($cellA) && trim($cellA) === 'Nama Peserta') {
                        $sheet->getStyle("A{$row}:" . $sheet->getHighestColumn() . "{$row}")
                              ->getFont()->setBold(true);
                        $sheet->getStyle("A{$row}:" . $sheet->getHighestColumn() . "{$row}")
                              ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    }
                }

                // small overall formatting: set wrap and vertical center
                $sheet->getStyle("A1:" . $sheet->getHighestColumn() . $highestRow)
                      ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            },
        ];
    }
}