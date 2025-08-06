<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class peserta extends Model
{
    protected $table = 'peserta';
    protected $primaryKey = 'id';
    protected $fillable = ['nama_peserta', 'tgl_lahir', 'jenis_kelamin', 'asal_klub', 'klub_id', 'lomba_id'];
    public $timestamps = false;

    public function detail_lomba()
    {
        return $this->hasMany('App\Models\detail_lomba', 'peserta_id', 'id');
    }

    

    public function klub()
    {
        return $this->belongsTo(Klub::class, 'klub_id'); // Relasi ke tabel klub
    }

    public function lomba()
{
    return $this->belongsToMany(Lomba::class, 'detail_lomba', 'peserta_id', 'lomba_id')
                ->withPivot('no_lintasan', 'urutan', 'catatan_waktu');
}
}
