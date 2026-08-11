<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Box extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_box',
        'cabang_tujuan_id',
        'status',
        'tanggal_dikemas',
        'tanggal_dikirim',
        'tanggal_sampai',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'tanggal_dikemas' => 'datetime',
        'tanggal_dikirim' => 'datetime',
        'tanggal_sampai' => 'datetime',
    ];

    public function cabangTujuan(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_tujuan_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function barang(): HasMany
    {
        return $this->hasMany(Barang::class, 'box_id');
    }
}
