<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class GantiPassword extends Component
{
    public string $password_lama      = '';
    public string $password_baru      = '';
    public string $password_konfirmasi = '';

    public bool $showLama      = false;
    public bool $showBaru      = false;
    public bool $showKonfirmasi = false;

    protected function rules(): array
    {
        return [
            'password_lama'       => 'required|string',
            'password_baru'       => ['required', Password::min(8)->mixedCase()->numbers()],
            'password_konfirmasi' => 'required|same:password_baru',
        ];
    }

    protected $messages = [
        'password_lama.required'       => 'Password lama wajib diisi.',
        'password_baru.required'       => 'Password baru wajib diisi.',
        'password_baru.min'            => 'Password baru minimal 8 karakter.',
        'password_konfirmasi.required' => 'Konfirmasi password wajib diisi.',
        'password_konfirmasi.same'     => 'Konfirmasi password tidak cocok dengan password baru.',
    ];

    public function simpan()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->password_lama, $user->password)) {
            $this->addError('password_lama', 'Password lama tidak sesuai.');
            return;
        }

        if (Hash::check($this->password_baru, $user->password)) {
            $this->addError('password_baru', 'Password baru tidak boleh sama dengan password lama.');
            return;
        }

        $user->update(['password' => Hash::make($this->password_baru)]);

        $this->reset(['password_lama', 'password_baru', 'password_konfirmasi']);
        $this->showLama      = false;
        $this->showBaru      = false;
        $this->showKonfirmasi = false;

        session()->flash('success_pw', 'Password berhasil diubah!');
    }

    public function render()
    {
        return view('livewire.ganti-password');
    }
}
