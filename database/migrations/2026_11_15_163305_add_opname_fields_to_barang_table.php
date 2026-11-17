<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->text('catatan_opname')->nullable();
            $table->timestamp('tanggal_opname')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn([
                'catatan_opname',
                'tanggal_opname'
            ]);
        });
    }
};
