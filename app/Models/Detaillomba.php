<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detaillomba extends Model
{
    protected $table = 'detail_lomba';
    protected $primaryKey = 'id';
    protected $fillable = ['lomba_id', 'seri', 'peserta_id', 'no_lintasan', 'urutan', 'catatan_waktu', 'keterangan', 'limit'];

    public function lomba()
    {
        return $this->belongsTo(Lomba::class, 'lomba_id');
    }
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public static function getParticipants()
    {
        return self::pluck('peserta_id')->toArray();
    }
}
