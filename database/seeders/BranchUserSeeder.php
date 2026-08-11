<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BranchUserSeeder extends Seeder
{
    public function run(): void
    {
        $cabangs = Cabang::all();
        $createdCount = 0;

        foreach ($cabangs as $cabang) {
            // Check if user already exists for this branch
            $existingUser = User::where('cabang_id', $cabang->id)->first();
            if ($existingUser) {
                continue;
            }

            // Generate clean username / email
            $cleanName = Str::slug($cabang->nama_cabang, '.');
            $baseEmail = $cleanName . '@esb.com';
            $email = $baseEmail;

            $suffix = 1;
            while (User::where('email', $email)->exists()) {
                $email = $cleanName . $suffix . '@esb.com';
                $suffix++;
            }

            // Determine role
            if ($cabang->tipe_lokasi === 'hongkong') {
                $role = 'admin_cabang_hk';
            } elseif ($cabang->kategori_cabang === 'PUSAT') {
                $role = 'admin_pusat';
            } else {
                $role = 'admin_cabang_indo';
            }

            $userName = $cabang->nama_pemilik ? "{$cabang->nama_pemilik} ({$cabang->nama_cabang})" : "Admin {$cabang->nama_cabang}";

            User::create([
                'cabang_id' => $cabang->id,
                'name' => $userName,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => $role,
                'no_hp' => $cabang->no_telp ?: '081234567890',
                'is_active' => true,
            ]);

            $createdCount++;
        }

        $this->command->info("Berhasil membuat {$createdCount} akun pengguna (user) baru untuk cabang!");
    }
}
