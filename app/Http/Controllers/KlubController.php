<?php

namespace App\Http\Controllers;

use App\Models\Klub;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\peserta;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class KlubController extends Controller
{
    public function registerKlub(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_klub' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'kontak' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat data klub
        $klub = Klub::create([
            'nama_klub' => $request->nama_klub,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
        ]);

        // Buat akun user untuk klub
        User::create([
            'name' => $request->nama_klub,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'klub_id' => $klub->id,
            'role' => 'klub', // Atau sesuai dengan role yang diinginkan
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Akun klub berhasil dibuat.');
    }

    public function showRegisterForm()
    {
        return view('register_klub');
    }

    public function index()
    {
        $klubs = Klub::withCount('peserta')->get();
        $totalPeserta = peserta::count();  // Mengambil semua data klub
        return view('klub', compact('klubs'));
    }

    public function info()
    {
        $user = Auth::user(); // Get the authenticated user
        $klub = $user->klub; // Get the klub associated with the user

        if (!$klub) {
            // Handle the case where the user does not have an associated klub
            return redirect()->route('klub')->with('error', 'Anda belum terdaftar dalam klub manapun.');
        }

        $anggota = $klub->peserta; // Get the peserta associated with the klub

        return view('klub.info', compact('klub', 'anggota'));
    }

    // Edit Klub
public function edit($id)
{
    $klub = Klub::findOrFail($id);
    return view('edit_klub', compact('klub'));
}

// Update Klub
public function update(Request $request, $id)
{
    $request->validate([
        'nama_klub' => 'required|string|max:255',
        'alamat' => 'required|string',
        'kontak' => 'required|string',
    ]);

    $klub = Klub::findOrFail($id);
    $klub->update($request->only('nama_klub', 'alamat', 'kontak'));

    return redirect()->route('klub')->with('success', 'Data klub berhasil diperbarui.');
}

// Hapus Klub
public function destroy($id)
{
    $klub = Klub::findOrFail($id);
    $klub->delete();

    return redirect()->route('klub')->with('success', 'Klub berhasil dihapus.');
}

}
