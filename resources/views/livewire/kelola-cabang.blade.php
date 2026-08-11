<div>
    <x-slot name="title">Kelola Cabang & Fee Kategori</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Cabang & Pengaturan Fee</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola cabang pengirim di Indonesia (ESB / ELP) dan lokasi cabang tujuan di Hong Kong.</p>
    </div>

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
                            <option value="ESB">ESB (Rp 1.000/kg)</option>
                            <option value="ELP">ELP (Rp 2.000/kg)</option>
                            <option value="PUSAT">PUSAT (Sorting Hub)</option>
                            <option value="HK">HK (Destination)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Nilai Fee (Rp / kg)</label>
                    <input type="number" step="500" wire:model="fee_per_kg" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-300 font-bold text-emerald-600 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
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
                            <option value="ESB">ESB (Rp 1.000/kg)</option>
                            <option value="ELP">ELP (Rp 2.000/kg)</option>
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
                                    <div class=text-slate-800">{{ $cb->no_telp ?: '-' }}</div>
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
