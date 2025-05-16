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
        Schema::table('peserta', function (Blueprint $table) {
            $table->unsignedBigInteger('klub_id')->nullable()->after('id'); // Tambahkan kolom klub_id
            $table->foreign('klub_id')->references('id')->on('klub')->onDelete('cascade'); // Relasi ke tabel klub
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta', function (Blueprint $table) {
            $table->dropForeign(['klub_id']);
            $table->dropColumn('klub_id');
        });
    }
};
