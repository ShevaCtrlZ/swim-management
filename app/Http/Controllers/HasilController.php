<?php

namespace App\Http\Controllers;

use App\Models\peserta;
use App\Models\DetailLomba;
use App\Models\Kompetisi;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public function simpan(Request $request)
    {
        $request->validate([
            'id_peserta' => 'required|exists:peserta_lomba,id',
            'catatan_waktu' => 'required|string|max:20', // format: mm:ss atau detik total
        ]);

        // Simpan ke tabel catatan_waktu
        DB::table('catatan_waktu')->updateOrInsert(
            ['peserta_lomba_id' => $request->id_peserta],
            ['catatan_waktu' => $request->hasil, 'created_at' => now()]
        );

        return back()->with('success', 'Hasil berhasil disimpan.');
    }

    public function index()
    {
        $data = Kompetisi::all();
        return view('admin_hasil', compact('data'));
    }

    public function tampil()
    {
        $data = Kompetisi::all();
        return view('hasil', compact('data'));
    }

    public function show($id)
{
    $kompetisi = DB::table('kompetisi')->where('id', $id)->first();

    $lomba = DB::table('lomba')
        ->where('kompetisi_id', $id)
        ->get()
        ->map(function ($item) {
            $peserta = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $item->id)
                ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                ->select(
                    'detail_lomba.id',
                    'detail_lomba.no_lintasan',
                    'detail_lomba.urutan',
                    'detail_lomba.catatan_waktu',
                    'peserta.nama_peserta',
                    'peserta.tgl_lahir',
                    'peserta.jenis_kelamin',
                    'peserta.asal_klub',
                    'detail_lomba.limit',
                )
                ->orderByRaw('ISNULL(detail_lomba.catatan_waktu), detail_lomba.catatan_waktu')
                ->get();

            $item->nomorLomba = collect([$peserta]); // Inject property supaya Blade tidak error
            return $item;
        });

    return view('hasil_juara', compact('kompetisi', 'lomba'));
}

public function lihat($id)
{
    $kompetisi = DB::table('kompetisi')->where('id', $id)->first();

    $lomba = DB::table('lomba')
        ->where('kompetisi_id', $id)
        ->get()
        ->map(function ($item) {
            $peserta = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $item->id)
                ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                ->select(
                    'detail_lomba.id',
                    'detail_lomba.no_lintasan',
                    'detail_lomba.urutan',
                    'detail_lomba.catatan_waktu',
                    'peserta.nama_peserta',
                    'peserta.tgl_lahir',
                    'peserta.jenis_kelamin',
                    'peserta.asal_klub',
                    'detail_lomba.limit',
                )
                ->orderByRaw('ISNULL(detail_lomba.catatan_waktu), detail_lomba.catatan_waktu')
                ->get();

            $item->nomorLomba = collect([$peserta]); // Inject property supaya Blade tidak error
            return $item;
        });

    return view('lihat_hasil', compact('kompetisi', 'lomba'));
}

}
