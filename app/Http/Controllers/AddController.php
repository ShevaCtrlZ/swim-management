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
        // Validasi data yang dikirimkan
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tgl_lahir' => 'required|date',
            'limit' => 'required',
            'lomba_id' => 'required|exists:lomba,id', // Pastikan lomba_id valid
        ]);

        $user = Auth::user();
        $asal_klub = $user->klub->nama_klub ?? 'Unknown'; // Default to 'Unknown' if nama_klub is null

        // Simpan data peserta ke database
        $peserta = peserta::create([
            'nama_peserta' => $request->nama_peserta,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_lahir' => $request->tgl_lahir,
            'asal_klub' => $asal_klub, // Ambil nama klub dari user yang login
            'limit' => $request->limit,
            'klub_id' => Auth::user()->klub_id, // Ambil klub_id dari user yang login
            'lomba_id' => $request->lomba_id,
        ]);

        $detailLomba = Detaillomba::create([
            'lomba_id' => $request->lomba_id,
            'peserta_id' => $peserta->id,
            'no_lintasan' => $request->no_lintasan ?? null,
            'urutan' => null,
            'catatan_waktu' => null,
        ]);

        // Redirect kembali ke halaman tambah peserta dengan pesan sukses
        return redirect()->route('add')->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function list()
    {
        $data = peserta::paginate(10);
        return view('list', compact('data'));
    }

    public function atlet()
    {
        // Ambil semua data peserta dari database
        $data = peserta::all();

        // Kirim data ke view
        return view('atlet', compact('data'));
    }

    public function create()
    {
        // Ambil semua kompetisi beserta lomba yang terkait
        $kompetisi = Kompetisi::with('lomba')->get();

        // Kirim data kompetisi ke view
        return view('add', compact('kompetisi'));
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
