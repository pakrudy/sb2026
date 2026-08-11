<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Database\Seeder;

class CsvBranchImporter extends Seeder
{
    public function run(): void
    {
        $filePath = base_path('gabungan_cabang.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File gabungan_cabang.csv tidak ditemukan!");
            return;
        }

        $file = fopen($filePath, 'r');
        $count = 0;

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            if (empty($row) || count($row) < 3) {
                continue;
            }

            $namaCabang = trim($row[0]);
            $namaPemilik = trim($row[1] ?? '');
            $namaPemilik = ($namaPemilik === 'NULL' || $namaPemilik === '') ? null : $namaPemilik;

            $tipeLokasi = strtolower(trim($row[2] ?? 'indonesia'));
            $kategoriCabang = strtoupper(trim($row[3] ?? 'ESB'));
            $feePerKg = (float) trim($row[4] ?? 0);
            $alamat = trim($row[5] ?? '');

            $noTelp = trim($row[6] ?? '');
            $noTelp = ($noTelp === 'NULL' || $noTelp === '') ? null : $noTelp;

            $noTelpAlt = trim($row[7] ?? '');
            $noTelpAlt = ($noTelpAlt === 'NULL' || $noTelpAlt === '') ? null : $noTelpAlt;

            // Generate clean unique kode_cabang
            $cleanName = preg_replace('/[^A-Za-z0-9]/', '', $namaCabang);
            $kodeCabang = strtoupper(substr($cleanName, 0, 15));
            if (empty($kodeCabang)) {
                $kodeCabang = 'CBG-' . ($count + 1);
            }

            $originalKode = $kodeCabang;
            $suffix = 1;
            while (Cabang::where('kode_cabang', $kodeCabang)->where('nama_cabang', '!=', $namaCabang)->exists()) {
                $kodeCabang = substr($originalKode, 0, 12) . '-' . $suffix;
                $suffix++;
            }

            Cabang::updateOrCreate(
                ['nama_cabang' => $namaCabang],
                [
                    'kode_cabang' => $kodeCabang,
                    'nama_pemilik' => $namaPemilik,
                    'tipe_lokasi' => $tipeLokasi,
                    'kategori_cabang' => $kategoriCabang,
                    'fee_per_kg' => $feePerKg,
                    'alamat' => $alamat,
                    'no_telp' => $noTelp,
                    'no_telp_alternatif' => $noTelpAlt,
                    'is_active' => true,
                ]
            );

            $count++;
        }

        fclose($file);

        $this->command->info("Berhasil mengimpor {$count} cabang dari gabungan_cabang.csv ke database MySQL!");
    }
}
