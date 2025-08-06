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
        // dd($request->all());

        // Validasi data awal
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'lomba_id' => 'required|array',
            'lomba_id.*' => 'exists:lomba,id',
            'limit_per_lomba' => 'required|array',
            'limit_per_lomba.*' => ['required', 'regex:/^\d{2}:\d{2}:\d{2}$/'],
        ], [

            'limit_per_lomba.required' => 'Field limit wajib diisi.',
            'limit_per_lomba.*.required' => 'Limit untuk setiap lomba wajib diisi.',
            'limit_per_lomba.*.integer' => 'Limit harus berupa angka.',
            'limit_per_lomba.*.min' => 'Limit minimal bernilai 1.',
        ]);


        $tahunLahir = date('Y', strtotime($request->tgl_lahir));

        // Validasi setiap lomba sesuai tahun lahir
        foreach ($request->lomba_id as $lombaId) {
            $lomba = Lomba::findOrFail($lombaId);
            if ($tahunLahir < $lomba->tahun_lahir_minimal || $tahunLahir > $lomba->tahun_lahir_maksimal) {
                return back()->with('error', "Tahun lahir peserta tidak sesuai dengan ketentuan lomba: {$lomba->jenis_gaya} - {$lomba->jarak}m");
            }
        }

        // Ambil data user/klub
        $user = Auth::user();
        $asal_klub = $user->klub->nama_klub ?? 'Unknown';
        $firstLombaId = $request->lomba_id[0];
        // Simpan data peserta
        $peserta = peserta::create([
            'nama_peserta' => $request->nama_peserta,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'asal_klub' => $asal_klub,
            'klub_id' => $user->klub_id,
            'lomba_id' => $firstLombaId, // karena bisa banyak
        ]);

        $totalHarga = 0;
        foreach ($request->lomba_id as $lombaId) {
            $limitValue = $request->limit_per_lomba[$lombaId] ?? null;

            // if (!$limitValue || $limitValue === '99:99:99') {
            //     return back()->with('error', "Limit untuk lomba ID $lombaId belum diisi atau default.");
            // }

            Detaillomba::create([
                'peserta_id' => $peserta->id,
                'lomba_id' => $lombaId,
                'limit' => $limitValue,
                'no_lintasan' => null,
                'urutan' => null,
                'catatan_waktu' => null,
            ]);
        }
        foreach ($request->lomba_id as $lombaId) {
            if (empty($request->limit_per_lomba[$lombaId])) {
                return back()->with('error', "Limit untuk lomba ID $lombaId belum diisi.");
            }
        }

        // Validasi khusus bundling
        if ($request->metode === 'bundling') {
            $totalHarga = 120000; // harga tetap
            if (count($request->lomba_id) !== 4) {
                return back()->withErrors(['Bundling harus memilih tepat 4 lomba.']);
            }
        }


        // Tambahkan total harga ke kolom klub
        $klub = $user->klub;
        $klub->increment('total_harga', $totalHarga);


        return redirect()->back()->with('success', 'Peserta dan detail lomba berhasil ditambahkan.');
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

        $allLombaData = $kompetisi->flatMap(function ($k) {
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


        return view('add', compact('kompetisi', 'allLombaData'));
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
