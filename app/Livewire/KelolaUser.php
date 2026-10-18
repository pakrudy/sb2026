<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class KelolaUser extends Component
{
    use WithPagination;

    public string $search = '';

    // State modal reset password
    public ?int   $resetUserId    = null;
    public string $resetUserName  = '';
    public string $resetUserEmail = '';
    public string $passwordBaru   = '';
    public string $passwordKonfirmasi = '';
    public bool   $showPassword   = false;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // ── Buka modal reset password untuk user tertentu ─────────────────────────
    public function bukaReset(int $userId): void
    {
        // Hanya super_admin yang boleh
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        // Tidak boleh reset password diri sendiri melalui panel ini
        abort_if($userId === Auth::id(), 403, 'Gunakan halaman Ganti Password untuk akun sendiri.');

        $user = User::findOrFail($userId);

        $this->resetUserId       = $user->id;
        $this->resetUserName     = $user->name;
        $this->resetUserEmail    = $user->email;
        $this->passwordBaru      = '';
        $this->passwordKonfirmasi = '';
        $this->showPassword      = false;
    }

    // ── Tutup modal ───────────────────────────────────────────────────────────
    public function tutupReset(): void
    {
        $this->resetUserId       = null;
        $this->resetUserName     = '';
        $this->resetUserEmail    = '';
        $this->passwordBaru      = '';
        $this->passwordKonfirmasi = '';
        $this->showPassword      = false;
    }

    // ── Simpan password baru ──────────────────────────────────────────────────
    public function simpanReset(): void
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $this->validate([
            'passwordBaru'        => 'required|string|min:8',
            'passwordKonfirmasi'  => 'required|same:passwordBaru',
        ], [
            'passwordBaru.required'       => 'Password baru wajib diisi.',
            'passwordBaru.min'            => 'Password minimal 8 karakter.',
            'passwordKonfirmasi.required' => 'Konfirmasi password wajib diisi.',
            'passwordKonfirmasi.same'     => 'Konfirmasi tidak cocok dengan password baru.',
        ]);

        $user = User::findOrFail($this->resetUserId);
        $user->update(['password' => Hash::make($this->passwordBaru)]);

        $nama = $this->resetUserName;
        $this->tutupReset();

        session()->flash('success', "Password untuk akun \"{$nama}\" berhasil direset!");
    }

    // ── Toggle status aktif user ──────────────────────────────────────────────
    public function toggleAktif(int $userId): void
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);
        abort_if($userId === Auth::id(), 403);

        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('success', "Akun \"{$user->name}\" berhasil {$status}.");
    }

    public function render()
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $users = User::with('cabang')
            ->when($this->search, function ($q) {
                $q->where(function ($inner) {
                    $inner->where('name', 'LIKE', "%{$this->search}%")
                          ->orWhere('email', 'LIKE', "%{$this->search}%")
                          ->orWhere('role', 'LIKE', "%{$this->search}%")
                          ->orWhereHas('cabang', function ($cq) {
                              $cq->where('nama_cabang', 'LIKE', "%{$this->search}%")
                                 ->orWhere('kode_cabang', 'LIKE', "%{$this->search}%");
                          });
                });
            })
            ->orderByRaw("CASE role
                WHEN 'super_admin' THEN 1
                WHEN 'admin_pusat' THEN 2
                WHEN 'admin_cabang_indo' THEN 3
                WHEN 'admin_cabang_hk' THEN 4
                ELSE 5 END")
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.kelola-user', ['users' => $users]);
    }
}
