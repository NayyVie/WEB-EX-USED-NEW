<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ex_used_reports', function (Blueprint $table) {
            $table->string('tanda_tangan_admin')->nullable();
            $table->foreignId('admin_verifikator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('tanggal_verifikasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ex_used_reports', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_verifikator_id');
            $table->dropColumn([
                'tanda_tangan_admin',
                'tanggal_verifikasi',
            ]);
        });
    }
};