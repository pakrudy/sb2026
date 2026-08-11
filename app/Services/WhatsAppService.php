<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\WaLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WA notification to recipient when item is received at branch in Indonesia.
     */
    public static function sendBarangDiterimaNotification(Barang $barang): bool
    {
        $noWa = self::formatPhoneNumber($barang->no_wa_penerima);
        $cabangTujuan = $barang->cabangTujuan ? $barang->cabangTujuan->nama_cabang : 'Cabang Hongkong';
        $tanggalKirim = $barang->tanggal_pengiriman ? $barang->tanggal_pengiriman->format('d/m/Y H:i') : date('d/m/Y H:i');
        $biayaFmt = 'Rp ' . number_format($barang->biaya_kirim, 0, ',', '.');
        $beratFmt = number_format($barang->berat_kg, 2, ',', '.') . ' kg';

        $pesan = "*[EKSPEDISI SUKSES BERSAMA]*\n\n"
            . "Halo *" . $barang->nama_penerima . "*,\n"
            . "Paket Anda telah kami terima di cabang asal dan siap diproses ke Hong Kong.\n\n"
            . "📦 *Detail Pengiriman:*\n"
            . "• Kode Barang: *" . $barang->kode_barang . "*\n"
            . "• Area Ambil (HK): *" . $cabangTujuan . "*\n"
            . "• Tanggal Kirim: " . $tanggalKirim . "\n"
            . "• Berat Paket: " . $beratFmt . "\n"
            . "• Biaya Kirim: *" . $biayaFmt . "*\n\n"
            . "Lacak status paket Anda melalui website Ekspedisi Sukses Bersama.\n"
            . "Terima kasih telah mempercayakan pengiriman kepada kami!";

        return self::sendMessage($noWa, $pesan, 'diterima_di_cabang', $barang->id);
    }

    /**
     * Send notification when package arrives at HK Branch.
     */
    public static function sendBarangSampaiNotification(Barang $barang): bool
    {
        $noWa = self::formatPhoneNumber($barang->no_wa_penerima);
        $cabangTujuan = $barang->cabangTujuan ? $barang->cabangTujuan->nama_cabang : 'Cabang Hongkong';
        $alamatTujuan = $barang->cabangTujuan ? $barang->cabangTujuan->alamat : '';

        $pesan = "*[EKSPEDISI SUKSES BERSAMA]*\n\n"
            . "Halo *" . $barang->nama_penerima . "*,\n"
            . "🎉 Paket Anda *" . $barang->kode_barang . "* telah *SAMPAI* di Hong Kong!\n\n"
            . "📍 *Lokasi Pengambilan:*\n"
            . "• Cabang: *" . $cabangTujuan . "*\n"
            . ($alamatTujuan ? "• Alamat: " . $alamatTujuan . "\n" : "")
            . "\nSilakan datang ke cabang tujuan untuk mengambil paket Anda.\n"
            . "Terima kasih!";

        return self::sendMessage($noWa, $pesan, 'sampai_di_hk', $barang->id);
    }

    /**
     * Send notification when package is picked up.
     */
    public static function sendBarangDiambilNotification(Barang $barang): bool
    {
        $noWa = self::formatPhoneNumber($barang->no_wa_penerima);
        $tanggalAmbil = $barang->tanggal_diambil ? $barang->tanggal_diambil->format('d/m/Y H:i') : date('d/m/Y H:i');

        $pesan = "*[EKSPEDISI SUKSES BERSAMA]*\n\n"
            . "Halo *" . $barang->nama_penerima . "*,\n"
            . "Paket *" . $barang->kode_barang . "* telah *DIAMBIL* pada " . $tanggalAmbil . ".\n\n"
            . "Terima kasih telah menggunakan jasa pengiriman Ekspedisi Sukses Bersama!";

        return self::sendMessage($noWa, $pesan, 'sudah_diambil', $barang->id);
    }

    /**
     * Core dispatch message function.
     */
    public static function sendMessage(string $noWa, string $pesan, string $jenisPesan, ?int $barangId = null): bool
    {
        $waLog = WaLog::create([
            'barang_id' => $barangId,
            'no_wa' => $noWa,
            'jenis_pesan' => $jenisPesan,
            'isi_pesan' => $pesan,
            'status_kirim' => 'pending',
        ]);

        $apiKey = env('WA_API_KEY');
        $apiUrl = env('WA_API_URL', 'https://api.fonnte.com/send');

        // If no API key configured, log locally and mark sent for dev
        if (empty($apiKey)) {
            Log::info("WA Notification [DEV MODE] to $noWa:\n$pesan");
            $waLog->update([
                'status_kirim' => 'sent',
                'response_vendor' => 'DEV_MODE_SIMULATED_SUCCESS',
            ]);
            return true;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $apiKey,
            ])->post($apiUrl, [
                'target' => $noWa,
                'message' => $pesan,
            ]);

            if ($response->successful()) {
                $waLog->update([
                    'status_kirim' => 'sent',
                    'response_vendor' => $response->body(),
                ]);
                return true;
            } else {
                $waLog->update([
                    'status_kirim' => 'failed',
                    'response_vendor' => $response->body(),
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WA Gateway Error: " . $e->getMessage());
            $waLog->update([
                'status_kirim' => 'failed',
                'response_vendor' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Format phone number to international standard (e.g. 0812xxx -> 62812xxx or +852 -> 852)
     */
    public static function formatPhoneNumber(string $number): string
    {
        $clean = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($clean, '0')) {
            return '62' . substr($clean, 1);
        }

        return $clean;
    }
}
