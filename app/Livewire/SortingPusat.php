<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Box;
use App\Models\Cabang;
use App\Models\RiwayatStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SortingPusat extends Component
{
    public $cabang_tujuan_id;
    public $kode_box = '';
    public $selectedBarang = [];
    public $activeBoxId = null;

    public function mount()
    {
        $firstHk = Cabang::where('tipe_lokasi', 'hongkong')->first();
        $this->cabang_tujuan_id = $firstHk ? $firstHk->id : null;
        $this->generateKodeBox();
    }

    public function updatedCabangTujuanId()
    {
        $this->generateKodeBox();
        $this->selectedBarang = [];
    }

    public function generateKodeBox()
    {
        if ($this->cabang_tujuan_id) {
            $cabang = Cabang::find($this->cabang_tujuan_id);
            $code = $cabang ? strtoupper($cabang->kode_cabang) : 'HK';
            $this->kode_box = 'BOX-' . $code . '-' . date('Ymd-His');
        }
    }

    public function createAndPackBox()
    {
        if (empty($this->selectedBarang)) {
            session()->flash('error', 'Pilih minimal 1 barang untuk dikemas ke dalam Box!');
            return;
        }

        $this->validate([
            'cabang_tujuan_id' => 'required|exists:cabang,id',
            'kode_box' => 'required|string|unique:boxes,kode_box',
        ]);

        $box = Box::create([
            'kode_box' => $this->kode_box,
            'cabang_tujuan_id' => $this->cabang_tujuan_id,
            'status' => 'dikemas',
            'tanggal_dikemas' => now(),
            'created_by' => Auth::id(),
        ]);

        // Update selected barang
        Barang::whereIn('id', $this->selectedBarang)->update([
            'box_id' => $box->id,
            'status' => 'disortir_di_pusat',
        ]);

        foreach ($this->selectedBarang as $barangId) {
            RiwayatStatus::create([
                'barang_id' => $barangId,
                'status' => 'disortir_di_pusat',
                'keterangan' => "Disortir & dikemas ke Box {$box->kode_box} di Kantor Pusat",
                'user_id' => Auth::id(),
            ]);
        }

        session()->flash('success', "Box {$box->kode_box} berhasil dibuat dengan " . count($this->selectedBarang) . " barang!");

        $this->selectedBarang = [];
        $this->generateKodeBox();
    }

    public function kirimBox(int $boxId)
    {
        $box = Box::with('barang')->findOrFail($boxId);
        $box->update([
            'status' => 'dalam_perjalanan',
            'tanggal_dikirim' => now(),
        ]);

        foreach ($box->barang as $barang) {
            $barang->update(['status' => 'dalam_pengiriman_hk']);
            RiwayatStatus::create([
                'barang_id' => $barang->id,
                'status' => 'dalam_pengiriman_hk',
                'keterangan' => "Dalam pengiriman udara/laut ke Hong Kong (Box: {$box->kode_box})",
                'user_id' => Auth::id(),
            ]);
        }

        session()->flash('success', "Box {$box->kode_box} berhasil dikirim ke Hong Kong!");
    }

    public function render()
    {
        $cabangHk = Cabang::where('tipe_lokasi', 'hongkong')->get();

        // Barang yang belum masuk box & cabang tujuannya sesuai filter
        $barangAvailable = Barang::with(['cabangAsal', 'cabangTujuan'])
            ->whereNull('box_id')
            ->where('cabang_tujuan_id', $this->cabang_tujuan_id)
            ->get();

        $boxActive = Box::with(['cabangTujuan', 'barang'])
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.sorting-pusat', [
            'cabangHk' => $cabangHk,
            'barangAvailable' => $barangAvailable,
            'boxActive' => $boxActive,
        ]);
    }
}
