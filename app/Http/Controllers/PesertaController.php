<?php

namespace App\Http\Controllers;
use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class PesertaController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama_peserta' => 'required|string|max:255',
        'jenis_kelamin' => 'required|in:L,P',
        'tgl_lahir' => 'required|date',
    ]);

    Peserta::create([
        'nama_peserta' => $request->nama_peserta,
        'jenis_kelamin' => $request->jenis_kelamin,
        'tgl_lahir' => $request->tgl_lahir,
        'asal_klub' => Auth::user()->klub->nama_klub,
        'klub_id' => Auth::user()->klub_id,
    ]);

    return redirect()->route('peserta.create')->with('success', 'Peserta berhasil ditambahkan.');
}
}
