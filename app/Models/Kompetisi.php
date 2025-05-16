<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kompetisi extends Model
{
    protected $table = 'kompetisi';
    protected $primaryKey = 'id';   
    protected $fillable = ['nama_kompetisi', 'tgl_mulai', 'tgl_selesai', 'lokasi'];
    public $timestamps = false;

    public function lomba()
    {
        return $this->hasMany(Lomba::class, 'kompetisi_id');
    }
    
}


