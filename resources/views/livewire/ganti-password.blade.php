<div>
    <x-slot name="title">Ganti Password</x-slot>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Keamanan Akun</h1>
        <p class="text-sm text-slate-500 mt-1">Ubah password login Anda. Gunakan kombinasi huruf besar, huruf kecil, dan angka.</p>
    </div>

    <div class="max-w-lg mx-auto">

        @if(session('success_pw'))
        <div class="mb-6 px-4 py-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-3 shadow-sm">
            <span class="text-xl">✅</span>
            <span>{{ session('success_pw') }}</span>
        </div>
        @endif

        <!-- Info Card User -->
        <div class="mb-6 p-4 bg-gradient-to-r from-slate-800 to-slate-700 rounded-2xl text-white flex items-center gap-4 shadow-md">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-2xl font-extrabold text-emerald-300 shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-bold text-sm leading-tight">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
                <div class="mt-1">
                    @php
                        $roleLabels = [
                            'super_admin'       => ['🛡️ Super Admin',    'bg-amber-500/20 text-amber-300 border-amber-500/30'],
                            'admin_pusat'       => ['🏢 Admin Pusat',    'bg-indigo-500/20 text-indigo-300 border-indigo-500/30'],
                            'admin_cabang_indo' => ['🇮🇩 Admin Cabang',  'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'],
                            'admin_cabang_hk'   => ['🇭🇰 Admin HK',     'bg-rose-500/20 text-rose-300 border-rose-500/30'],
                        ];
                        $roleInfo = $roleLabels[Auth::user()->role] ?? ['👤 User', 'bg-slate-500/20 text-slate-300 border-slate-500/30'];
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $roleInfo[1] }}">
                        {{ $roleInfo[0] }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Ganti Password -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <h2 class="text-base font-extrabold text-slate-900 mb-6 flex items-center gap-2">
                <span class="w-7 h-7 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm">🔑</span>
                Ubah Password
            </h2>

            <form wire:submit.prevent="simpan" class="space-y-5">

                <!-- Password Lama -->
                <div>
                    <label for="pw-lama" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Password Saat Ini
                    </label>
                    <div class="relative">
                        <input
                            id="pw-lama"
                            wire:model.defer="password_lama"
                            :type="$wire.showLama ? 'text' : 'password'"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('password_lama') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }} text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        <button type="button"
                            wire:click="$toggle('showLama')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!$wire.showLama" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="$wire.showLama" class="w-4 h-4" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_lama')
                    <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-dashed border-slate-200 pt-1"></div>

                <!-- Password Baru -->
                <div>
                    <label for="pw-baru" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input
                            id="pw-baru"
                            wire:model.defer="password_baru"
                            :type="$wire.showBaru ? 'text' : 'password'"
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter, huruf & angka"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('password_baru') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }} text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        <button type="button"
                            wire:click="$toggle('showBaru')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!$wire.showBaru" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="$wire.showBaru" class="w-4 h-4" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_baru')
                    <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">⚠️ {{ $message }}</p>
                    @enderror

                    <!-- Password strength hints -->
                    <div class="mt-2 grid grid-cols-3 gap-1.5">
                        <div class="h-1 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-rose-400 w-full" wire:ignore.self></div>
                        </div>
                        <div class="h-1 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-amber-400" style="{{ strlen($password_baru) >= 8 ? 'width:100%' : 'width:0' }}"></div>
                        </div>
                        <div class="h-1 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-emerald-400" style="{{ (strlen($password_baru) >= 8 && preg_match('/[A-Z]/', $password_baru) && preg_match('/[0-9]/', $password_baru)) ? 'width:100%' : 'width:0' }}"></div>
                        </div>
                    </div>
                    <p class="mt-1 text-[11px] text-slate-400">Minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
                </div>

                <!-- Konfirmasi Password Baru -->
                <div>
                    <label for="pw-konfirmasi" class="block text-sm font-bold text-slate-700 mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input
                            id="pw-konfirmasi"
                            wire:model.defer="password_konfirmasi"
                            :type="$wire.showKonfirmasi ? 'text' : 'password'"
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('password_konfirmasi') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }} text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none transition">
                        <button type="button"
                            wire:click="$toggle('showKonfirmasi')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                            <svg x-show="!$wire.showKonfirmasi" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="$wire.showKonfirmasi" class="w-4 h-4" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('password_konfirmasi')
                    <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">⚠️ {{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="pt-2">
                    <button type="submit"
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 active:scale-[.98] text-white font-bold rounded-xl shadow-sm transition-all text-sm">
                        🔐 Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips keamanan -->
        <div class="mt-5 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-xs text-amber-800 space-y-1.5">
            <p class="font-bold flex items-center gap-1.5">💡 Tips Keamanan Password</p>
            <ul class="space-y-1 pl-4 list-disc text-amber-700">
                <li>Jangan gunakan tanggal lahir atau nama sebagai password.</li>
                <li>Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol.</li>
                <li>Jangan bagikan password Anda kepada siapa pun, termasuk admin.</li>
                <li>Ganti password secara berkala untuk keamanan maksimal.</li>
            </ul>
        </div>
    </div>
</div>
