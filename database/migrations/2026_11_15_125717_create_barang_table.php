<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();

            // Data dari Excel
            $table->string('kode_barang')->unique();
            $table->text('deskripsi');

            // Data tambahan untuk pengembangan katalog
            $table->string('nama_barang')->nullable();
            $table->string('kategori')->nullable();
            $table->string('lokasi_rak')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('stok_fisik')->default(0);
            $table->integer('stok_maximo')->default(0);
            $table->decimal('harga_satuan', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};