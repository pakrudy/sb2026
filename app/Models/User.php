<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'cabang_id',
        'name',
        'email',
        'password',
        'role',
        'no_hp',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function cabang(): BelongsTo
    {
        return $table = $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminCabangIndo(): bool
    {
        return $this->role === 'admin_cabang_indo';
    }

    public function isAdminPusat(): bool
    {
        return $this->role === 'admin_pusat';
    }

    public function isAdminCabangHk(): bool
    {
        return $this->role === 'admin_cabang_hk';
    }
}
