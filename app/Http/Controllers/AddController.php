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
use App\Models\Klub;

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
            'metode' => 'required|in:normal,bundling',
        ]);

        $peserta_id = $request->peserta_id;
        $lombaDipilih = $request->lomba_id;
        $limits = $request->input('limit_per_lomba', []);

        // Ambil kompetisi_id dari salah satu lomba (anggap semua lomba dalam satu kompetisi)
        $kompetisiId = Lomba::whereIn('id', $lombaDipilih)->value('kompetisi_id');
        $kompetisi = Kompetisi::find($kompetisiId);

        $hargaBundling   = $kompetisi->harga_bundling ?? 0;
        $syaratBundling  = $kompetisi->syarat_bundling ?? 9999;

        $lombaList = Lomba::whereIn('id', $lombaDipilih)->get();

        // --- Hitung total harga ---
        $totalHarga = 0;
        if ($request->metode === 'bundling') {
            if ($lombaList->count() < $syaratBundling) {
                return redirect()->back()->with('error', "Bundling minimal harus {$syaratBundling} lomba");
            }

            // harga bundling
            $totalHarga = $hargaBundling;

            // kalau lebih dari syarat bundling, hitung sisanya harga normal
            if ($lombaList->count() > $syaratBundling) {
                $sisaLomba = $lombaList->skip($syaratBundling);
                foreach ($sisaLomba as $lomba) {
                    $totalHarga += $lomba->harga;
                }
            }
        } else {
            // normal → jumlahkan harga masing-masing lomba
            foreach ($lombaList as $lomba) {
                $totalHarga += $lomba->harga;
            }
        }

        // --- Simpan detail lomba ---
        foreach ($lombaList as $lomba) {
            DetailLomba::create([
                'lomba_id'    => $lomba->id,
                'peserta_id'  => $peserta_id,
                'no_lintasan' => null,
                'urutan'      => null,
                'catatan_waktu' => null,
                'keterangan'  => null,
                'limit'       => $limits[$lomba->id] ?? '99:99:99',
            ]);
        }

        // --- Update total_harga ke klub ---
        $peserta = Peserta::findOrFail($peserta_id);

        if ($peserta->klub_id) {
            $klub = Klub::find($peserta->klub_id);
            if ($klub) {
                $klub->increment('total_harga', $totalHarga);
                // kalau mau overwrite, bisa pakai:
                // $klub->update(['total_harga' => $totalHarga]);
            }
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
        $user = Auth::user();
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
                    'jk' => strtoupper(substr($l->jk, 0, 1)),
                    'harga' => $l->harga ?? 0
                ];
            });
        })->values();

        $lombaSudahDipilih = [];

        // contoh: ambil bundling default dari kompetisi pertama
        $hargaBundling = $kompetisi->first()->harga_bundling ?? 0;
        $syaratBundling = $kompetisi->first()->syarat_bundling ?? 9999;

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
