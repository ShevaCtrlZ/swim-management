<?php

namespace App\Exports;

use App\Models\Kompetisi;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HasilKompetisiExport implements FromArray, WithHeadings
{
    protected $kompetisiId;

    public function __construct($id)
    {
        $this->kompetisiId = $id;
    }

    public function array(): array
    {
        $kompetisi = Kompetisi::with('lomba.detailLomba')->findOrFail($this->kompetisiId);
        $rows = [];

        foreach ($kompetisi->lomba as $lomba) {
            foreach ($lomba->detailLomba as $peserta) {
                $rows[] = [
                    $peserta->nama_peserta,
                    $lomba->nomor_lomba,
                    $lomba->jarak,
                    $lomba->jenis_gaya,
                    $peserta->asal_klub,
                    $peserta->catatan_waktu ?? '-',
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Juara', 'Jarak', 'Gaya', 'Nama Peserta', 'Asal Klub', 'Catatan Waktu'];
    }
}
