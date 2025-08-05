<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_lomba', function (Blueprint $table) {
            $table->string('catatan_waktu')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('detail_lomba', function (Blueprint $table) {
            $table->time('catatan_waktu')->nullable()->change(); // asumsi sebelumnya time
        });
    }
};
