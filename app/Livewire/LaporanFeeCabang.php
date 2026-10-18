<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Cabang;
use App\Models\Barang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanFeeCabang extends Component
{
    public int    $month;
    public int    $year;
    public string $filterKategori = 'semua'; // semua | ESB | ELP
    public string $search         = '';
    public array  $fees           = [];
    public float  $grandTotal     = 0;

    public function mount(): void
    {
        $now = Carbon::now();
        $this->month = $now->month;
        $this->year  = $now->year;
        $this->calculate();
    }

    public function updatedMonth(): void        { $this->calculate(); }
    public function updatedYear(): void         { $this->calculate(); }
    public function updatedFilterKategori(): void { $this->calculate(); }
    public function updatedSearch(): void       { $this->calculate(); }

    protected function calculate(): void
    {
        // Build cabang query with optional kategori and name filter
        $cabangQuery = Cabang::query();

        if ($this->filterKategori !== 'semua') {
            $cabangQuery->where('kategori_cabang', $this->filterKategori);
        }

        if ($this->search !== '') {
            $cabangQuery->where('nama_cabang', 'LIKE', '%' . $this->search . '%');
        }

        $cabangs = $cabangQuery->get()->keyBy('id');

        // Aggregate fee per cabang for selected month/year
        $records = Barang::select('cabang_asal_id', DB::raw('SUM(fee_cabang) as total_fee'))
            ->whereMonth('tanggal_pengiriman', $this->month)
            ->whereYear('tanggal_pengiriman', $this->year)
            ->whereIn('cabang_asal_id', $cabangs->keys())
            ->groupBy('cabang_asal_id')
            ->get()
            ->keyBy('cabang_asal_id');

        $this->fees = [];
        foreach ($cabangs as $id => $cabang) {
            $this->fees[] = [
                'cabang'    => $cabang->nama_cabang,
                'kategori'  => $cabang->kategori_cabang,
                'total_fee' => (float) ($records->get($id)->total_fee ?? 0),
            ];
        }

        $this->grandTotal = array_sum(array_column($this->fees, 'total_fee'));
    }

    public function render()
    {
        return view('livewire.laporan-fee-cabang');
    }
}
?>
