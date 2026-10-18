<div>
    <x-slot name="title">Kelola Akun User</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Akun User</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar seluruh akun login. Reset password cabang dan atur status aktif akun.</p>
    </div>

    <!-- Flash success -->
    @if(session('success'))
    <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
    @endif

    <!-- Search Bar -->
    <div class="mb-5 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-3">
        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Cari nama, email, role, atau cabang..."
            class="flex-1 text-sm focus:outline-none bg-transparent text-slate-800 placeholder-slate-400">
        @if($search)
        <button wire:click="$set('search','')" class="text-slate-400 hover:text-slate-600 transition">✕</button>
        @endif
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="p-3 pl-5">#</th>
                        <th class="p-3">Nama & Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Cabang</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                    @php
                        $isSelf     = $u->id === Auth::id();
                        $roleLabels = [
                            'super_admin'       => ['🛡️ Super Admin',    'bg-amber-100 text-amber-900'],
                            'admin_pusat'       => ['🏢 Admin Pusat',    'bg-indigo-100 text-indigo-900'],
                            'admin_cabang_indo' => ['🇮🇩 Admin Cabang', 'bg-emerald-100 text-emerald-900'],
                            'admin_cabang_hk'   => ['🇭🇰 Admin HK',    'bg-rose-100 text-rose-900'],
                        ];
                        $roleInfo = $roleLabels[$u->role] ?? ['👤 User', 'bg-slate-100 text-slate-700'];
                    @endphp
                    <tr class="hover:bg-slate-50 transition {{ $isSelf ? 'bg-emerald-50/40' : '' }}">
                        <td class="p-3 pl-5 text-xs text-slate-400 font-mono">{{ $u->id }}</td>
                        <td class="p-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-slate-700 to-slate-600 flex items-center justify-center text-white font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                        {{ $u->name }}
                                        @if($isSelf)
                                        <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded">Anda</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $u->email }}</div>
                                    @if($u->no_hp)
                                    <div class="text-[10px] text-slate-400">📱 {{ $u->no_hp }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $roleInfo[1] }}">
                                {{ $roleInfo[0] }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($u->cabang)
                            <div class="text-sm font-medium text-slate-800">{{ $u->cabang->nama_cabang }}</div>
                            <div class="text-xs text-slate-400 font-mono">{{ $u->cabang->kode_cabang }}</div>
                            @else
                            <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            @if($u->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span> Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block"></span> Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if(!$isSelf)
                                <!-- Reset Password -->
                                <button
                                    wire:click="bukaReset({{ $u->id }})"
                                    class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-lg text-xs font-bold transition"
                                    title="Reset Password">
                                    🔑 Reset PW
                                </button>
                                <!-- Toggle Aktif -->
                                <button
                                    wire:click="toggleAktif({{ $u->id }})"
                                    wire:confirm="{{ $u->is_active ? 'Nonaktifkan akun ini?' : 'Aktifkan kembali akun ini?' }}"
                                    class="px-2 py-1.5 {{ $u->is_active ? 'bg-slate-100 hover:bg-rose-100 text-slate-600 hover:text-rose-700 border-slate-200 hover:border-rose-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200' }} border rounded-lg text-xs font-bold transition"
                                    title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    {{ $u->is_active ? '🚫' : '✅' }}
                                </button>
                                @else
                                <a href="{{ route('ganti-password') }}"
                                    class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-bold transition">
                                    🔐 Ganti PW
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-slate-400">Tidak ada user yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════════ -->
    <!-- Modal Reset Password                                        -->
    <!-- ═══════════════════════════════════════════════════════════ -->
    @if($resetUserId)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="tutupReset"></div>

        <!-- Modal Panel -->
        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden z-10">
            <!-- Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl font-extrabold">
                        {{ strtoupper(substr($resetUserName, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base">Reset Password</h3>
                        <p class="text-xs text-white/80 font-mono">{{ $resetUserEmail }}</p>
                    </div>
                </div>
            </div>

            <!-- Body Form -->
            <div class="p-6 space-y-4">
                <div class="px-3 py-2.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                    ⚠️ Password baru untuk akun <strong>{{ $resetUserName }}</strong> akan langsung aktif. Informasikan password baru kepada pemilik akun.
                </div>

                <!-- Password Baru -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Password Baru</label>
                    <div class="relative">
                        <input
                            wire:model.defer="passwordBaru"
                            :type="$wire.showPassword ? 'text' : 'password'"
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="w-full px-4 py-2.5 pr-11 rounded-xl border {{ $errors->has('passwordBaru') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }} text-sm focus:ring-2 focus:ring-amber-400 focus:outline-none">
                        <button type="button" wire:click="$toggle('showPassword')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('passwordBaru') <p class="mt-1 text-xs text-rose-600">⚠️ {{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input
                        wire:model.defer="passwordKonfirmasi"
                        :type="$wire.showPassword ? 'text' : 'password'"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('passwordKonfirmasi') ? 'border-rose-400 bg-rose-50' : 'border-slate-300' }} text-sm focus:ring-2 focus:ring-amber-400 focus:outline-none">
                    @error('passwordKonfirmasi') <p class="mt-1 text-xs text-rose-600">⚠️ {{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-6 pb-6 flex gap-3">
                <button wire:click="simpanReset"
                    class="flex-1 py-2.5 bg-amber-500 hover:bg-amber-600 active:scale-[.98] text-white font-bold rounded-xl text-sm shadow transition-all">
                    💾 Simpan Password Baru
                </button>
                <button wire:click="tutupReset"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-sm transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
