<?php

namespace App\Http\Controllers;

use App\Models\DetailLomba;
use App\Models\Lomba;
use App\Models\Kompetisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\HasilExport;
use App\Exports\HasilJuaraExport;
use App\Exports\BukuAcaraExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportPdfController extends Controller
{
    // helper: milliseconds -> MM:SS:CS (centiseconds 2 digits)
    private function msToDisplay(?int $ms): string {
        // sentinel -1 berarti NS/DQ -> tampilkan literal 99:99:99
        if ($ms === -1) return '99:99:99';
        if ($ms === null) return '00:00:00';
        $ms = (int)$ms;
        $totalSeconds = intdiv($ms, 1000);
        $minutes = intdiv($totalSeconds, 60);
        $seconds = $totalSeconds % 60;
        $centis = intdiv($ms % 1000, 10);
        return sprintf('%02d:%02d:%02d', $minutes, $seconds, $centis);
    }

    public function hasilKompetisi($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);
        $lomba = Lomba::with(['detailLomba.peserta'])->where('kompetisi_id', $kompetisi_id)->get();

        // convert catatan_waktu (ms) to display string and overwrite field so views that use catatan_waktu show formatted value
        foreach ($lomba as $lb) {
            foreach ($lb->detailLomba as $detail) {
                $raw = $detail->catatan_waktu ?? null;
                $detail->catatan_waktu = is_numeric($raw) ? $this->msToDisplay((int)$raw) : ($raw ?? '00:00:00');
            }
        }

        return PDF::loadView('export.hasil_pdf', compact('kompetisi', 'lomba'))
            ->download('hasil_kompetisi.pdf');
    }

    public function acara($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);
        $lomba = Lomba::with(['detailLomba.peserta'])->where('kompetisi_id', $kompetisi_id)->get();

        foreach ($lomba as $lb) {
            foreach ($lb->detailLomba as $detail) {
                $raw = $detail->catatan_waktu ?? null;
                $detail->catatan_waktu = is_numeric($raw) ? $this->msToDisplay((int)$raw) : ($raw ?? '00:00:00');
            }
        }

        $pdf = PDF::loadView('export.buku_acara_pdf', compact('kompetisi', 'lomba'));
        return $pdf->stream('buku_acara.pdf');
    }

    public function exportPesertaKlub($kompetisi_id, $klub = null)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);

        // ambil nama klub dari param atau dari user yang login
        $userClub = null;
        if (Auth::check() && optional(Auth::user())->klub_id) {
            $userClub = DB::table('klub')->where('id', Auth::user()->klub_id)->value('nama_klub');
        }
        $klubParam = $klub ? urldecode($klub) : $userClub;

        $query = DB::table('detail_lomba')
            ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
            ->join('lomba', 'detail_lomba.lomba_id', '=', 'lomba.id')
            ->where('lomba.kompetisi_id', $kompetisi_id);

        if ($klubParam) {
            $query->where('peserta.asal_klub', $klubParam);
        }

        // ambil kolom sesuai struktur database
        $pesertas = $query->select(
            'detail_lomba.no_lintasan',
            'detail_lomba.catatan_waktu',
            'detail_lomba.limit as limit_waktu', // <-- alias aman
            'detail_lomba.seri',
            'peserta.id as peserta_id',
            'peserta.nama_peserta',
            'peserta.tgl_lahir',
            'peserta.jenis_kelamin',
            'peserta.asal_klub',
            'lomba.nomor_lomba',
            'lomba.jarak',
            'lomba.jenis_gaya',
            'lomba.tahun_lahir_minimal',
            'lomba.tahun_lahir_maksimal',
            'lomba.jk as lomba_jk'
        )
        ->orderBy('peserta.nama_peserta') // urut berdasarkan nama peserta
        ->orderBy('lomba.nomor_lomba')
        ->orderBy('detail_lomba.seri')
        ->orderBy('detail_lomba.no_lintasan')
        ->get();

        // format catatan_waktu to display string and overwrite catatan_waktu so views use formatted value
        $pesertas = $pesertas->map(function($row) {
            $raw = $row->catatan_waktu ?? null;
            $row->catatan_waktu = is_numeric($raw) ? $this->msToDisplay((int)$raw) : ($raw ?? '00:00:00');
            return $row;
        });

        $filenameClub = $klubParam ? preg_replace('/[^A-Za-z0-9_-]/', '_', $klubParam) : 'all';
        $filename = "starting_list_{$kompetisi->id}_{$filenameClub}.pdf";

        $pdf = Pdf::loadView('export.starting_list_pdf', [
            'kompetisi' => $kompetisi,
            'pesertas' => $pesertas,
            'klub' => $klubParam,
        ]);

        return $pdf->download($filename);
    }

    public function exportHasilExcel($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);

        // Ambil semua lomba + detail
        $lombaList = Lomba::with('detailLomba.peserta')
                    ->where('kompetisi_id', $kompetisi_id)
                    ->get();

        // Bangun rekap juara per tahun (sama logika seperti view)
        $rekap = [];

        foreach ($lombaList as $lomba) {
            // group per seri
            $grouped = ($lomba->detailLomba ?? collect())->groupBy('seri');
            foreach ($grouped as $seri => $details) {
                // exclude DQ/NS
                $filtered = $details->filter(function($d) {
                    return !in_array(strtoupper($d->keterangan ?? ''), ['DQ','NS']);
                });

                // sort by waktu (smallest first). if no waktu => put last
                $sorted = $filtered->sort(function($a, $b) {
                    $aTime = $a->catatan_waktu ? strtotime("1970-01-01 {$a->catatan_waktu} UTC") : PHP_INT_MAX;
                    $bTime = $b->catatan_waktu ? strtotime("1970-01-01 {$b->catatan_waktu} UTC") : PHP_INT_MAX;
                    return $aTime <=> $bTime;
                })->values();

                // take top 3 as juara
                foreach ($sorted->take(3) as $idx => $detail) {
                    $peserta = $detail->peserta;
                    if (!$peserta) continue;
                    $tahun = $peserta->tgl_lahir ? date('Y', strtotime($peserta->tgl_lahir)) : 'Umum';

                    if (!isset($rekap[$tahun][$peserta->id])) {
                        $rekap[$tahun][$peserta->id] = [
                            'nama' => $peserta->nama_peserta ?? '-',
                            'asal_klub' => $peserta->asal_klub ?? '-',
                            'total_juara1' => 0,
                            'total_juara2' => 0,
                            'total_juara3' => 0,
                            'total_lomba' => 0,
                        ];
                    }

                    if ($idx == 0) $rekap[$tahun][$peserta->id]['total_juara1']++;
                    if ($idx == 1) $rekap[$tahun][$peserta->id]['total_juara2']++;
                    if ($idx == 2) $rekap[$tahun][$peserta->id]['total_juara3']++;

                    $rekap[$tahun][$peserta->id]['total_lomba']++;
                }
            }
        }

        // Sort per year: apply desired ordering (gold-first)
        foreach ($rekap as $tahun => &$pesertas) {
            uasort($pesertas, function($a, $b) {
                if ($a['total_juara1'] != $b['total_juara1']) return $b['total_juara1'] - $a['total_juara1'];
                if ($a['total_juara2'] != $b['total_juara2']) return $b['total_juara2'] - $a['total_juara2'];
                return $b['total_juara3'] - $a['total_juara3'];
            });
        }
        unset($pesertas);

        // Build rows for Excel to match view format
        $rows = [];
        foreach ($rekap as $tahun => $pesertas) {
            // Year header
            $rows[] = ['Kategori Tahun Lahir: ' . $tahun];

            // Column headers
            $rows[] = ['Nama Peserta','Asal Klub','Juara 1','Juara 2','Juara 3','Total Medali','Total Lomba'];

            foreach ($pesertas as $p) {
                $rows[] = [
                    $p['nama'],
                    $p['asal_klub'],
                    $p['total_juara1'],
                    $p['total_juara2'],
                    $p['total_juara3'],
                    ($p['total_juara1'] + $p['total_juara2'] + $p['total_juara3']),
                    $p['total_lomba'],
                ];
            }

            // Blank line between years
            $rows[] = [];
        }

        $filename = 'juara_umum_kompetisi_' . $kompetisi->id . '.xlsx';

        return Excel::download(new HasilJuaraExport($rows), $filename);
    }

    public function exportBukuAcaraExcel($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);
        $lombaList = Lomba::with(['detailLomba.peserta'])
                 ->where('kompetisi_id', $kompetisi_id)
                 ->orderBy('nomor_lomba')
                 ->get();

        $rows = [];

        // Header meta
        $rows[] = ['Buku Acara - ' . ($kompetisi->nama_kompetisi ?? '-')];
        $rows[] = ['Tanggal Mulai', $kompetisi->tgl_mulai ?? '-'];
        $rows[] = ['Tanggal Selesai', $kompetisi->tgl_selesai ?? '-'];
        $rows[] = ['Lokasi', $kompetisi->lokasi ?? '-'];
        $rows[] = []; // blank

        foreach ($lombaList as $item) {
            // Lomba header (gabungkan nanti)
            $lombaTitle = sprintf("%s. %s M GAYA %s %s/%s %s",
                $item->nomor_lomba,
                $item->jarak ?? '-',
                strtoupper($item->jenis_gaya ?? '-'),
                $item->tahun_lahir_minimal ?? '-',
                $item->tahun_lahir_maksimal ?? '-',
                $item->jk ?? ''
            );
            $rows[] = [$lombaTitle];

            // group per seri
            $grouped = ($item->detailLomba ?? collect())->groupBy('seri')->sortKeys();

            foreach ($grouped as $seri => $kelompok) {
                $rows[] = ['Seri ' . $seri];

                // table header
                $rows[] = ['No Lint','Nama Peserta','Lahir','Asal Klub','Limit Waktu','Hasil','Keterangan'];

                // sort by no_lintasan (or catatan_waktu)
                $list = $kelompok->sortBy('no_lintasan')->values();

                $no = 1;
                foreach ($list as $detail) {
                    $peserta = $detail->peserta;
                    $rows[] = [
                        $detail->no_lintasan ?? $no,
                        $peserta->nama_peserta ?? '-',
                        $peserta->tgl_lahir ?? '-',
                        $peserta->asal_klub ?? '-',
                        $detail->limit ?? $detail->limit_waktu ?? '-',
                        $detail->catatan_waktu ?? '-',
                        $detail->keterangan ?? '',
                    ];
                    $no++;
                }

                $rows[] = []; // blank line after each seri
            }

            $rows[] = []; // blank line after each lomba
        }

        $filename = 'buku_acara_kompetisi_' . $kompetisi->id . '.xlsx';
        return Excel::download(new BukuAcaraExport($rows), $filename);
    }
}
