<div>
    <x-slot name="title">Kelola Cabang & Fee Kategori</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Cabang & Pengaturan Fee</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola cabang pengirim di Indonesia (ESB / ELP) dan lokasi cabang tujuan di Hong Kong.</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-5 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm font-medium flex items-center gap-2">
        ✅ {{ session('success') }}
    </div>
    @endif
    @if(session('success_fee'))
    <div class="mb-5 px-4 py-3 bg-teal-50 border border-teal-200 text-teal-800 rounded-2xl text-sm font-medium flex items-center gap-2">
        💰 {{ session('success_fee') }}
    </div>
    @endif

    <!-- ═══════════════════════════════════════════════════════════════════════ -->
    <!-- Panel: Pengaturan Fee Standar Kategori                                 -->
    <!-- ═══════════════════════════════════════════════════════════════════════ -->
    <div class="mb-8 bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Panel Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg">
                    💰
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 leading-tight">Pengaturan Fee Standar Kategori</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Fee ini menjadi <strong>nilai default</strong> saat cabang baru dengan kategori ESB / ELP dibuat.</p>
                </div>
            </div>
        </div>

        <!-- Fee Cards -->
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach(['ESB', 'ELP'] as $kat)
            @php
                $feeRecord   = $feeKategoriList[$kat] ?? null;
                $feeNilai    = $feeRecord ? $feeRecord['fee_per_kg'] : ($kat === 'ESB' ? 1000 : 2000);
                $feeKet      = $feeRecord ? ($feeRecord['keterangan'] ?? '') : '';
                $updatedBy   = $feeRecord['updated_by']['name'] ?? null;
                $updatedAt   = $feeRecord ? $feeRecord['updated_at'] : null;
                $isEditing   = $editingFeeKategori === $kat;
                $colorClass  = $kat === 'ESB'
                    ? 'bg-emerald-50 border-emerald-200 text-emerald-900'
                    : 'bg-teal-50 border-teal-200 text-teal-900';
                $badgeClass  = $kat === 'ESB'
                    ? 'bg-emerald-100 text-emerald-800'
                    : 'bg-teal-100 text-teal-800';
                $ringClass   = $kat === 'ESB'
                    ? 'focus:ring-emerald-500'
                    : 'focus:ring-teal-500';
                $btnClass    = $kat === 'ESB'
                    ? 'bg-emerald-600 hover:bg-emerald-700'
                    : 'bg-teal-600 hover:bg-teal-700';
            @endphp

            <div class="rounded-2xl border {{ $colorClass }} p-5 transition-shadow hover:shadow-md">
                <!-- Kategori Badge + Nilai Saat Ini -->
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $badgeClass }} mb-2">
                            {{ $kat }}
                        </span>
                        @if(!$isEditing)
                        <div class="text-3xl font-extrabold tracking-tight leading-none">
                            Rp {{ number_format($feeNilai, 0, ',', '.') }}
                        </div>
                        <div class="text-xs font-medium opacity-60 mt-1">per kilogram</div>
                        @endif
                    </div>
                    @if(!$isEditing)
                    <button wire:click="editFeeKategori('{{ $kat }}')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-xl border border-current opacity-70 hover:opacity-100 transition-opacity">
                        ✏️ Edit Fee
                    </button>
                    @endif
                </div>

                @if($feeKet && !$isEditing)
                <p class="text-xs opacity-60 mb-2 line-clamp-2">{{ $feeKet }}</p>
                @endif

                @if($updatedAt && !$isEditing)
                <div class="text-[10px] opacity-50 mt-2 flex items-center gap-1">
                    🕐 Diperbarui:
                    {{ \Carbon\Carbon::parse($updatedAt)->timezone('Asia/Jakarta')->isoFormat('D MMM YYYY, HH:mm') }}
                    @if($updatedBy) oleh <strong>{{ $updatedBy }}</strong> @endif
                </div>
                @endif

                <!-- Form Edit Fee (tampil inline saat isEditing) -->
                @if($isEditing)
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold mb-1 opacity-80">Nilai Fee Baru (Rp / kg)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold opacity-50">Rp</span>
                            <input type="number" step="500" min="0"
                                wire:model.defer="feeEditValue"
                                class="w-full pl-8 pr-3 py-2.5 rounded-xl border-2 border-current bg-white/60 font-extrabold text-lg focus:outline-none focus:ring-2 {{ $ringClass }}"
                                placeholder="0">
                        </div>
                        @error('feeEditValue') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1 opacity-80">Keterangan (opsional)</label>
                        <input type="text" wire:model.defer="feeEditKeterangan"
                            class="w-full px-3 py-2 rounded-xl border border-current bg-white/60 text-xs focus:outline-none focus:ring-2 {{ $ringClass }}"
                            placeholder="Mis: Fee revisi per Okt 2026">
                        @error('feeEditKeterangan') <span class="text-rose-500 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button wire:click="simpanFeeKategori"
                            class="flex-1 py-2 text-white font-bold rounded-xl text-xs {{ $btnClass }} transition-colors shadow-sm">
                            💾 Simpan Fee {{ $kat }}
                        </button>
                        <button wire:click="cancelEditFeeKategori"
                            class="px-4 py-2 bg-white/80 hover:bg-white text-slate-700 font-bold rounded-xl text-xs border border-slate-300 transition-colors">
                            Batal
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Catatan informatif -->
        <div class="px-6 pb-5">
            <div class="px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                <strong>ℹ️ Catatan:</strong> Mengubah fee standar di sini <strong>tidak</strong> mengubah fee cabang yang sudah ada.
                Perubahan hanya berlaku sebagai nilai default untuk cabang <em>baru</em> yang ditambahkan.
                Untuk mengubah fee cabang individual, gunakan tombol <strong>✏️ Edit</strong> pada baris cabang di tabel.
            </div>
        </div>
    </div>
    <!-- ═══════════════════════════════════════════════════════════════════════ -->

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Add/Edit Cabang -->
        <div id="form-cabang" class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm h-fit scroll-mt-24">
            <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center justify-between">
                <span>{{ $editingCabangId ? 'Edit Data Cabang' : 'Tambah Cabang Baru' }}</span>
                @if($editingCabangId)
                <span class="text-sm bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-mono font-bold">
                    ID: #{{ $editingCabangId }}
                </span>
                @endif
            </h2>

            <form wire:submit.prevent="simpan" class="space-y-4 text-xs">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Kode Cabang</label>
                    <input type="text" wire:model="kode_cabang" required placeholder="CBG-JKT-ESB"
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @error('kode_cabang') <span class="text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Cabang</label>
                    <input type="text" wire:model="nama_cabang" required placeholder="Cabang Jakarta Barat"
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @error('nama_cabang') <span class="text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nama Pemilik / Penanggung Jawab</label>
                    <input type="text" wire:model="nama_pemilik" placeholder="Hj. Fatimah / Pak Hendra"
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @error('nama_pemilik') <span class="text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tipe Lokasi</label>
                        <select wire:model="tipe_lokasi" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="indonesia">Indonesia</option>
                            <option value="hongkong">Hong Kong</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kategori Fee</label>
                        <select wire:model.live="kategori_cabang" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @php
                                $feeEsb = $feeKategoriList['ESB']['fee_per_kg'] ?? 1000;
                                $feeElp = $feeKategoriList['ELP']['fee_per_kg'] ?? 2000;
                            @endphp
                            <option value="ESB">ESB (Rp {{ number_format($feeEsb, 0, ',', '.') }}/kg)</option>
                            <option value="ELP">ELP (Rp {{ number_format($feeElp, 0, ',', '.') }}/kg)</option>
                            <option value="PUSAT">PUSAT (Sorting Hub)</option>
                            <option value="HK">HK (Destination)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">
                        Nilai Fee (Rp / kg)
                        @if(in_array($kategori_cabang, ['ESB','ELP']))
                        <span class="ml-1 text-[10px] font-normal text-slate-400 italic">
                            — default dari standar {{ $kategori_cabang }}, bisa diubah
                        </span>
                        @endif
                    </label>
                    <input type="number" step="500" wire:model="fee_per_kg" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold text-emerald-600 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    @error('fee_per_kg') <span class="text-rose-500 block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <textarea wire:model="alamat" rows="2" placeholder="Jl. Raya Kebon Jeruk No..."
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Telepon Utama</label>
                        <input type="text" wire:model="no_telp" placeholder="08123456789"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">No. Telp Alternatif</label>
                        <input type="text" wire:model="no_telp_alternatif" placeholder="081399887766"
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 font-mono text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <button type="submit"
                        class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition">
                        {{ $editingCabangId ? 'Simpan Perubahan' : 'Tambah Cabang' }}
                    </button>
                    @if($editingCabangId)
                    <button type="button" wire:click="resetForm"
                        class="px-3 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition">
                        Batal
                    </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Cabang Table & Search/Filters -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Search and Filter Bar -->
            <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-base font-bold text-slate-900">
                        Daftar Cabang Aktif 
                        <span class="ml-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            Total: {{ $cabangList->total() }} Cabang
                        </span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 border-t border-slate-100">
                    <!-- Search Input -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Cari Cabang / Email / Telp</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama, email, telp..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>

                    <!-- Filter Tipe Lokasi -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Filter Tipe Lokasi</label>
                        <select wire:model.live="filterTipeLokasi" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="">Semua Lokasi</option>
                            <option value="indonesia">🇮🇩 Indonesia</option>
                            <option value="hongkong">🇭🇰 Hong Kong</option>
                        </select>
                    </div>

                    <!-- Filter Kategori Fee -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Filter Kategori Fee</label>
                        <select wire:model.live="filterKategori" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            <option value="">Semua Kategori</option>
                            <option value="ESB">ESB</option>
                            <option value="ELP">ELP</option>
                            <option value="PUSAT">PUSAT (Sorting Hub)</option>
                            <option value="HK">HK (Destination)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Datatable -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                            <tr>
                                <th class="p-3">Kode</th>
                                <th class="p-3">Nama Cabang & Akun Login</th>
                                <th class="p-3">Kontak & Alamat</th>
                                <th class="p-3">Kategori</th>
                                <th class="p-3 text-right">Fee / kg</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($cabangList as $cb)
                            <tr class="hover:bg-slate-50">
                                <td class="p-3 font-mono font-bold text-slate-800">{{ $cb->kode_cabang }}</td>
                                <td class="p-3">
                                    <div class="font-bold text-slate-900">{{ $cb->nama_cabang }}</div>
                                    @if($cb->nama_pemilik)
                                    <div class="text-xs text-emerald-700 font-medium mt-0.5">👤 {{ $cb->nama_pemilik }}</div>
                                    @endif
                                    @if($cb->users->first())
                                    <div class="text-xs text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded w-fit mt-1">
                                        🔑 <strong>{{ $cb->users->first()->email }}</strong>
                                    </div>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <div class="text-slate-800">{{ $cb->no_telp ?: '-' }}</div>
                                    @if($cb->no_telp_alternatif)
                                    <div class="text-slate-500 text-[10px]">Alt: {{ $cb->no_telp_alternatif }}</div>
                                    @endif
                                    <div class="text-xs text-slate-400 mt-0.5 line-clamp-2" title="{{ $cb->alamat }}">{{ $cb->alamat }}</div>
                                </td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $cb->kategori_cabang === 'ESB' ? 'bg-emerald-100 text-emerald-900' : ($cb->kategori_cabang === 'ELP' ? 'bg-teal-100 text-teal-900' : 'bg-slate-100 text-slate-700') }}">
                                        {{ $cb->kategori_cabang }} ({{ strtoupper($cb->tipe_lokasi) }})
                                    </span>
                                </td>
                                <td class="p-3 text-right font-bold text-emerald-700">
                                    Rp {{ number_format($cb->fee_per_kg, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center">
                                    <button wire:click="edit({{ $cb->id }})"
                                        @click="document.getElementById('form-cabang').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold border transition">
                                        ✏️ Edit
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada cabang yang sesuai dengan pencarian / filter.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $cabangList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
