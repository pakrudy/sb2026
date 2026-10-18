<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeKategori extends Model
{
    use HasFactory;

    protected $table = 'fee_kategori';

    protected $fillable = [
        'kategori',
        'fee_per_kg',
        'keterangan',
        'updated_by',
    ];

    protected $casts = [
        'fee_per_kg' => 'decimal:2',
    ];

    /**
     * Ambil fee standar untuk kategori tertentu.
     * Fallback ke nilai default jika belum ada di DB.
     */
    public static function getFeeUntukKategori(string $kategori): float
    {
        $defaults = ['ESB' => 1000, 'ELP' => 2000];
        $record = static::where('kategori', $kategori)->first();
        return $record ? (float) $record->fee_per_kg : ($defaults[$kategori] ?? 0);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
