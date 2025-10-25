<?php

namespace App\Http\Controllers;

use App\Models\DetailLomba;
use App\Models\Lomba;
use App\Models\Kompetisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExportPdfController extends Controller
{
    public function hasilKompetisi($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);
        $lomba = Lomba::with(['detailLomba.peserta'])->where('kompetisi_id', $kompetisi_id)->get();

        return PDF::loadView('export.hasil_pdf', compact('kompetisi', 'lomba'))
            ->download('hasil_kompetisi.pdf');
    }

    public function acara($kompetisi_id)
    {
        $kompetisi = Kompetisi::findOrFail($kompetisi_id);
        $lomba = Lomba::with(['detailLomba.peserta'])->where('kompetisi_id', $kompetisi_id)->get();

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

        $filenameClub = $klubParam ? preg_replace('/[^A-Za-z0-9_-]/', '_', $klubParam) : 'all';
        $filename = "starting_list_{$kompetisi->id}_{$filenameClub}.pdf";

        $pdf = Pdf::loadView('export.starting_list_pdf', [
            'kompetisi' => $kompetisi,
            'pesertas' => $pesertas,
            'klub' => $klubParam,
        ]);

        return $pdf->download($filename);
    }
}
