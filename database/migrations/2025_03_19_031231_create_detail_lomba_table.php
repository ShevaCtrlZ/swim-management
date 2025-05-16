<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lomba_id')->constrained('lomba')->onDelete('cascade');
            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->integer('no_lintasan');
            $table->integer('urutan');
            $table->time('catatan_waktu')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('detail_lomba');
    }
};
