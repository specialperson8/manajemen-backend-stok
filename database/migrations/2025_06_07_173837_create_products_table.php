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
    Schema::create('products', function (Blueprint $table) { // <-- UBAH DI SINI
        $table->id();
        $table->string('nama_produk', 100);
        $table->string('kode_produk', 20)->unique();
        $table->integer('harga');
        $table->integer('stok');
        $table->text('deskripsi')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('products'); // <-- UBAH DI SINI
}
};
