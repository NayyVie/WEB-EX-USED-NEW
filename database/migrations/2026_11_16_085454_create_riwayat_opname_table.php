<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_opname', function (Blueprint $table) {
            $table->id();

            $table->string('kode_barang');

            $table->unsignedInteger('stok_fisik_sebelum');
            $table->unsignedInteger('stok_fisik_sesudah');
            $table->unsignedInteger('stok_maximo_saat_opname');

            $table->text('catatan_opname')->nullable();

            $table->timestamp('tanggal_opname');

            $table->timestamps();

            $table->index('kode_barang');
            $table->index('tanggal_opname');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_opname');
    }
};
