<?php

namespace App\Http\Controllers;

use App\Models\Kompetisi;
use App\Models\Lomba;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

                $jumlahLintasan = $item->jumlah_lintasan ?? 4; // Default 8 lintasan
                $item->nomorLomba = $peserta->chunk($jumlahLintasan);

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
}
