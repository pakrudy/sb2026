<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanKgBulanIni extends Component
{
    public int $month;
    public int $year;
    public array $totals = [];

    public function mount(): void
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year = $now->year;
        $this->calculate();
    }

    public function updatedMonth(): void
    {
        $this->calculate();
    }

    public function updatedYear(): void
    {
        $this->calculate();
    }

    protected function calculate(): void
    {
        $start = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $records = Barang::select('kategori_cabang_snapshot', DB::raw('SUM(berat_kg) as total_kg'))
            ->whereBetween('tanggal_pengiriman', [$start, $end])
            ->groupBy('kategori_cabang_snapshot')
            ->get()
            ->keyBy('kategori_cabang_snapshot');

        $this->totals = [
            'ESB' => $records->get('ESB')->total_kg ?? 0,
            'ELP' => $records->get('ELP')->total_kg ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.laporan-kg-bulan-ini');
    }
}
