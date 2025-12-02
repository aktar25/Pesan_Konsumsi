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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                     // Nama Makanan (misal: Indomie Goreng)
            $table->integer('price');                   // Harga (misal: 5000)
            $table->string('image')->nullable();        // Foto makanannya (boleh kosong)
            $table->integer('stock')->default(10);      // Stok tersedia
            $table->text('description')->nullable();    // Keterangan (misal: Pedas level 5)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
