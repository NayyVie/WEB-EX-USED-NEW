<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_rekonsiliasi', function (Blueprint $table) {
            $table->id();

            $table->string('kode_barang');

            $table->integer('stok_fisik');
            $table->integer('stok_maximo');

            $table->integer('selisih');

            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('nilai_deviasi', 18, 2)->nullable();

            $table->text('catatan_rekonsiliasi')->nullable();

            $table->timestamp('tanggal_rekonsiliasi');

            $table->timestamps();

            $table->index('kode_barang');
            $table->index('tanggal_rekonsiliasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_rekonsiliasi');
    }
};
