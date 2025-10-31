<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Lomba;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'harga_bundling' => 'nullable|numeric|min:0',
            'syarat_bundling' => 'nullable|integer|min:1',
        ]);

        // Store data in the kompetisi model
        DB::table('kompetisi')->insert([
            'nama_kompetisi' => $request->nama_kompetisi,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'lokasi' => $request->lokasi,
            'harga_bundling' => $request->harga_bundling,
            'syarat_bundling' => $request->syarat_bundling,
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
            'harga_bundling' => $request->harga_bundling,
            'syarat_bundling' => $request->syarat_bundling,
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
                        'detail_lomba.keterangan',
                        'peserta.nama_peserta',
                        'peserta.tgl_lahir',
                        'peserta.jenis_kelamin',
                        'peserta.asal_klub',
                        'detail_lomba.limit',
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
                        'detail_lomba.limit',
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
        // Validasi input
        $request->validate([
            'hasil' => 'nullable|string|regex:/^\d{2}:\d{2}:\d{2}$/',
            'keterangan' => 'nullable|in:NS,DQ',
        ]);

        // Jika NS atau DQ dipilih, maka waktu diset ke '00:00:00'
        $catatanWaktu = $request->keterangan ? '00:00:00' : $request->hasil;

        // Update ke database
        DB::table('detail_lomba')
            ->where('id', $id)
            ->update([
                'catatan_waktu' => $catatanWaktu,
                'keterangan' => $request->keterangan,
            ]);

        return redirect()->back()->with('success', 'Hasil lomba berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kompetisi = Kompetisi::findOrFail($id);
        $kompetisi->delete();

        return redirect()->route('list_kompetisi')->with('success', 'Data successfully deleted.');
    }

    public function sortAllPeserta($id)
    {
        $lombaList = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->get();

        foreach ($lombaList as $lomba) {
            $jumlahLintasan = $lomba->jumlah_lintasan;

            if ($jumlahLintasan < 2) continue;

            $peserta = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $lomba->id)
                ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                ->select('detail_lomba.id', 'peserta.nama_peserta', 'detail_lomba.limit')
                ->orderByRaw("CASE WHEN detail_lomba.limit IS NULL OR detail_lomba.limit = '' THEN 1 ELSE 0 END, detail_lomba.limit DESC")
                ->get()
                ->values();

            $totalPeserta = $peserta->count();
            if ($totalPeserta < 2) continue;

            // Jika peserta <= lintasan, buat hanya 1 seri
            if ($totalPeserta <= $jumlahLintasan) {
                foreach ($peserta as $i => $pesertaRow) {
                    DB::table('detail_lomba')
                        ->where('id', $pesertaRow->id)
                        ->update([
                            'no_lintasan' => $i + 1,
                            'urutan' => 1,
                            'seri' => 1,
                        ]);
                }
                continue;
            }

            // NEW: isi seri penuh di bagian bawah sebanyak mungkin,
            // sisanya ditempatkan di seri atas. Usahakan setiap seri atas minimal 2 peserta dengan meminjam dari bawah bila perlu.
            $jumlahSeri = (int) ceil($totalPeserta / $jumlahLintasan);
            $fullBottomCount = intdiv($totalPeserta, $jumlahLintasan); // berapa seri penuh (nilai = jumlahLintasan)
            if ($fullBottomCount > $jumlahSeri) $fullBottomCount = $jumlahSeri;
            $remainder = $totalPeserta - ($fullBottomCount * $jumlahLintasan);

            // inisialisasi semua seri 0
            $seriDistribusi = array_fill(0, $jumlahSeri, 0);

            // isi seri penuh di bagian bawah (last indices)
            for ($i = 0; $i < $fullBottomCount; $i++) {
                $seriDistribusi[$jumlahSeri - 1 - $i] = $jumlahLintasan;
            }

            $slots = $jumlahSeri - $fullBottomCount; // seri yang belum terisi (di atas)

            if ($slots > 0) {
                // Usahakan minimal 2 per seri atas jika memungkinkan
                $minPerSlot = 2;
                $required = $minPerSlot * $slots;

                // Jika remainder kurang dari required, coba "pinjam" dari seri penuh di bagian bawah.
                // Perubahan: pinjam dari seri penuh paling atas (indeks = jumlahSeri - fullBottomCount)
                // agar seri paling bawah tetap penuh jika memungkinkan.
                $borrowStart = $jumlahSeri - $fullBottomCount; // index pertama dari seri penuh (jika ada)
                if ($borrowStart < 0) $borrowStart = 0;

                // ulangi peminjaman sampai terpenuhi atau tidak bisa meminjam lagi
                while ($remainder < $required) {
                    $borrowed = false;
                    for ($idx = $borrowStart; $idx < $jumlahSeri && $remainder < $required; $idx++) {
                        if ($seriDistribusi[$idx] > 1) {
                            // pinjam 1 dari seri ini
                            $seriDistribusi[$idx] -= 1;
                            $remainder += 1;
                            $borrowed = true;
                        }
                    }
                    if (!$borrowed) break; // tidak ada seri yang bisa dipinjam lagi
                }

                // Setelah upaya pinjam, jika masih kurang, kita tetap lanjutkan dengan apa yang ada.

                // Sekarang alokasikan minimal ke tiap slot
                $basePerSlot = intdiv($remainder, $slots);
                $extra = $remainder % $slots;

                // Jika basePerSlot < minPerSlot, alokasikan minPerSlot dulu bila memungkinkan
                if ($basePerSlot < $minPerSlot) {
                    // Coba set setiap slot minimal minPerSlot jika total memungkinkan
                    $alloc = min($remainder, $required);
                    // isi setiap slot dengan 1 atau 2 sesuai alokasi
                    for ($s = 0; $s < $slots; $s++) {
                        $give = min($minPerSlot, $alloc);
                        $seriDistribusi[$s] = $give;
                        $alloc -= $give;
                    }
                    // jika masih sisa alokasi, sebar sisa ke slot dari kiri ke kanan
                    for ($s = 0; $s < $slots && $alloc > 0; $s++) {
                        $seriDistribusi[$s]++;
                        $alloc--;
                    }
                } else {
                    // Alokasi rata + distribusi extra (dari kiri ke kanan)
                    for ($s = 0; $s < $slots; $s++) {
                        $seriDistribusi[$s] = $basePerSlot + ($extra > 0 ? 1 : 0);
                        if ($extra > 0) $extra--;
                    }
                }
            } else {
                // semua seri diisi penuh, nothing to do
            }

            // Pastikan jumlah total cocok (safety)
            $sumDistribusi = array_sum($seriDistribusi);
            if ($sumDistribusi !== $totalPeserta) {
                // koreksi jika ada perbedaan karena pembulatan: tambahkan/kurangi di seri atas pertama yang ada
                $diff = $totalPeserta - $sumDistribusi;
                for ($i = 0; $i < $jumlahSeri && $diff !== 0; $i++) {
                    if ($diff > 0) {
                        $seriDistribusi[$i]++;
                        $diff--;
                    } elseif ($diff < 0 && $seriDistribusi[$i] > 0) {
                        $seriDistribusi[$i]--;
                        $diff++;
                    }
                }
            }

            // Reset seri lokal (agar selalu dimulai dari 1)
            $localSeriNumber = 1;

            // Bagi peserta sesuai distribusi (seriDistribusi[0] => seri 1 (atas), ..., last => bawah)
            $index = 0;
            foreach ($seriDistribusi as $jumlahPeserta) {
                if ($jumlahPeserta === 0) {
                    $localSeriNumber++;
                    continue;
                }

                $pesertaSeri = $peserta->slice($index, $jumlahPeserta);
                $index += $jumlahPeserta;

                foreach ($pesertaSeri as $i => $pesertaRow) {
                    DB::table('detail_lomba')
                        ->where('id', $pesertaRow->id)
                        ->update([
                            'no_lintasan' => $i + 1,
                            'urutan' => $localSeriNumber,
                            'seri' => $localSeriNumber,
                        ]);
                }

                $localSeriNumber++; // tambahkan seri hanya dalam satu lomba
            }
        }

        return redirect()->route('lihat_kompetisi', ['id' => $id])
            ->with('success', 'Peserta berhasil dibagi ke dalam seri. Seri awal berisi peserta paling lambat dan jumlah lebih sedikit.');
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
            ->select('detail_lomba.id', 'detail_lomba.peserta_id', 'detail_lomba.limit')
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

    // New: Terapkan Center-Out Sorting untuk semua nomor lomba pada sebuah kompetisi (semua seri)
    public function centerMaxLimitAll($kompetisi_id)
    {
        // Ambil semua lomba pada kompetisi
        $lombaList = DB::table('lomba')->where('kompetisi_id', $kompetisi_id)->get();

        foreach ($lombaList as $lomba) {
            // Ambil semua seri yang ada untuk lomba ini
            $seriList = DB::table('detail_lomba')
                ->where('detail_lomba.lomba_id', $lomba->id)
                ->distinct()
                ->pluck('seri')
                ->filter(function ($s) { return !is_null($s) && $s !== ''; })
                ->values();

            foreach ($seriList as $seri) {
                // Ambil peserta untuk seri ini
                $kelompok = DB::table('detail_lomba')
                    ->where('detail_lomba.lomba_id', $lomba->id)
                    ->where('detail_lomba.seri', $seri)
                    ->join('peserta', 'detail_lomba.peserta_id', '=', 'peserta.id')
                    ->select('detail_lomba.id', 'detail_lomba.peserta_id', 'detail_lomba.limit', 'detail_lomba.keterangan')
                    ->orderBy('detail_lomba.urutan')
                    ->get();

                if ($kelompok->isEmpty()) {
                    continue;
                }

                // Filter keluarin DQ/NS jika diperlukan (konsisten dengan hasilJuaraUmum)
                $sorted = $kelompok->filter(function($d) {
                    return !in_array(strtoupper($d->keterangan ?? ''), ['DQ','NS']);
                })->sortBy(function ($p) {
                    return is_null($p->limit) || $p->limit === '' ? INF : $p->limit;
                })->values();

                // Jika setelah filter tidak ada, gunakan semua peserta asli
                if ($sorted->isEmpty()) {
                    $sorted = $kelompok->values();
                }

                // Center-Out Sorting (sama seperti single-seri)
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

                // Update no_lintasan hanya pada detail_lomba yang sesuai (lomba + seri)
                foreach ($final as $i => $pes) {
                    DB::table('detail_lomba')
                        ->where('id', $pes->id)
                        ->where('detail_lomba.lomba_id', $lomba->id)
                        ->where('seri', $seri)
                        ->update([
                            'no_lintasan' => $i + 1
                        ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Semua nomor lomba telah diatur (Center-Out) berdasarkan limit.');
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

    public function klubPesertaKompetisi($id)
    {
        $kompetisi = Kompetisi::findOrFail($id);
        $lomba = Lomba::with('peserta')->where('kompetisi_id', $id)->get();

        // ambil nama klub dari user yang login (sesuaikan sumber jika berbeda)
        $namaKlub = Auth::check() ? (Auth::user()->asal_klub ?? '') : '';

        return view('klub.lihat_kompetisi', compact('kompetisi', 'lomba', 'namaKlub'));
    }

public function hasilJuaraUmum($kompetisi_id)
{
    $kompetisi = Kompetisi::findOrFail($kompetisi_id);
    $lomba = Lomba::with(['detailLomba.peserta'])->where('kompetisi_id', $kompetisi_id)->get();

    $rekap = [];
    foreach ($lomba as $item) {
        $grouped = $item->detailLomba->groupBy('seri');
        foreach ($grouped as $seri => $details) {
            // Exclude DQ/NS
            $filtered = $details->filter(function($detail) {
                return !in_array(strtoupper($detail->keterangan), ['DQ', 'NS']);
            });
            $sorted = $filtered->sort(function($a, $b) {
                $waktuA = $a->catatan_waktu ? strtotime("1970-01-01 {$a->catatan_waktu} UTC") : PHP_INT_MAX;
                $waktuB = $b->catatan_waktu ? strtotime("1970-01-01 {$b->catatan_waktu} UTC") : PHP_INT_MAX;
                return $waktuA <=> $waktuB;
            })->values();

            foreach ($sorted->take(3) as $idx => $detail) {
                $peserta = $detail->peserta;
                if ($peserta) {
                    $tahun = date('Y', strtotime($peserta->tgl_lahir));
                    if (!isset($rekap[$tahun][$peserta->id])) {
                        $rekap[$tahun][$peserta->id] = [
                            'nama' => $peserta->nama_peserta,
                            'asal_klub' => $peserta->asal_klub,
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
    }

    // Urutkan juara umum tiap tahun berdasarkan total medali
    foreach ($rekap as $tahun => &$pesertas) {
        uasort($pesertas, function($a, $b) {
            if ($a['total_juara1'] != $b['total_juara1']) {
                return $b['total_juara1'] - $a['total_juara1'];
            }
            if ($a['total_juara2'] != $b['total_juara2']) {
                return $b['total_juara2'] - $a['total_juara2'];
            }
            return $b['total_juara3'] - $a['total_juara3'];
        });
    }

    return view('hasil_juara_umum', compact('kompetisi', 'rekap'));
}
public function exportPesertaKlub($kompetisi_id, $klub){
    $kompetisi = Kompetisi::findOrFail($kompetisi_id);

    // ambil peserta untuk klub (cocokkan string asal_klub)
    $pesertas = DB::table('detail_lomba')
        ->join('peserta','detail_lomba.peserta_id','peserta.id')
        ->join('lomba','detail_lomba.lomba_id','lomba.id')
        ->where('lomba.kompetisi_id', $kompetisi_id)
        ->where('peserta.asal_klub', urldecode($klub))
        ->select(
            'peserta.id as peserta_id',
            'peserta.nama_peserta',
            'peserta.tgl_lahir',
            'peserta.jenis_kelamin',
            'peserta.asal_klub',
            'detail_lomba.no_lintasan',
            'detail_lomba.catatan_waktu',
            'detail_lomba.limit',
            'lomba.nomor_lomba',
            'lomba.jarak',
            'lomba.jenis_gaya'
        )
        ->orderBy('lomba.nomor_lomba')
        ->orderBy('detail_lomba.no_lintasan')
        ->get();

        $pdf = Pdf::loadView('klub.peserta_pdf', compact('kompetisi','pesertas','klub'));
        return $pdf->download('peserta_'.$kompetisi->id.'_'.preg_replace('/[^A-Za-z0-9]/','_',urldecode($klub)).'.pdf');
        }

        public function updateHasilSeries(Request $request, $lomba_id, $seri = null)
    {
        // Jika seri tidak disertakan, batalkan dengan pesan yang jelas.
        if (is_null($seri) || $seri === '') {
            return redirect()->back()->with('error', 'Parameter seri diperlukan untuk menyimpan hasil per seri.');
        }

        // Pastikan seri numeric
        if (!is_numeric($seri)) {
            return redirect()->back()->with('error', 'Parameter seri tidak valid.');
        }

        // Terima array: hasil[detailId] dan keterangan[detailId]
        $hasilArr = $request->input('hasil', []);
        $keteranganArr = $request->input('keterangan', []);

        foreach ($hasilArr as $detailId => $waktu) {
            $keterangan = $keteranganArr[$detailId] ?? null;
            $keterangan = $keterangan === '' ? null : $keterangan;

            // jika NS atau DQ dipilih, set waktu ke '00:00:00'
            if (in_array(strtoupper($keterangan ?? ''), ['NS', 'DQ'])) {
                $catatanWaktu = '00:00:00';
            } else {
                // validasi format HH:MM:SS atau kosong → biarkan kosong/null bila tidak valid
                $waktu = trim((string)$waktu);
                if ($waktu === '') {
                    $catatanWaktu = null;
                } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $waktu)) {
                    $catatanWaktu = $waktu;
                } else {
                    // skip update untuk entri invalid
                    continue;
                }
            }

            DB::table('detail_lomba')
                ->where('id', $detailId)
                ->where('lomba_id', $lomba_id)
                ->where('seri', $seri)
                ->update([
                    'catatan_waktu' => $catatanWaktu,
                    'keterangan' => $keterangan,
                ]);
        }

        return redirect()->back()->with('success', 'Hasil seri telah disimpan.');
    }
}
