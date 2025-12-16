<?php
// filepath: app/Exports/BukuAcaraExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BukuAcaraExport implements FromArray, ShouldAutoSize, WithEvents
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
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestCol  = $sheet->getHighestColumn();

                // default font + wrap
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")
                      ->getAlignment()->setWrapText(true);
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")
                      ->getFont()->setName('DejaVu Sans')->setSize(10);

                // 1) Detect and style main title (row 1 assumed)
                $title = trim((string) $sheet->getCell('A1')->getValue());
                if ($title !== '') {
                    $sheet->mergeCells("A1:{$highestCol}1");
                    $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(12);
                    $sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // 2) Find header rows and year/lomba/seri rows
                $tableHeaderRows = []; // store row numbers where table header found
                $mergeRows = []; // rows to merge (lomba/seri/title)

                for ($r = 1; $r <= $highestRow; $r++) {
                    $cellA = trim((string) $sheet->getCell('A' . $r)->getValue());
                    if ($cellA === '') continue;

                    // Seri header e.g. "Seri 1"
                    if (preg_match('/^Seri\s+\d+/i', $cellA)) {
                        $mergeRows[] = $r;
                        $sheet->mergeCells("A{$r}:{$highestCol}{$r}");
                        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    }

                    // Lomba header (starts with "1. " or contains "M GAYA")
                    if (preg_match('/^\d+\.\s+.*M\s+GAYA/i', $cellA) || preg_match('/M\s+GAYA/i', $cellA) && preg_match('/\d{4}\s*\/\s*\d{4}/', $cellA)) {
                        $mergeRows[] = $r;
                        $sheet->mergeCells("A{$r}:{$highestCol}{$r}");
                        $sheet->getStyle("A{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    }

                    // Table header detection (Nama Peserta etc.)
                    if (in_array(strtolower($cellA), ['nama peserta','no lint','no lintasan','no','nama'])) {
                        $tableHeaderRows[] = $r;
                        // style header row: bold, center, fill
                        $sheet->getStyle("A{$r}:{$highestCol}{$r}")->getFont()->setBold(true);
                        $sheet->getStyle("A{$r}:{$highestCol}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle("A{$r}:{$highestCol}{$r}")->getFill()->setFillType(Fill::FILL_SOLID)
                              ->getStartColor()->setARGB('FFEFEFEF');
                        // set thin border around header
                        $sheet->getStyle("A{$r}:{$highestCol}{$r}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                    }
                }

                // 3) Freeze pane right after first detected table header (keeps header visible)
                if (!empty($tableHeaderRows)) {
                    $firstHeader = $tableHeaderRows[0];
                    $freezeRow = $firstHeader + 1;
                    $sheet->freezePane("A{$freezeRow}");
                }

                // 4) Apply borders to all data area and set vertical align middle
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")
                      ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle("A1:{$highestCol}{$highestRow}")
                      ->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // 5) Set sensible column widths for common columns (override autosize if needed)
                try {
                    $colWidths = [
                        'A' => 8,   // No / No Lint
                        'B' => 28,  // Nama Peserta
                        'C' => 12,  // Lahir
                        'D' => 18,  // Asal Klub
                        'E' => 12,  // Limit Waktu
                        'F' => 12,  // Hasil
                        'G' => 12,  // Keterangan
                    ];
                    foreach ($colWidths as $col => $width) {
                        $sheet->getColumnDimension($col)->setWidth($width);
                    }
                } catch (\Throwable $e) {
                    // ignore if column dimension not applicable
                }

                // 6) Trim trailing blank row(s)
                // remove extremely long empties at bottom by trimming style area (no deletion of data)
                // (no action needed here beyond formatting)

                // 7) Auto-filter on first table header if exists
                if (!empty($tableHeaderRows)) {
                    $firstHeader = $tableHeaderRows[0];
                    $lastCol = $highestCol;
                    $sheet->setAutoFilter("A{$firstHeader}:{$lastCol}{$firstHeader}");
                }
            },
        ];
    }
}