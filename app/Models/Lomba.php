<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lomba extends Model
{
    protected $table = 'lomba';
    protected $primaryKey = 'id';
    protected $fillable = ['kompetisi_id', 'jarak', 'jenis_gaya', 'jumlah_lintasan', 'nomor_lomba', 'tahun_lahir_minimal', 'tahun_lahir_maksimal', 'jk'];
    public $timestamps = false;

    public function kompetisi()
    {
        return $this->belongsTo(Kompetisi::class, 'kompetisi_id');
    }

    public function detailLomba()
    {
        return $this->hasMany(DetailLomba::class, 'lomba_id');
    }



    public function nomorLomba()
{
    return $this->hasMany(DetailLomba::class, 'lomba_id');
}

    public function peserta()
{
    return $this->belongsToMany(Peserta::class, 'detail_lomba', 'lomba_id', 'peserta_id')
                ->withPivot('no_lintasan', 'urutan', 'catatan_waktu');
}

}
