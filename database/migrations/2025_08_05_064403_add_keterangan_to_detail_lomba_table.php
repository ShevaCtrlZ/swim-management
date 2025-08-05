<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('detail_lomba', function (Blueprint $table) {
            $table->string('keterangan')->nullable()->after('catatan_waktu');
        });
    }

    public function down()
    {
        Schema::table('detail_lomba', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });
    }
};
