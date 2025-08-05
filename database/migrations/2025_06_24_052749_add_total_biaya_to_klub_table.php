<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('klub', function (Blueprint $table) {
            $table->decimal('total_harga', 10, 2)->default(0)->after('nama_klub')->comment('Total biaya yang dikeluarkan oleh klub');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('klub', function (Blueprint $table) {
            //
        });
    }
};
