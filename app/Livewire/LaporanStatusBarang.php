<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Barang;
use Carbon\Carbon;

class LaporanStatusBarang extends Component
{
    use WithPagination;

    public string $filterStatus = 'semua';   // semua | diambil | belum_diambil
    public string $filterBulan  = '';         // Y-m format, e.g. "2026-08"
    public string $search       = '';

    public function mount(): void
    {
        // Default ke bulan ini
        $this->filterBulan = Carbon::now()->format('Y-m');
    }

    public function updatedFilterStatus(): void { $this->resetPage(); }
    public function updatedFilterBulan(): void  { $this->resetPage(); }
    public function updatedSearch(): void       { $this->resetPage(); }

    public function render()
    {
        $query = Barang::with(['cabangAsal', 'cabangTujuan'])
            ->orderBy('tanggal_pengiriman', 'desc');

        // Filter bulan kirim
        if ($this->filterBulan !== '') {
            [$year, $month] = explode('-', $this->filterBulan);
            $query->whereYear('tanggal_pengiriman', $year)
                  ->whereMonth('tanggal_pengiriman', $month);
        }

        // Filter status diambil / belum
        if ($this->filterStatus === 'diambil') {
            $query->whereNotNull('tanggal_diambil');
        } elseif ($this->filterStatus === 'belum_diambil') {
            $query->whereNull('tanggal_diambil');
        }

        // Pencarian nama penerima / kode barang
        if ($this->search !== '') {
            $search = '%' . $this->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'LIKE', $search)
                  ->orWhere('nama_penerima', 'LIKE', $search)
                  ->orWhere('nama_pengirim', 'LIKE', $search);
            });
        }

        $barangs = $query->paginate(20);

        return view('livewire.laporan-status-barang', compact('barangs'));
    }
}
