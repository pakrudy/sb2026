<?php

namespace App\Livewire;

use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaCabang extends Component
{
    use WithPagination;

    public $kode_cabang = '';
    public $nama_cabang = '';
    public $nama_pemilik = '';
    public $tipe_lokasi = 'indonesia';
    public $kategori_cabang = 'ESB';
    public $fee_per_kg = 1000;
    public $alamat = '';
    public $no_telp = '';
    public $no_telp_alternatif = '';

    public $search = '';
    public $filterTipeLokasi = '';
    public $filterKategori = '';

    public $editingCabangId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterTipeLokasi' => ['except' => ''],
        'filterKategori' => ['except' => ''],
    ];

    protected $rules = [
        'kode_cabang' => 'required|string|max:20',
        'nama_cabang' => 'required|string|max:150',
        'nama_pemilik' => 'nullable|string|max:150',
        'tipe_lokasi' => 'required|in:indonesia,hongkong',
        'kategori_cabang' => 'required|in:ESB,ELP,PUSAT,HK',
        'fee_per_kg' => 'required|numeric|min:0',
        'alamat' => 'nullable|string',
        'no_telp' => 'nullable|string|max:30',
        'no_telp_alternatif' => 'nullable|string|max:30',
    ];

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

    public function updatedKategoriCabang()
    {
        if ($this->kategori_cabang === 'ESB') {
            $this->fee_per_kg = 1000;
        } elseif ($this->kategori_cabang === 'ELP') {
            $this->fee_per_kg = 2000;
        } else {
            $this->fee_per_kg = 0;
        }
    }

    public function edit(int $id)
    {
        $cabang = Cabang::findOrFail($id);
        $this->editingCabangId = $cabang->id;
        $this->kode_cabang = $cabang->kode_cabang;
        $this->nama_cabang = $cabang->nama_cabang;
        $this->nama_pemilik = $cabang->nama_pemilik;
        $this->tipe_lokasi = $cabang->tipe_lokasi;
        $this->kategori_cabang = $cabang->kategori_cabang;
        $this->fee_per_kg = $cabang->fee_per_kg;
        $this->alamat = $cabang->alamat;
        $this->no_telp = $cabang->no_telp;
        $this->no_telp_alternatif = $cabang->no_telp_alternatif;
    }

    public function resetForm()
    {
        $this->editingCabangId = null;
        $this->reset(['kode_cabang', 'nama_cabang', 'nama_pemilik', 'alamat', 'no_telp', 'no_telp_alternatif']);
        $this->tipe_lokasi = 'indonesia';
        $this->kategori_cabang = 'ESB';
        $this->fee_per_kg = 1000;
    }

    public function simpan()
    {
        $this->validate();

        if ($this->editingCabangId) {
            $cabang = Cabang::findOrFail($this->editingCabangId);
            $cabang->update([
                'kode_cabang' => $this->kode_cabang,
                'nama_cabang' => $this->nama_cabang,
                'nama_pemilik' => $this->nama_pemilik,
                'tipe_lokasi' => $this->tipe_lokasi,
                'kategori_cabang' => $this->kategori_cabang,
                'fee_per_kg' => $this->fee_per_kg,
                'alamat' => $this->alamat,
                'no_telp' => $this->no_telp,
                'no_telp_alternatif' => $this->no_telp_alternatif,
            ]);
            session()->flash('success', "Cabang {$this->nama_cabang} berhasil diperbarui!");
        } else {
            $newCabang = Cabang::create([
                'kode_cabang' => $this->kode_cabang,
                'nama_cabang' => $this->nama_cabang,
                'nama_pemilik' => $this->nama_pemilik,
                'tipe_lokasi' => $this->tipe_lokasi,
                'kategori_cabang' => $this->kategori_cabang,
                'fee_per_kg' => $this->fee_per_kg,
                'alamat' => $this->alamat,
                'no_telp' => $this->no_telp,
                'no_telp_alternatif' => $this->no_telp_alternatif,
            ]);

            // Auto generate user account for new branch
            $cleanName = Str::slug($newCabang->nama_cabang, '.');
            $email = $cleanName . '@esb.com';
            $suffix = 1;
            while (User::where('email', $email)->exists()) {
                $email = $cleanName . $suffix . '@esb.com';
                $suffix++;
            }

            $role = $newCabang->tipe_lokasi === 'hongkong' ? 'admin_cabang_hk' : ($newCabang->kategori_cabang === 'PUSAT' ? 'admin_pusat' : 'admin_cabang_indo');

            User::create([
                'cabang_id' => $newCabang->id,
                'name' => $newCabang->nama_pemilik ? "{$newCabang->nama_pemilik} ({$newCabang->nama_cabang})" : "Admin {$newCabang->nama_cabang}",
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => $role,
                'no_hp' => $newCabang->no_telp ?: '081234567890',
                'is_active' => true,
            ]);

            session()->flash('success', "Cabang baru {$this->nama_cabang} & Akun Login ({$email}) berhasil dibuat!");
        }

        $this->resetForm();
    }

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
