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

    // helper: milliseconds -> MM:SS:MS
    private function msToDisplay(?int $ms): string {
        if ($ms === -1) return '99:99:99';
        if ($ms === null) return '00:00:000';
        $ms = (int)$ms;
        $totalSeconds = intdiv($ms, 1000);
        $minutes = intdiv($totalSeconds, 60);
        $seconds = $totalSeconds % 60;
        $millis = $ms % 1000;
        return sprintf('%02d:%02d:%03d', $minutes, $seconds, $millis);
    }

    public function array(): array
    {
        $kompetisi = Kompetisi::with('lomba.detailLomba')->findOrFail($this->kompetisiId);
        $rows = [];

        foreach ($kompetisi->lomba as $lomba) {
            foreach ($lomba->detailLomba as $peserta) {
                // convert stored milliseconds (integer) to display format
                $display = is_numeric($peserta->catatan_waktu) ? $this->msToDisplay((int)$peserta->catatan_waktu) : ($peserta->catatan_waktu ?? '-');
                $rows[] = [
                    $peserta->nama_peserta,
                    $lomba->nomor_lomba,
                    $lomba->jarak,
                    $lomba->jenis_gaya,
                    $peserta->asal_klub,
                    $display,
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
