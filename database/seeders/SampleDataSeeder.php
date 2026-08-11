<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Box;
use App\Models\Cabang;
use App\Models\RiwayatStatus;
use App\Services\WhatsAppService;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $esbJkt = Cabang::where('kode_cabang', 'CBG-JKT-ESB')->first();
        $elpBdg = Cabang::where('kode_cabang', 'CBG-BDG-ELP')->first();
        $hkCentral = Cabang::where('kode_cabang', 'HK-CENTRAL')->first();
        $hkTst = Cabang::where('kode_cabang', 'HK-TST')->first();

        if (!$esbJkt || !$elpBdg || !$hkCentral || !$hkTst) {
            return;
        }

        // Item 1: Received at ESB Jakarta (Fee = 3.5kg * 1000 = 3500)
        $b1 = Barang::create([
            'kode_barang' => 'ESB-' . date('Ymd') . '-0001',
            'cabang_asal_id' => $esbJkt->id,
            'cabang_tujuan_id' => $hkCentral->id,
            'nama_pengirim' => 'Ani Wijaya',
            'no_wa_pengirim' => '081234567890',
            'nama_penerima' => 'Siti Nurhaliza',
            'no_wa_penerima' => '+85291234567',
            'berat_kg' => 3.50,
            'biaya_kirim' => 175000,
            'fee_cabang' => 3500,
            'kategori_cabang_snapshot' => 'ESB',
            'tanggal_pengiriman' => now()->subHours(5),
            'status' => 'diterima_di_cabang',
            'catatan' => 'Pakaian & kerajinan tangan',
        ]);

        RiwayatStatus::create([
            'barang_id' => $b1->id,
            'status' => 'diterima_di_cabang',
            'keterangan' => 'Barang diterima di Cabang Jakarta (ESB)',
            'created_at' => now()->subHours(5),
        ]);

        // Item 2: Received at ELP Bandung (Fee = 5.0kg * 2000 = 10000)
        $b2 = Barang::create([
            'kode_barang' => 'ELP-' . date('Ymd') . '-0002',
            'cabang_asal_id' => $elpBdg->id,
            'cabang_tujuan_id' => $hkTst->id,
            'nama_pengirim' => 'Budi Santoso',
            'no_wa_pengirim' => '081399887766',
            'nama_penerima' => 'Dewi Rahmawati',
            'no_wa_penerima' => '+85298765432',
            'berat_kg' => 5.00,
            'biaya_kirim' => 250000,
            'fee_cabang' => 10000,
            'kategori_cabang_snapshot' => 'ELP',
            'tanggal_pengiriman' => now()->subDays(1),
            'status' => 'disortir_di_pusat',
            'catatan' => 'Makanan kering & camilan',
        ]);

        RiwayatStatus::create([
            'barang_id' => $b2->id,
            'status' => 'diterima_di_cabang',
            'keterangan' => 'Barang diterima di Cabang Bandung (ELP)',
            'created_at' => now()->subDays(1),
        ]);

        // Box 1 for HK Tsim Sha Tsui
        $box = Box::create([
            'kode_box' => 'BOX-HK-TST-' . date('Ymd') . '-001',
            'cabang_tujuan_id' => $hkTst->id,
            'status' => 'dikemas',
            'tanggal_dikemas' => now()->subHours(3),
        ]);

        $b2->update(['box_id' => $box->id]);

        RiwayatStatus::create([
            'barang_id' => $b2->id,
            'status' => 'disortir_di_pusat',
            'keterangan' => "Disortir & dikemas ke Box {$box->kode_box} di Gudang Pusat",
            'created_at' => now()->subHours(3),
        ]);

        // Item 3: Arrived in HK & Picked Up
        $b3 = Barang::create([
            'kode_barang' => 'ESB-' . date('Ymd', strtotime('-2 days')) . '-0003',
            'cabang_asal_id' => $esbJkt->id,
            'cabang_tujuan_id' => $hkCentral->id,
            'nama_pengirim' => 'Rina Kartika',
            'no_wa_pengirim' => '081255443322',
            'nama_penerima' => 'Maya Indah',
            'no_wa_penerima' => '+85295556677',
            'berat_kg' => 2.00,
            'biaya_kirim' => 100000,
            'fee_cabang' => 2000,
            'kategori_cabang_snapshot' => 'ESB',
            'tanggal_pengiriman' => now()->subDays(3),
            'tanggal_sampai' => now()->subDays(1),
            'tanggal_diambil' => now()->subHours(2),
            'status' => 'sudah_diambil',
            'catatan' => 'Dokumen & aksesoris',
        ]);

        RiwayatStatus::create([
            'barang_id' => $b3->id,
            'status' => 'diterima_di_cabang',
            'keterangan' => 'Barang diterima di Cabang Jakarta (ESB)',
            'created_at' => now()->subDays(3),
        ]);

        RiwayatStatus::create([
            'barang_id' => $b3->id,
            'status' => 'sampai_di_hk',
            'keterangan' => 'Tiba di Cabang HK Central',
            'created_at' => now()->subDays(1),
        ]);

        RiwayatStatus::create([
            'barang_id' => $b3->id,
            'status' => 'sudah_diambil',
            'keterangan' => 'Sudah diambil oleh penerima Maya Indah',
            'created_at' => now()->subHours(2),
        ]);
    }
}
