<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';

    protected $fillable = [
        'kode_cabang',
        'nama_cabang',
        'nama_pemilik',
        'tipe_lokasi',
        'kategori_cabang',
        'fee_per_kg',
        'alamat',
        'no_telp',
        'no_telp_alternatif',
        'is_active',
    ];

    protected $casts = [
        'fee_per_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'cabang_id');
    }

    public function barangAsal(): HasMany
    {
        return $this->hasMany(Barang::class, 'cabang_asal_id');
    }

    public function barangTujuan(): HasMany
    {
        return $this->hasMany(Barang::class, 'cabang_tujuan_id');
    }
}
