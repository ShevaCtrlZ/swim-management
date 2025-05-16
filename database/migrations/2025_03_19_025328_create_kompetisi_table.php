<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kompetisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kompetisi');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->string('lokasi');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('kompetisi');
    }
};
