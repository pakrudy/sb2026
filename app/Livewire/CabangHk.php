<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Box;
use App\Models\Cabang;
use App\Models\RiwayatStatus;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CabangHk extends Component
{
    use WithPagination;

    public $activeTab = 'penerimaan'; // 'penerimaan' or 'pengambilan'
    public $searchBox = '';
    public $searchResi = '';

    public $selectedBarangId = null;
    public $tanggal_sampai;
    public $tanggal_diambil;

    public $lastReceivedBox = null;
    public $lastReceivedWaCount = 0;

    public function mount()
    {
        $this->tanggal_sampai = now()->format('Y-m-d\TH:i');
        $this->tanggal_diambil = now()->format('Y-m-d\TH:i');
    }

    public function closeNotification()
    {
        $this->lastReceivedBox = null;
    }

    public function terimaBox(int $boxId)
    {
        $box = Box::with(['barang', 'cabangTujuan'])->findOrFail($boxId);
        $tglSampai = $this->tanggal_sampai ? \Carbon\Carbon::parse($this->tanggal_sampai) : now();

        $box->update([
            'status' => 'sampai_di_hk',
            'tanggal_sampai' => $tglSampai,
        ]);

        $waCount = 0;
        foreach ($box->barang as $barang) {
            $barang->update([
                'status' => 'sampai_di_hk',
                'tanggal_sampai' => $tglSampai,
            ]);

            RiwayatStatus::create([
                'barang_id' => $barang->id,
                'status' => 'sampai_di_hk',
                'keterangan' => "Barang telah tiba di cabang HK: {$box->cabangTujuan->nama_cabang}",
                'user_id' => Auth::id(),
            ]);

            // Auto send WA notification to recipient
            $sent = WhatsAppService::sendBarangSampaiNotification($barang);
            if ($sent) {
                $waCount++;
            }
        }

        $this->lastReceivedBox = $box->fresh(['cabangTujuan', 'barang']);
        $this->lastReceivedWaCount = $waCount;

        session()->flash('success', "Box {$box->kode_box} beserta seluruh isi barang telah tiba di cabang HK & Notifikasi WA dikirim!");
    }

    public function terimaBarangIndividual(int $barangId)
    {
        $barang = Barang::findOrFail($barangId);
        $tglSampai = $this->tanggal_sampai ? \Carbon\Carbon::parse($this->tanggal_sampai) : now();

        $barang->update([
            'status' => 'sampai_di_hk',
            'tanggal_sampai' => $tglSampai,
        ]);

        RiwayatStatus::create([
            'barang_id' => $barang->id,
            'status' => 'sampai_di_hk',
            'keterangan' => "Barang telah tiba di cabang HK: {$barang->cabangTujuan->nama_cabang}",
            'user_id' => Auth::id(),
        ]);

        WhatsAppService::sendBarangSampaiNotification($barang);

        session()->flash('success', "Barang {$barang->kode_barang} berhasil diupdate ke status 'Sampai di HK'!");
    }

    public function prosesPengambilan(int $barangId)
    {
        $barang = Barang::findOrFail($barangId);
        $tglDiambil = $this->tanggal_diambil ? \Carbon\Carbon::parse($this->tanggal_diambil) : now();

        $barang->update([
            'status' => 'sudah_diambil',
            'tanggal_diambil' => $tglDiambil,
        ]);

        RiwayatStatus::create([
            'barang_id' => $barang->id,
            'status' => 'sudah_diambil',
            'keterangan' => "Barang telah diambil oleh penerima ({$barang->nama_penerima})",
            'user_id' => Auth::id(),
        ]);

        WhatsAppService::sendBarangDiambilNotification($barang);

        session()->flash('success', "Pengambilan barang {$barang->kode_barang} berhasil dicatat & Notifikasi WA dikirim!");
    }

    public function render()
    {
        $userCabangId = Auth::user() ? Auth::user()->cabang_id : null;

        // Box & Barang yang sedang dalam perjalanan ke HK
        $boxInTransitQuery = Box::with(['cabangTujuan', 'barang'])
            ->whereIn('status', ['dikemas', 'dalam_perjalanan']);

        if ($userCabangId) {
            $boxInTransitQuery->where('cabang_tujuan_id', $userCabangId);
        }

        $boxInTransit = $boxInTransitQuery->get();

        // Barang yang sudah di HK dan menunggu diambil
        $barangReadyQuery = Barang::with(['cabangAsal', 'cabangTujuan', 'box'])
            ->where('status', 'sampai_di_hk');

        if ($userCabangId) {
            $barangReadyQuery->where('cabang_tujuan_id', $userCabangId);
        }

        if (!empty($this->searchResi)) {
            $barangReadyQuery->where(function ($q) {
                $q->where('kode_barang', 'LIKE', '%' . $this->searchResi . '%')
                    ->orWhere('nama_penerima', 'LIKE', '%' . $this->searchResi . '%')
                    ->orWhere('no_wa_penerima', 'LIKE', '%' . $this->searchResi . '%');
            });
        }

        $barangReadyList = $barangReadyQuery->latest('tanggal_sampai')->paginate(10);

        // History barang yang sudah diambil
        $barangTakenQuery = Barang::with(['cabangAsal', 'cabangTujuan'])
            ->where('status', 'sudah_diambil');

        if ($userCabangId) {
            $barangTakenQuery->where('cabang_tujuan_id', $userCabangId);
        }

        $barangTakenList = $barangTakenQuery->latest('tanggal_diambil')->take(10)->get();

        return view('livewire.cabang-hk', [
            'boxInTransit' => $boxInTransit,
            'barangReadyList' => $barangReadyList,
            'barangTakenList' => $barangTakenList,
        ]);
    }
}
