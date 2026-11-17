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
    Schema::create('ex_used_reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();

        // Step 1
        $table->dateTime('tanggal_laporan');
        $table->string('status_lokasi')->nullable();
        $table->string('bidang');

        // Step 2
        $table->string('nomor_wo');
        $table->string('nama_pekerjaan');
        $table->string('item_number');
        $table->string('nama_barang');
        $table->string('jenis_pengembalian');
        $table->string('kondisi_barang');
        $table->string('material_base');
        $table->string('lokasi_gudang');
        $table->unsignedInteger('jumlah_barang');
        $table->string('satuan');
        $table->decimal('estimasi', 12, 2);

        // Step 3
        $table->string('status_kondisi');
        $table->string('foto_bukti');
        $table->text('deskripsi')->nullable();
        $table->longText('tanda_tangan')->nullable();
        $table->string('status_laporan')->default('Menunggu Verifikasi');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ex_used_reports');
    }
};
