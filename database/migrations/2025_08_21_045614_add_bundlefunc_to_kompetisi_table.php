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
        Schema::table('kompetisi', function (Blueprint $table) {
            $table->decimal('harga_bundling', 10, 2)->nullable();
            $table->integer('syarat_bundling')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kompetisi', function (Blueprint $table) {
            //
        });
    }
};
