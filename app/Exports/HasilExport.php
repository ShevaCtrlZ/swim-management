<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HasilExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return new Collection($this->rows);
    }

    public function headings(): array
    {
        return [
            'Lomba',
            'Seri',
            'Juara',
            'Nama Peserta',
            'Tanggal Lahir',
            'Asal Klub',
            'Limit Waktu',
            'Hasil'
        ];
    }
}
