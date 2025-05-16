<?php

namespace App\Http\Controllers;
use App\Models\DetailLomba;
use App\Models\Lomba;
use App\Models\Kompetisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportPdfController extends Controller
{
    public function hasilKompetisi($id)
    {
        $kompetisi = Kompetisi::with(['lomba.detailLomba.peserta'])->findOrFail($id);
$lomba = $kompetisi->lomba;

return PDF::loadView('export.hasil_pdf', compact('kompetisi', 'lomba'))
           ->download('hasil_kompetisi.pdf');
    }
}
