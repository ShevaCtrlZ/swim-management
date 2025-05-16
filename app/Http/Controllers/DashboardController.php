<?php

namespace App\Http\Controllers;

use App\Models\peserta;
use App\Models\Kompetisi;
use App\Models\Klub;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $jumlahPeserta = Peserta::count();
        $jumlahKompetisi = Kompetisi::count();
        $jumlahKlub = Klub::count();

        return view('dashboard', compact('jumlahPeserta', 'jumlahKompetisi', 'jumlahKlub'));
    }
}
