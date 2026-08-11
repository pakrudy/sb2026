<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Cabang;
use App\Models\RiwayatStatus;
use App\Models\WaLog;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InputBarang extends Component
{
    public $cabang_asal_id;
    public $cabang_tujuan_id;
    public $nama_pengirim = '';
    public $no_wa_pengirim = '';
    public $nama_penerima = '';
    public $no_wa_penerima = '';
    public $berat_kg = 1.0;
    public $biaya_kirim = 0;
    public $catatan = '';

    public $fee_per_kg = 1000;
    public $kategori_cabang = 'ESB';
    public $fee_cabang = 1000;
    
    public $lastInsertedBarang = null;
    public $lastWaLog = null;
    public $lastWaStatus = false;
    public $isCabangLocked = false;

    protected $rules = [
        'cabang_asal_id' => 'required|exists:cabang,id',
        'cabang_tujuan_id' => 'required|exists:cabang,id',
        'nama_pengirim' => 'required|string|max:150',
        'no_wa_pengirim' => 'required|string|max:30',
        'nama_penerima' => 'required|string|max:150',
        'no_wa_penerima' => 'required|string|max:30',
        'berat_kg' => 'required|numeric|min:0.1',
        'biaya_kirim' => 'required|numeric|min:0',
        'catatan' => 'nullable|string',
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->cabang_id) {
            $this->cabang_asal_id = $user->cabang_id;
            if (!$user->isSuperAdmin()) {
                $this->isCabangLocked = true;
            }
        } else {
            $firstIndo = Cabang::where('tipe_lokasi', 'indonesia')->first();
            $this->cabang_asal_id = $firstIndo ? $firstIndo->id : null;
        }

        $firstHk = Cabang::where('tipe_lokasi', 'hongkong')->first();
        $this->cabang_tujuan_id = $firstHk ? $firstHk->id : null;

        $this->calculateFee();
    }

    public function updatedCabangAsalId()
    {
        if ($this->isCabangLocked && Auth::user()->cabang_id) {
            $this->cabang_asal_id = Auth::user()->cabang_id;
        }
        $this->calculateFee();
    }

    public function updatedBeratKg()
    {
        $this->calculateFee();
    }

    public function calculateFee()
    {
        if ($this->cabang_asal_id) {
            $cabang = Cabang::find($this->cabang_asal_id);
            if ($cabang) {
                $this->kategori_cabang = $cabang->kategori_cabang;
                $this->fee_per_kg = (float) $cabang->fee_per_kg;
            }
        }
        $berat = (float) ($this->berat_kg ?: 0);
        $this->fee_cabang = $berat * $this->fee_per_kg;

        if ($this->biaya_kirim == 0 && $berat > 0) {
            $this->biaya_kirim = $berat * 50000;
        }
    }

    public function closeNotification()
    {
        $this->lastInsertedBarang = null;
        $this->lastWaLog = null;
    }

    public function simpan()
    {
        $user = Auth::user();
        if ($this->isCabangLocked && $user && $user->cabang_id) {
            $this->cabang_asal_id = $user->cabang_id;
        }

        $this->validate();

        $cabangAsal = Cabang::findOrFail($this->cabang_asal_id);
        $kodeBarang = Barang::generateKodeBarang($cabangAsal);

        $this->calculateFee();

        $barang = Barang::create([
            'kode_barang' => $kodeBarang,
            'cabang_asal_id' => $this->cabang_asal_id,
            'cabang_tujuan_id' => $this->cabang_tujuan_id,
            'nama_pengirim' => $this->nama_pengirim,
            'no_wa_pengirim' => $this->no_wa_pengirim,
            'nama_penerima' => $this->nama_penerima,
            'no_wa_penerima' => $this->no_wa_penerima,
            'berat_kg' => $this->berat_kg,
            'biaya_kirim' => $this->biaya_kirim,
            'fee_cabang' => $this->fee_cabang,
            'kategori_cabang_snapshot' => $this->kategori_cabang,
            'tanggal_pengiriman' => now(),
            'status' => 'diterima_di_cabang',
            'catatan' => $this->catatan,
        ]);

        RiwayatStatus::create([
            'barang_id' => $barang->id,
            'status' => 'diterima_di_cabang',
            'keterangan' => 'Barang diterima di ' . $cabangAsal->nama_cabang,
            'user_id' => Auth::id(),
        ]);

        // Send WhatsApp notification
        $this->lastWaStatus = WhatsAppService::sendBarangDiterimaNotification($barang);
        $this->lastWaLog = WaLog::where('barang_id', $barang->id)->latest()->first();

        $this->lastInsertedBarang = $barang->fresh(['cabangAsal', 'cabangTujuan']);

        // Reset inputs
        $this->reset(['nama_pengirim', 'no_wa_pengirim', 'nama_penerima', 'no_wa_penerima', 'catatan']);
        $this->berat_kg = 1.0;
        $this->biaya_kirim = 50000;
        $this->calculateFee();
    }

    public function render()
    {
        $cabangIndo = Cabang::where('tipe_lokasi', 'indonesia')->get();
        $cabangHk = Cabang::where('tipe_lokasi', 'hongkong')->get();

        return view('livewire.input-barang', [
            'cabangIndo' => $cabangIndo,
            'cabangHk' => $cabangHk,
        ]);
    }
}
