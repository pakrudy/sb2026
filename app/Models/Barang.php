<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'cabang_asal_id',
        'cabang_tujuan_id',
        'box_id',
        'nama_pengirim',
        'no_wa_pengirim',
        'nama_penerima',
        'no_wa_penerima',
        'berat_kg',
        'biaya_kirim',
        'fee_cabang',
        'kategori_cabang_snapshot',
        'tanggal_pengiriman',
        'tanggal_sampai',
        'tanggal_diambil',
        'status',
        'catatan',
    ];

    protected $casts = [
        'berat_kg' => 'decimal:2',
        'biaya_kirim' => 'decimal:2',
        'fee_cabang' => 'decimal:2',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_sampai' => 'datetime',
        'tanggal_diambil' => 'datetime',
    ];

    public function cabangAsal(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_asal_id');
    }

    public function cabangTujuan(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_tujuan_id');
    }

    public function box(): BelongsTo
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class, 'barang_id')->orderBy('created_at', 'desc');
    }

    public function waLogs(): HasMany
    {
        return $this->hasMany(WaLog::class, 'barang_id');
    }

    public static function generateKodeBarang(Cabang $cabangAsal): string
    {
        $prefix = strtoupper($cabangAsal->kategori_cabang) . '-' . date('Ymd');
        $last = self::where('kode_barang', 'LIKE', $prefix . '-%')->latest('id')->first();
        if ($last) {
            $number = (int) substr($last->kode_barang, strrpos($last->kode_barang, '-') + 1) + 1;
        } else {
            $number = 1;
        }
        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
