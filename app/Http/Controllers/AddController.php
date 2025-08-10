<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\peserta;
use App\Models\Lomba;
use App\Models\Detaillomba;

class AddController extends Controller
{
    public function showAddView(): View
    {
        return view('add');
    }

    public function storeData(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:peserta,id',
            'lomba_id' => 'required|array|min:1',
            'limit_per_lomba' => 'required|array',
        ]);

        $peserta_id = $request->peserta_id;
        $lombaDipilih = $request->lomba_id;
        $limits = $request->input('limit_per_lomba', []);

        // Aturan harga
        $hargaBundling = 120000;
        $syaratBundling = 4;

        // Hitung total harga reguler
        $totalReguler = Lomba::whereIn('id', $lombaDipilih)->sum('harga');

        // Tentukan harga yang dipakai
        $totalHarga = (count($lombaDipilih) >= $syaratBundling) ? $hargaBundling : $totalReguler;

        // Simpan ke detail_lomba
        foreach ($lombaDipilih as $lomba_id) {
            DetailLomba::create([
                'lomba_id'    => $lomba_id,
                'peserta_id'  => $peserta_id,
                'no_lintasan' => null, // nanti diatur saat penyusunan nomor lintasan
                'urutan'      => null,
                'catatan_waktu' => null,
                'keterangan'  => null,
                'limit'       => $limits[$lomba_id] ?? '99:99:99', // default limit
            ]);
        }

        return redirect()->back()->with('success', 'Pendaftaran berhasil. Total harga: Rp' . number_format($totalHarga, 0, ',', '.'));
    }

    public function list()
    {
        $data = peserta::paginate(10);
        return view('list', compact('data'));
    }

    public function atlet()
    {
        $data = peserta::all();
        return view('atlet', compact('data'));
    }

    public function create()
    {
        $kompetisi = Kompetisi::with('lomba')->get();
        // Ambil klub yang sedang login
        $user = Auth::user();

        // Ambil semua peserta yang dimiliki klub ini
        $peserta = Peserta::where('klub_id', $user->klub_id)->get();

        $lomba = $kompetisi->flatMap(function ($k) {
            return $k->lomba->map(function ($l) use ($k) {
                return [
                    'id' => $l->id,
                    'kompetisi_id' => $k->id,
                    'jenis_gaya' => $l->jenis_gaya,
                    'jarak' => $l->jarak,
                    'min' => (int) $l->tahun_lahir_minimal,
                    'max' => (int) $l->tahun_lahir_maksimal,
                    'jk' => strtoupper(substr($l->jk, 0, 1)), // convert 'Laki-laki' jadi 'L', 'Perempuan' jadi 'P'
                    'harga' => $l->harga ?? 0
                ];
            });
        })->values();

        $lombaSudahDipilih = [];

        // Aturan bundling
        $hargaBundling = 120000; // contoh harga bundling
        $syaratBundling = 4; // minimal ikut 3 lomba

        return view('add', compact('kompetisi', 'lomba', 'peserta', 'hargaBundling', 'syaratBundling', 'lombaSudahDipilih'));
    }

    // AddController.php
    public function getLombaTerpilih($pesertaId)
    {
        $lombaSudahDipilih = DetailLomba::where('peserta_id', $pesertaId)
            ->pluck('lomba_id')
            ->toArray();

        return response()->json($lombaSudahDipilih);
    }


    public function edit($id)
    {
        $peserta = peserta::findOrFail($id);
        return view('edit', compact('peserta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'limit' => 'required',
        ]);

        $peserta = peserta::findOrFail($id);
        $peserta->update($request->only(['nama_peserta', 'jenis_kelamin', 'tgl_lahir', 'limit']));

        return redirect()->route('list')->with('success', 'Peserta berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peserta = peserta::findOrFail($id);
        $peserta->delete();

        return redirect()->route('list')->with('success', 'Peserta berhasil dihapus.');
    }
}
