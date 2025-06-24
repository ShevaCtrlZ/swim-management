<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DetailLomba;
use App\Models\Lomba;

class LombaController extends Controller
{
    public function create($id)
    {
        // Kirim ID kompetisi ke view
        return view('tambah_lomba', compact('id'));
    }

    public function store(Request $request, $id)
    {

        // Validasi data
        $request->validate([
            'jarak' => 'required|integer|min:1',
            'jenis_gaya' => 'required|string|max:255',
            'jumlah_lintasan' => 'required|integer|min:1',
            'tahun_lahir_minimal' => 'required|integer|min:1000|max:' . (date('Y') - 1),
            'tahun_lahir_maksimal' => 'required|integer|min:1000|max:' . (date('Y') - 1),
            'jk' => 'required|string|in:L,P', // L untuk laki-laki, P untuk perempuan
            'harga' => 'required|numeric|min:0',
        ]);

        // Hitung nomor lomba otomatis
        $nomorLomba = DB::table('lomba')->where('kompetisi_id', $id)->count() + 1;

        // Simpan data lomba ke database
        DB::table('lomba')->insert([
            'kompetisi_id' => $id,
            'jarak' => $request->jarak,
            'jenis_gaya' => $request->jenis_gaya,
            'jumlah_lintasan' => $request->jumlah_lintasan,
            'nomor_lomba' => $nomorLomba,
            'tahun_lahir_minimal' => $request->tahun_lahir_minimal,
            'tahun_lahir_maksimal' => $request->tahun_lahir_maksimal,
            'jk' => $request->jk,
            'harga' => $request->harga,
        ]);

        // Redirect ke halaman detail kompetisi
        return redirect()->route('lihat_kompetisi', $id)->with('success', 'Lomba berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        // Ambil data lomba yang akan dihapus
        $lomba = DB::table('lomba')->where('id', $id)->first();

        if ($lomba) {
            // Hapus lomba
            DB::table('lomba')->where('id', $id)->delete();

            // Update nomor lomba untuk lomba lain di kompetisi yang sama
            $lombaLain = DB::table('lomba')
                ->where('kompetisi_id', $lomba->kompetisi_id)
                ->orderBy('nomor_lomba')
                ->get();

            $nomor = 1;
            foreach ($lombaLain as $item) {
                DB::table('lomba')->where('id', $item->id)->update(['nomor_lomba' => $nomor]);
                $nomor++;
            }
        }

        // Redirect ke halaman detail kompetisi
        return redirect()->route('lihat_kompetisi', $lomba->kompetisi_id)->with('success', 'Lomba berhasil dihapus.');
    }

    public function edit($id)
    {
        $lomba = Lomba::findOrFail($id);
        return view('edit_lomba', compact('lomba'));
    }

    public function updateHasil(Request $request, $id)
    {
        $request->validate([
            'hasil' => 'required|string|regex:/^\d{2}:\d{2}:\d{2}$/',
        ]);

        DB::table('detail_lomba')
            ->where('id', $id)
            ->update(['catatan_waktu' => $request->hasil]);

        return redirect()->back()->with('success', 'Hasil waktu berhasil diperbarui.');
    }

    public function show($id)
    {
        $kompetisi = DB::table('kompetisi')->find($id);
        $lomba = DB::table('lomba')
            ->where('kompetisi_id', $id)
            ->with(['nomorLomba' => function ($query) {
                $query->select('id', 'nama_peserta', 'tgl_lahir', 'asal_klub', 'limit', 'hasil', 'peserta_id', 'lomba_id');
            }])
            ->get();

        return view('lihat_kompetisi', compact('kompetisi', 'lomba'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_lomba' => 'required|string|max:255',
            'jarak' => 'required|integer',
            'jenis_gaya' => 'required|string',
            'jk' => 'required|in:Laki-laki,Perempuan',
            'tahun_lahir_minimal' => 'required|integer',
            'tahun_lahir_maksimal' => 'required|integer',
        ]);

        $lomba = Lomba::findOrFail($id);
        $lomba->update([
            'nomor_lomba' => $request->nomor_lomba,
            'jarak' => $request->jarak,
            'jenis_gaya' => $request->jenis_gaya,
            'jk' => $request->jk,
            'tahun_lahir_minimal' => $request->tahun_lahir_minimal,
            'tahun_lahir_maksimal' => $request->tahun_lahir_maksimal,
        ]);

        return redirect()->back()->with('success', 'Nomor lomba berhasil diperbarui.');
    }

    public function stopSeries(Request $request)
    {
        $peserta = DetailLomba::find($request->id);
        if ($peserta) {
            $peserta->catatan_waktu = gmdate("H:i:s", $request->catatan_waktu); // atau simpan detik kalau perlu
            $peserta->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
