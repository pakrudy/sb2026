<?php

namespace App\Livewire;

use App\Models\Barang;
use App\Models\Cabang;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DaftarBarang extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterCabangAsal = '';
    public $filterKategori = '';
    public $isCabangLocked = false;
    public $isKategoriLocked = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterCabangAsal' => ['except' => ''],
        'filterKategori' => ['except' => ''],
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->cabang_id && !$user->isSuperAdmin()) {
            $cabang = $user->cabang;
            // Only lock origin branch filter for Indonesia origin branch admins
            if ($cabang && $cabang->tipe_lokasi === 'indonesia') {
                $this->filterCabangAsal = $user->cabang_id;
                $this->isCabangLocked = true;

                if (in_array($cabang->kategori_cabang, ['ESB', 'ELP'])) {
                    $this->filterKategori = $cabang->kategori_cabang;
                    $this->isKategoriLocked = true;
                }
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterCabangAsal()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();
        $query = Barang::with(['cabangAsal', 'cabangTujuan', 'box']);

        if ($user && $user->cabang_id && !$user->isSuperAdmin()) {
            $cabang = $user->cabang;
            if ($cabang && $cabang->tipe_lokasi === 'indonesia') {
                // Admin Cabang Asal (Indo): filter by origin branch
                $query->where('cabang_asal_id', $user->cabang_id);
            } elseif ($cabang && $cabang->tipe_lokasi === 'hongkong') {
                // Admin Cabang Tujuan (HK): filter strictly by HK destination branch
                $query->where('cabang_tujuan_id', $user->cabang_id);

                if (!empty($this->filterCabangAsal)) {
                    $query->where('cabang_asal_id', $this->filterCabangAsal);
                }
                if (!empty($this->filterKategori)) {
                    $query->where('kategori_cabang_snapshot', $this->filterKategori);
                }
            }
        } else {
            // Super Admin or Hub Pusat: flexible filters
            if (!empty($this->filterCabangAsal)) {
                $query->where('cabang_asal_id', $this->filterCabangAsal);
            }
            if (!empty($this->filterKategori)) {
                $query->where('kategori_cabang_snapshot', $this->filterKategori);
            }
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('kode_barang', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('nama_pengirim', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('nama_penerima', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('no_wa_penerima', 'LIKE', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $barangList = $query->latest()->paginate(10);
        $cabangIndo = Cabang::where('tipe_lokasi', 'indonesia')->get();

        return view('livewire.daftar-barang', [
            'barangList' => $barangList,
            'cabangIndo' => $cabangIndo,
        ]);
    }
}
