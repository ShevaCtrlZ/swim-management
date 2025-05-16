<?php

namespace App\Http\Controllers;
use App\Models\Kompetisi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HasilKompetisiExport;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportExcel($id)
    {
        return Excel::download(new HasilKompetisiExport($id), 'hasil_kompetisi.xlsx');
    }
}
