<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'ex_used_report_id',
        'judul',
        'pesan',
        'jenis',
        'dibaca_pada',
    ];

   protected function casts(): array
{
    return [
        'dibaca_pada' => 'datetime',
    ];
}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function laporan()
    {
        return $this->belongsTo(ExUsedReport::class, 'ex_used_report_id');
    }
}
