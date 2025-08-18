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
        Schema::create('klub', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('nama_klub');
            $table->decimal('total_harga', 10, 2)->default(0)->comment('Total biaya yang dikeluarkan oleh klub');
            $table->string('alamat')->nullable();
            $table->string('kontak')->nullable();
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('klub');
    }
};
