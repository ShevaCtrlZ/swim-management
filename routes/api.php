<?php
use App\Models\Lomba;
use Illuminate\Support\Facades\Route;


Route::get('/lomba/{kompetisi}', function ($kompetisi) {
    return Lomba::where('kompetisi_id', $kompetisi)->get();
});