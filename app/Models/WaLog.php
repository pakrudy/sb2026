<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaLog extends Model
{
    use HasFactory;

    protected $table = 'wa_logs';

    protected $fillable = [
        'barang_id',
        'no_wa',
        'jenis_pesan',
        'isi_pesan',
        'status_kirim',
        'response_vendor',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}
