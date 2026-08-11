<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CabangSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cabang Indonesia ESB (Fee Rp 1.000 / kg)
        $esbJakarta = Cabang::create([
            'kode_cabang' => 'CBG-JKT-ESB',
            'nama_cabang' => 'Cabang Jakarta (ESB)',
            'tipe_lokasi' => 'indonesia',
            'kategori_cabang' => 'ESB',
            'fee_per_kg' => 1000.00,
            'alamat' => 'Jl. Kebon Jeruk No. 45, Jakarta Barat',
            'no_telp' => '081234567890',
        ]);

        $esbSurabaya = Cabang::create([
            'kode_cabang' => 'CBG-SBY-ESB',
            'nama_cabang' => 'Cabang Surabaya (ESB)',
            'tipe_lokasi' => 'indonesia',
            'kategori_cabang' => 'ESB',
            'fee_per_kg' => 1000.00,
            'alamat' => 'Jl. Basuki Rahmat No. 12, Surabaya',
            'no_telp' => '081298765432',
        ]);

        // 2. Cabang Indonesia ELP (Fee Rp 2.000 / kg)
        $elpBandung = Cabang::create([
            'kode_cabang' => 'CBG-BDG-ELP',
            'nama_cabang' => 'Cabang Bandung (ELP)',
            'tipe_lokasi' => 'indonesia',
            'kategori_cabang' => 'ELP',
            'fee_per_kg' => 2000.00,
            'alamat' => 'Jl. Asia Afrika No. 88, Bandung',
            'no_telp' => '081311223344',
        ]);

        $elpSemarang = Cabang::create([
            'kode_cabang' => 'CBG-SMG-ELP',
            'nama_cabang' => 'Cabang Semarang (ELP)',
            'tipe_lokasi' => 'indonesia',
            'kategori_cabang' => 'ELP',
            'fee_per_kg' => 2000.00,
            'alamat' => 'Jl. Pemuda No. 101, Semarang',
            'no_telp' => '081355667788',
        ]);

        // 3. Kantor Pusat (Indonesia Hub)
        $pusat = Cabang::create([
            'kode_cabang' => 'HUB-PUSAT',
            'nama_cabang' => 'Kantor Pusat Sorting Hub',
            'tipe_lokasi' => 'indonesia',
            'kategori_cabang' => 'PUSAT',
            'fee_per_kg' => 0.00,
            'alamat' => 'Gudang Pusat Sukses Bersama, Bandara Soekarno Hatta, Tangerang',
            'no_telp' => '0215551234',
        ]);

        // 4. Cabang HK (Destination Hubs in Hong Kong)
        $hkCentral = Cabang::create([
            'kode_cabang' => 'HK-CENTRAL',
            'nama_cabang' => 'Cabang HK - Central',
            'tipe_lokasi' => 'hongkong',
            'kategori_cabang' => 'HK',
            'fee_per_kg' => 0.00,
            'alamat' => 'Shop B, G/F, World-Wide House, Central, Hong Kong',
            'no_telp' => '+852 9123 4567',
        ]);

        $hkTst = Cabang::create([
            'kode_cabang' => 'HK-TST',
            'nama_cabang' => 'Cabang HK - Tsim Sha Tsui',
            'tipe_lokasi' => 'hongkong',
            'kategori_cabang' => 'HK',
            'fee_per_kg' => 0.00,
            'alamat' => '1st Floor, Chungking Mansions, Tsim Sha Tsui, Kowloon, Hong Kong',
            'no_telp' => '+852 9876 5432',
        ]);

        $hkCauseway = Cabang::create([
            'kode_cabang' => 'HK-CWB',
            'nama_cabang' => 'Cabang HK - Causeway Bay',
            'tipe_lokasi' => 'hongkong',
            'kategori_cabang' => 'HK',
            'fee_per_kg' => 0.00,
            'alamat' => 'Sugar Street No. 15, Causeway Bay, Hong Kong',
            'no_telp' => '+852 9555 6677',
        ]);

        // Seed Users for Demo
        // Super Admin
        User::create([
            'name' => 'Super Admin ESB',
            'email' => 'admin@esb.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'no_hp' => '081200000001',
        ]);

        // Admin Cabang Indo (ESB Jakarta)
        User::create([
            'cabang_id' => $esbJakarta->id,
            'name' => 'Admin Cabang ESB Jakarta',
            'email' => 'esb.jkt@esb.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang_indo',
            'no_hp' => '081200000002',
        ]);

        // Admin Cabang Indo (ELP Bandung)
        User::create([
            'cabang_id' => $elpBandung->id,
            'name' => 'Admin Cabang ELP Bandung',
            'email' => 'elp.bdg@esb.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang_indo',
            'no_hp' => '081200000003',
        ]);

        // Admin Kantor Pusat
        User::create([
            'cabang_id' => $pusat->id,
            'name' => 'Admin Gudang Pusat Hub',
            'email' => 'pusat@esb.com',
            'password' => Hash::make('password'),
            'role' => 'admin_pusat',
            'no_hp' => '081200000004',
        ]);

        // Admin HK Central
        User::create([
            'cabang_id' => $hkCentral->id,
            'name' => 'Admin HK Central',
            'email' => 'hk.central@esb.com',
            'password' => Hash::make('password'),
            'role' => 'admin_cabang_hk',
            'no_hp' => '+85291234567',
        ]);
    }
}
