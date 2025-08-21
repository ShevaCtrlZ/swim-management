<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klub extends Model
{
    use HasFactory;

    protected $table = 'klub';

    protected $fillable = [
        'nama_klub',
        'alamat',
        'kontak',
        'total_harga',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($klub) {
            // Ambil ID terakhir dan tambahkan 1
            $lastKlub = Klub::latest('id')->first();
            $nextId = $lastKlub ? $lastKlub->id + 1 : 1;

            // Set ID klub secara otomatis
            $klub->id = $nextId;
        });
    }

    // Relasi ke tabel users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function peserta()
    {
        return $this->hasMany(Peserta::class, 'klub_id'); // Relasi ke tabel peserta
    }
}
