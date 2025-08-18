<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('peserta', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('klub_id')->nullable()->constrained('klub')->onDelete('cascade'); // Foreign key ke tabel klub
            $table->string('nama_peserta');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tgl_lahir');
            $table->string('asal_klub');
            $table->foreignId('lomba_id')->nullable()->constrained('lomba')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('peserta');
    }
};
