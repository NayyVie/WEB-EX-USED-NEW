<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExUsedReport extends Model
{
    use HasFactory;

    protected $table = 'ex_used_reports';

    
protected $fillable = [
    'user_id',
    'tanggal_laporan',
    'status_lokasi',
    'bidang',
    'nomor_wo',
    'nama_pekerjaan',
    'item_number',
    'nama_barang',
    'jenis_pengembalian',
    'kondisi_barang',
    'material_base',
    'lokasi_gudang',
    'jumlah_barang',
    'satuan',
    'estimasi',
    'status_kondisi',
    'foto_bukti',
    'deskripsi',
    'tanda_tangan',
    'status_laporan',
    'alasan_penolakan',
    'created_by',
    'sumber_input',

    // Data persetujuan Admin
    'tanda_tangan_admin',
    'admin_verifikator_id',
    'tanggal_verifikasi',
];


    protected function casts(): array
{
    return [
        'tanggal_laporan' => 'datetime',
        // 'estimasi' => 'decimal:2',
    ];
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function pembuat()
{
    return $this->belongsTo(User::class, 'created_by');
}

}