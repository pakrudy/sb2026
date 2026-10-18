<?php

namespace App\Livewire;

use App\Models\Cabang;
use App\Models\FeeKategori;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaCabang extends Component
{
    use WithPagination;

    // ── Form Cabang ───────────────────────────────────────────────────────────
    public $kode_cabang = '';
    public $nama_cabang = '';
    public $nama_pemilik = '';
    public $tipe_lokasi = 'indonesia';
    public $kategori_cabang = 'ESB';
    public $fee_per_kg = 1000;
    public $alamat = '';
    public $no_telp = '';
    public $no_telp_alternatif = '';

    // ── Filter / Search ───────────────────────────────────────────────────────
    public $search = '';
    public $filterTipeLokasi = '';
    public $filterKategori = '';

    // ── Edit State ────────────────────────────────────────────────────────────
    public $editingCabangId = null;

    // ── Fee Kategori ──────────────────────────────────────────────────────────
    public $feeKategoriList = [];          // [['kategori'=>'ESB','fee_per_kg'=>1000], ...]
    public $editingFeeKategori = null;     // 'ESB' | 'ELP' | null
    public $feeEditValue = 0;             // nilai sementara saat edit
    public $feeEditKeterangan = '';        // keterangan sementara saat edit

    protected $queryString = [
        'search'           => ['except' => ''],
        'filterTipeLokasi' => ['except' => ''],
        'filterKategori'   => ['except' => ''],
    ];

    protected $rules = [
        'kode_cabang'       => 'required|string|max:20',
        'nama_cabang'       => 'required|string|max:150',
        'nama_pemilik'      => 'nullable|string|max:150',
        'tipe_lokasi'       => 'required|in:indonesia,hongkong',
        'kategori_cabang'   => 'required|in:ESB,ELP,PUSAT,HK',
        'fee_per_kg'        => 'required|numeric|min:0',
        'alamat'            => 'nullable|string',
        'no_telp'           => 'nullable|string|max:30',
        'no_telp_alternatif'=> 'nullable|string|max:30',
    ];

    // ── Lifecycle ─────────────────────────────────────────────────────────────
    public function mount(): void
    {
        $this->loadFeeKategoriList();
        $this->fee_per_kg = FeeKategori::getFeeUntukKategori('ESB');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function loadFeeKategoriList(): void
    {
        $this->feeKategoriList = FeeKategori::whereIn('kategori', ['ESB', 'ELP'])
            ->with('updatedBy')
            ->get()
            ->keyBy('kategori')
            ->toArray();
    }

    // ── Filter hooks ──────────────────────────────────────────────────────────
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterTipeLokasi()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    // ── Saat kategori berubah, auto-isi fee dari standar ─────────────────────
    public function updatedKategoriCabang()
    {
        if (in_array($this->kategori_cabang, ['ESB', 'ELP'])) {
            $this->fee_per_kg = FeeKategori::getFeeUntukKategori($this->kategori_cabang);
        } else {
            $this->fee_per_kg = 0;
        }
    }

    // ── CRUD Cabang ───────────────────────────────────────────────────────────
    public function edit(int $id)
    {
        $cabang = Cabang::findOrFail($id);
        $this->editingCabangId       = $cabang->id;
        $this->kode_cabang           = $cabang->kode_cabang;
        $this->nama_cabang           = $cabang->nama_cabang;
        $this->nama_pemilik          = $cabang->nama_pemilik;
        $this->tipe_lokasi           = $cabang->tipe_lokasi;
        $this->kategori_cabang       = $cabang->kategori_cabang;
        $this->fee_per_kg            = $cabang->fee_per_kg;
        $this->alamat                = $cabang->alamat;
        $this->no_telp               = $cabang->no_telp;
        $this->no_telp_alternatif    = $cabang->no_telp_alternatif;
    }

    public function resetForm()
    {
        $this->editingCabangId = null;
        $this->reset(['kode_cabang', 'nama_cabang', 'nama_pemilik', 'alamat', 'no_telp', 'no_telp_alternatif']);
        $this->tipe_lokasi       = 'indonesia';
        $this->kategori_cabang   = 'ESB';
        $this->fee_per_kg        = FeeKategori::getFeeUntukKategori('ESB');
    }

    public function simpan()
    {
        $this->validate();

        if ($this->editingCabangId) {
            $cabang = Cabang::findOrFail($this->editingCabangId);
            $cabang->update([
                'kode_cabang'        => $this->kode_cabang,
                'nama_cabang'        => $this->nama_cabang,
                'nama_pemilik'       => $this->nama_pemilik,
                'tipe_lokasi'        => $this->tipe_lokasi,
                'kategori_cabang'    => $this->kategori_cabang,
                'fee_per_kg'         => $this->fee_per_kg,
                'alamat'             => $this->alamat,
                'no_telp'            => $this->no_telp,
                'no_telp_alternatif' => $this->no_telp_alternatif,
            ]);
            session()->flash('success', "Cabang {$this->nama_cabang} berhasil diperbarui!");
        } else {
            $newCabang = Cabang::create([
                'kode_cabang'        => $this->kode_cabang,
                'nama_cabang'        => $this->nama_cabang,
                'nama_pemilik'       => $this->nama_pemilik,
                'tipe_lokasi'        => $this->tipe_lokasi,
                'kategori_cabang'    => $this->kategori_cabang,
                'fee_per_kg'         => $this->fee_per_kg,
                'alamat'             => $this->alamat,
                'no_telp'            => $this->no_telp,
                'no_telp_alternatif' => $this->no_telp_alternatif,
            ]);

            // Auto generate user account for new branch
            $cleanName = Str::slug($newCabang->nama_cabang, '.');
            $email     = $cleanName . '@esb.com';
            $suffix    = 1;
            while (User::where('email', $email)->exists()) {
                $email = $cleanName . $suffix . '@esb.com';
                $suffix++;
            }

            $role = $newCabang->tipe_lokasi === 'hongkong'
                ? 'admin_cabang_hk'
                : ($newCabang->kategori_cabang === 'PUSAT' ? 'admin_pusat' : 'admin_cabang_indo');

            User::create([
                'cabang_id'  => $newCabang->id,
                'name'       => $newCabang->nama_pemilik
                    ? "{$newCabang->nama_pemilik} ({$newCabang->nama_cabang})"
                    : "Admin {$newCabang->nama_cabang}",
                'email'      => $email,
                'password'   => Hash::make('password123'),
                'role'       => $role,
                'no_hp'      => $newCabang->no_telp ?: '081234567890',
                'is_active'  => true,
            ]);

            session()->flash('success', "Cabang baru {$this->nama_cabang} & Akun Login ({$email}) berhasil dibuat!");
        }

        $this->resetForm();
    }

    // ── Fee Kategori Actions ──────────────────────────────────────────────────

    /**
     * Buka panel edit fee untuk kategori tertentu (ESB atau ELP).
     */
    public function editFeeKategori(string $kategori)
    {
        $this->editingFeeKategori = $kategori;
        $record = FeeKategori::where('kategori', $kategori)->first();
        $this->feeEditValue       = $record ? (float) $record->fee_per_kg : 0;
        $this->feeEditKeterangan  = $record ? ($record->keterangan ?? '') : '';
    }

    /**
     * Batalkan edit fee kategori.
     */
    public function cancelEditFeeKategori()
    {
        $this->editingFeeKategori = null;
        $this->feeEditValue       = 0;
        $this->feeEditKeterangan  = '';
    }

    /**
     * Simpan perubahan fee standar untuk kategori yang sedang diedit.
     */
    public function simpanFeeKategori()
    {
        $this->validate([
            'feeEditValue'       => 'required|numeric|min:0',
            'feeEditKeterangan'  => 'nullable|string|max:200',
        ]);

        FeeKategori::updateOrCreate(
            ['kategori' => $this->editingFeeKategori],
            [
                'fee_per_kg'  => $this->feeEditValue,
                'keterangan'  => $this->feeEditKeterangan,
                'updated_by'  => Auth::id(),
            ]
        );

        $katLabel = $this->editingFeeKategori; // capture sebelum di-cancel

        $this->loadFeeKategoriList();
        $this->cancelEditFeeKategori();

        // Jika form cabang sedang aktif pada kategori yang sama, refresh fee-nya
        if (in_array($this->kategori_cabang, ['ESB', 'ELP'])) {
            $this->fee_per_kg = FeeKategori::getFeeUntukKategori($this->kategori_cabang);
        }

        session()->flash('success_fee', "Fee standar {$katLabel} berhasil diperbarui!");
    }

    // ── Render ────────────────────────────────────────────────────────────────
    public function render()
    {
        $query = Cabang::with(['users'])->withCount(['barangAsal', 'barangTujuan']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_cabang', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('kode_cabang', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('nama_pemilik', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('alamat', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('no_telp', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('no_telp_alternatif', 'LIKE', '%' . $this->search . '%')
                    ->orWhereHas('users', function ($uq) {
                        $uq->where('email', 'LIKE', '%' . $this->search . '%');
                    });
            });
        }

        if (!empty($this->filterTipeLokasi)) {
            $query->where('tipe_lokasi', $this->filterTipeLokasi);
        }

        if (!empty($this->filterKategori)) {
            $query->where('kategori_cabang', $this->filterKategori);
        }

        $cabangList = $query->orderBy('nama_cabang')->paginate(12);

        return view('livewire.kelola-cabang', [
            'cabangList' => $cabangList,
        ]);
    }
}
