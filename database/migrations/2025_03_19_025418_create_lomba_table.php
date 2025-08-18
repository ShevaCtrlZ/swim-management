<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lomba', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kompetisi_id')->constrained('kompetisi')->onDelete('cascade');
            $table->integer('jarak');
            $table->string('jenis_gaya');
            $table->integer('jumlah_lintasan');
            $table->integer('nomor_lomba')->nullable();
            $table->string('tahun_lahir_minimal');
            $table->string('tahun_lahir_maksimal');
            $table->string('jk');
            $table->decimal('harga', 10, 2);
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('lomba');
    }
};
