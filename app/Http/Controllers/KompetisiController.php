<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Lomba;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class KompetisiController extends Controller
{
    public function showListView(): View
    {
        return view('list_kompetisi');
    }

    public function showUserView(): View
    {
        return view('kompetisi');
    }

    public function showAddView(): View
    {
        return view('Add_kompetisi');
    }

    public function storeData(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'nama_kompetisi' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
            'lokasi' => 'required',
        ]);

        // Store data in the kompetisi model
        DB::table('kompetisi')->insert([
            'nama_kompetisi' => $request->nama_kompetisi,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'lokasi' => $request->lokasi,
        ]);

        // Redirect to the add view
        return redirect()->route('list_kompetisi')->with('success', 'Data successfully added.');
    }

    public function edit(Request $request, $id)
    {
        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->update([
            'nama_kompetisi' => $request->nama_kompetisi,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('list_kompetisi')->with('success', 'Data successfully added.');
    }

    public function list_kompetisi()
    {
        $data = Kompetisi::all();
        return view('list_Kompetisi', compact('data'));
    }

    public function kompetisi()
    {
        $data = Kompetisi::all();
        return view('kompetisi', compact('data'));
    }

    public function show($id)
    {
        $kompetisi = DB::table('kompetisi')->where('id', $id)->first();

        // Ambil semua lomba
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get()
            ->map(function ($item) {
                // Ambil peserta berdasarkan seri
                $peserta = DB::table('detail_lomba')
                    ->where('detail_lomba.lomba_id', $item->id)
                    ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                    ->select(
                        'detail_lomba.id',
                        'detail_lomba.no_lintasan',
                        'detail_lomba.urutan',
                        'detail_lomba.seri',
                        'detail_lomba.catatan_waktu',
                        'peserta.nama_peserta',
                        'peserta.tgl_lahir',
                        'peserta.jenis_kelamin',
                        'peserta.asal_klub',
                        'peserta.limit',
                    )
                    ->orderBy('detail_lomba.seri')
                    ->orderBy('detail_lomba.no_lintasan')
                    ->get()
                    ->groupBy('seri'); // ← ini kuncinya: pisahkan berdasarkan seri

                $item->nomorLomba = $peserta; // → sekarang sudah per-seri
                return $item;
            });

        return view('lihat_kompetisi', compact('kompetisi', 'lomba'));
    }


    public function lihat($id)
    {
        $kompetisi = DB::table('kompetisi')->where('id', $id)->first();

        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get()
            ->map(function ($item) {
                // Ambil peserta untuk lomba ini
                $peserta = DB::table('detail_lomba')
                    ->where('detail_lomba.lomba_id', $item->id)
                    ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                    ->select(
                        'detail_lomba.id', // Include the ID of detail_lomba
                        'detail_lomba.no_lintasan',
                        'detail_lomba.urutan',
                        'detail_lomba.catatan_waktu',
                        'peserta.nama_peserta',
                        'peserta.tgl_lahir',
                        'peserta.jenis_kelamin',
                        'peserta.asal_klub',
                        'peserta.limit',
                    )
                    ->orderBy('detail_lomba.no_lintasan')
                    ->get();

                // Bagi peserta menjadi beberapa nomor lomba berdasarkan jumlah lintasan
                $jumlahLintasan = $item->jumlah_lintasan ?? 4; // Default 8 lintasan
                $item->nomorLomba = $peserta->chunk($jumlahLintasan);

                return $item;
            });

        return view('user_kompetisi', compact('kompetisi', 'lomba'));
    }

    public function index()
    {
        // Ambil kompetisi yang sedang berlangsung (tgl_selesai >= hari ini)
        $kompetisi = Kompetisi::where('tgl_selesai', '>=', now())->get();

        // Kirim data ke view
        return view('index', compact('kompetisi'));
    }

    public function bagiPeserta($id)
    {
        // Ambil data lomba berdasarkan kompetisi
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get()
            ->map(function ($item) {
                // Validasi jumlah lintasan
                if ($item->jumlah_lintasan <= 0) {
                    throw new \Exception("Jumlah lintasan harus lebih dari nol untuk lomba ID: {$item->id}");
                }

                // Ambil peserta untuk lomba ini
                $peserta = DB::table('detail_lomba')
                    ->where('detail_lomba.lomba_id', $item->id)
                    ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                    ->select('peserta.id as peserta_id', 'peserta.nama_peserta')
                    ->get();

                // Bagi peserta ke lintasan
                $heats = $peserta->shuffle()->chunk($item->jumlah_lintasan);

                // Update lintasan dan urutan di database
                foreach ($heats as $heatIndex => $heat) {
                    foreach ($heat as $laneIndex => $peserta) {
                        DB::table('detail_lomba')
                            ->where('detail_lomba.lomba_id', $item->id)
                            ->where('detail_lomba.peserta_id', $peserta->peserta_id)
                            ->update([
                                'no_lintasan' => $laneIndex + 1,
                                'urutan' => $heatIndex + 1,
                            ]);
                    }
                }

                return [
                    'lomba_id' => $item->id,
                    'peserta' => $peserta->pluck('peserta_id'),
                ];
            });

        // Redirect ke halaman detail lomba
        return redirect()->route('lihat_kompetisi', ['id' => $id])
            ->with('success', 'Peserta berhasil dibagi ke dalam lintasan.');
    }

    public function randomizePeserta($id)
    {
        // Ambil data lomba berdasarkan kompetisi
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get()
            ->map(function ($item) {
                // Validasi jumlah lintasan
                if ($item->jumlah_lintasan <= 0) {
                    throw new \Exception("Jumlah lintasan harus lebih dari nol untuk lomba ID: {$item->id}");
                }

                // Ambil peserta untuk lomba ini
                $peserta = DB::table('detail_lomba')
                    ->where('detail_lomba.lomba_id', $item->id)
                    ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                    ->select('peserta.id as peserta_id', 'peserta.nama_peserta')
                    ->get();

                // Acak peserta dan bagi ke lintasan
                $heats = $peserta->shuffle()->chunk($item->jumlah_lintasan);

                // Update lintasan dan urutan di database
                foreach ($heats as $heatIndex => $heat) {
                    foreach ($heat as $laneIndex => $peserta) {
                        DB::table('detail_lomba')
                            ->where('detail_lomba.lomba_id', $item->id)
                            ->where('detail_lomba.peserta_id', $peserta->peserta_id)
                            ->update([
                                'no_lintasan' => $laneIndex + 1,
                                'urutan' => $heatIndex + 1,
                            ]);
                    }
                }

                return [
                    'lomba_id' => $item->id,
                    'peserta' => $peserta->pluck('peserta_id'),
                ];
            });

        // Redirect ke halaman detail lomba
        return redirect()->route('lihat_kompetisi', ['id' => $id])
            ->with('success', 'Peserta berhasil diacak dan dibagi ke dalam lintasan.');
    }

    public function randomizeAllPeserta($id)
    {
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get();

        foreach ($lomba as $item) {
            if ($item->jumlah_lintasan <= 0) {
                continue; // Skip if jumlah_lintasan is invalid
            }

            $peserta = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $item->id)
                ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                ->select('peserta.id as peserta_id', 'peserta.nama_peserta')
                ->get();

            $heats = $peserta->shuffle()->chunk($item->jumlah_lintasan);

            foreach ($heats as $heatIndex => $heat) {
                foreach ($heat as $laneIndex => $peserta) {
                    DB::table('detail_lomba')
                        ->where('detail_lomba.lomba_id', $item->id)
                        ->where('detail_lomba.peserta_id', $peserta->peserta_id)
                        ->update([
                            'no_lintasan' => $laneIndex + 1,
                            'urutan' => $heatIndex + 1,
                        ]);
                }
            }
        }

        return redirect()->route('lihat_kompetisi', ['id' => $id])
            ->with('success', 'Semua peserta berhasil diacak dan dibagi ke dalam lintasan.');
    }

    public function updateHasil(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hasil' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        DB::table('detail_lomba')
            ->where('id', $id)
            ->update(['catatan_waktu' => $request->hasil]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->delete();

        return redirect()->route('list_kompetisi')->with('success', 'Data successfully deleted.');
    }

    public function sortAllPeserta($id)
    {
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get();

        foreach ($lomba as $item) {
            if ($item->jumlah_lintasan <= 0) {
                continue; // Skip if jumlah_lintasan is invalid
            }

            // Ambil peserta dan urutkan dari limit terbesar ke terkecil
            $peserta = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $item->id)
                ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                ->select('detail_lomba.id', 'peserta.id as peserta_id', 'peserta.nama_peserta', 'peserta.limit')
                ->orderByRaw("CASE WHEN peserta.limit IS NULL OR peserta.limit = '' THEN 1 ELSE 0 END, peserta.limit DESC")
                ->get();

            // Bagi peserta ke dalam seri sesuai jumlah lintasan
            $seriPeserta = $peserta->chunk($item->jumlah_lintasan);

            foreach ($seriPeserta as $seriIndex => $heat) {
                foreach ($heat as $laneIndex => $peserta) {
                    DB::table('detail_lomba')
                        ->where('id', $peserta->id)
                        ->update([
                            'no_lintasan' => $laneIndex + 1,
                            'urutan' => $seriIndex + 1,
                            'seri' => $seriIndex + 1 // pastikan seri diisi!
                        ]);
                }
            }
        }

        return redirect()->route('lihat_kompetisi', ['id' => $id])
            ->with('success', 'Semua peserta berhasil diurutkan berdasarkan limit waktu (terbesar ke terkecil) dan seri sudah diatur.');
    }

    public function centerMaxLimitPeserta(Request $request, $lomba_id, $seri)
    {
        // Validasi parameter seri
        if (is_null($seri) || $seri === '' || !is_numeric($seri)) {
            return back()->with('error', 'Parameter seri tidak valid.');
        }

        // Gunakan $seri langsung dari parameter (tanpa dikurangi 1)
        $seri = (int)$seri;

        // Ambil jumlah lintasan dari lomba
        $lomba = DB::table('lomba')->where('id', $lomba_id)->first();
        if (!$lomba) {
            return back()->with('error', 'Lomba tidak ditemukan.');
        }

        // Ambil peserta hanya dari seri yang diminta
        $kelompok = DB::table('detail_lomba')
            ->where('detail_lomba.lomba_id', $lomba_id)
            ->where('detail_lomba.seri', $seri)
            ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
            ->select('detail_lomba.id', 'detail_lomba.peserta_id', 'peserta.limit')
            ->orderBy('detail_lomba.urutan')
            ->get();

        if ($kelompok->isEmpty()) {
            return back()->with('error', 'Seri tidak ditemukan atau tidak ada peserta.');
        }

        // Urutkan peserta berdasarkan limit (terendah ke tertinggi, null terakhir)
        $sorted = $kelompok->sortBy(function ($p) {
            return is_null($p->limit) || $p->limit === '' ? INF : $p->limit;
        })->values();

        // Center-Out Sorting
        $final = [];
        $count = $sorted->count();
        $center = intval(floor(($count - 1) / 2));
        $left = $center;
        $right = $center + 1;
        $toggle = true;

        foreach ($sorted as $peserta) {
            if ($toggle) {
                $final[$left--] = $peserta;
            } else {
                $final[$right++] = $peserta;
            }
            $toggle = !$toggle;
        }

        ksort($final);
        $final = array_values($final);

        // Update hanya peserta di seri dan lomba ini
        foreach ($final as $i => $peserta) {
            DB::table('detail_lomba')
                ->where('id', $peserta->id)
                ->where('detail_lomba.lomba_id', $lomba_id) // tambahkan filter lomba_id
                ->where('seri', $seri) // filter seri
                ->update([
                    'no_lintasan' => $i + 1
                ]);
        }

        return back()->with('success', 'Peserta pada seri ini sudah diurutkan dengan Center-Out Sorting.');
    }

    public function klub()
    {
        $klub = Auth::user()->klub;

        $kompetisiList = \App\Models\Kompetisi::with([
            'lomba.peserta' => function ($q) use ($klub) {
                $q->where('klub_id', $klub->id); // hanya peserta dari klub ini
            }
        ])->get();

        // Hitung total_harga per kompetisi
        foreach ($kompetisiList as $kompetisi) {
            $total = 0;

            foreach ($kompetisi->lomba as $lomba) {
                // hitung peserta klub di lomba ini × harga lomba
                $jumlahPeserta = $lomba->peserta->count();
                $total += $jumlahPeserta * $lomba->harga; // asumsi `harga` ada di model Lomba
            }

            // simpan sementara sebagai properti di model kompetisi
            $kompetisi->total_harga_klub = $total;
        }

        return view('klub.kompetisi', compact('klub', 'kompetisiList'));
    }
}
