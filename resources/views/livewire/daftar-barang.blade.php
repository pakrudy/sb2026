<div>
    <x-slot name="title">Daftar Data Barang & Fee Cabang</x-slot>

    @php
        $user = Auth::user();
        $userCabang = $user ? $user->cabang : null;
        $isHkUser = $userCabang && $userCabang->tipe_lokasi === 'hongkong';
        $isPusatUser = $userCabang && $userCabang->kategori_cabang === 'PUSAT';
    @endphp

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen & Rekap Data Barang</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh barang terdaftar, filter berdasarkan status, cabang, atau kategori fee ESB/ELP.</p>
        </div>

        @if(!$isHkUser && !$isPusatUser)
        <a href="{{ route('input-barang') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition">
            ➕ Input Barang Baru
        </a>
        @endif
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm mb-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Cari Resi / Nama / WA</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Contoh: ESB-2026... atau Dewi"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <!-- Filter Status -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Status Barang</label>
                <select wire:model.live="filterStatus" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="diterima_di_cabang">Diterima di Cabang</option>
                    <option value="dalam_perjalanan_ke_pusat">Dalam Perjalanan ke Pusat</option>
                    <option value="disortir_di_pusat">Disortir di Pusat (Boxed)</option>
                    <option value="dalam_pengiriman_hk">Dalam Pengiriman ke HK</option>
                    <option value="sampai_di_hk">Sampai di Cabang HK</option>
                    <option value="sudah_diambil">Sudah Diambil</option>
                </select>
            </div>

            <!-- Filter Cabang -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Cabang Asal</span>
                    @if($isCabangLocked)
                    <span class="text-[10px] text-slate-600 font-normal">🔒 Cabang Anda</span>
                    @endif
                </label>
                <select wire:model.live="filterCabangAsal"
                    @if($isCabangLocked) disabled @endif
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none @if($isCabangLocked) bg-slate-100/90 text-slate-700 cursor-not-allowed opacity-85 @endif">
                    @if(!$isCabangLocked)
                    <option value="">Semua Cabang Asal</option>
                    @endif
                    @foreach($cabangIndo as $cb)
                    <option value="{{ $cb->id }}">{{ $cb->nama_cabang }} ({{ $cb->kategori_cabang }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori Fee -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1 flex items-center justify-between">
                    <span>Kategori Fee Cabang</span>
                    @if($isKategoriLocked)
                    <span class="text-[10px] text-slate-600 font-normal">🔒 Dikunci ({{ $filterKategori }})</span>
                    @endif
                </label>
                <select wire:model.live="filterKategori"
                    @if($isKategoriLocked) disabled @endif
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none @if($isKategoriLocked) bg-slate-100/90 text-slate-700 cursor-not-allowed opacity-85 @endif">
                    @if(!$isKategoriLocked)
                    <option value="">Semua Kategori (ESB & ELP)</option>
                    @endif
                    <option value="ESB">Kategori ESB (Rp 1.000/kg)</option>
                    <option value="ELP">Kategori ELP (Rp 2.000/kg)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Datatable -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-200 font-bold">
                    <tr>
                        <th class="p-4">Kode Resi</th>
                        <th class="p-4">Tanggal Kirim</th>
                        <th class="p-4">Cabang Asal (Kat)</th>
                        <th class="p-4">Cabang HK</th>
                        <th class="p-4">Pengirim & Penerima</th>
                        <th class="p-4 text-right">Berat (kg)</th>
                        <th class="p-4 text-right">Fee Cabang</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($barangList as $b)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-mono font-bold text-slate-900">
                            <div>{{ $b->kode_barang }}</div>
                            @if($b->box)
                            <span class="inline-block mt-0.5 text-[10px] text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded font-mono border border-purple-200">
                                {{ $b->box->kode_box }}
                            </span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-600 font-mono">
                            {{ $b->tanggal_pengiriman ? $b->tanggal_pengiriman->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-slate-800">{{ $b->cabangAsal->nama_cabang }}</div>
                        </td>
                        <td class="p-4 font-bold text-indigo-700">
                            {{ $b->cabangTujuan->nama_cabang }}
                        </td>
                        <td class="p-4">
                            <div class="font-bold text-slate-900">Penerima: {{ $b->nama_penerima }}</div>
                            <div class="text-[11px] text-slate-500 font-mono">WA: {{ $b->no_wa_penerima }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">Pengirim: {{ $b->nama_pengirim }}</div>
                        </td>
                        <td class="p-4 text-right font-bold text-slate-900">
                            {{ number_format($b->berat_kg, 2, ',', '.') }} kg
                        </td>
                        <td class="p-4 text-right font-bold text-emerald-700">
                            Rp {{ number_format($b->fee_cabang, 0, ',', '.') }}
                        </td>
                        <td class="p-4">
                            @php
                                $statusBadges = [
                                    'diterima_di_cabang' => ['bg-yellow-100 text-yellow-800', 'Diterima Cabang'],
                                    'dalam_perjalanan_ke_pusat' => ['bg-blue-100 text-blue-800', 'Ke Hub Pusat'],
                                    'disortir_di_pusat' => ['bg-purple-100 text-purple-800', 'Disortir (Boxed)'],
                                    'dalam_pengiriman_hk' => ['bg-indigo-100 text-indigo-800', 'Pengiriman HK'],
                                    'sampai_di_hk' => ['bg-teal-100 text-teal-800', 'Tiba HK'],
                                    'sudah_diambil' => ['bg-emerald-100 text-emerald-800', 'Sudah Diambil'],
                                ];
                                $st = $statusBadges[$b->status] ?? ['bg-slate-100 text-slate-800', $b->status];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $st[0] }}">
                                {{ $st[1] }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <a href="{{ route('print.resi', $b->id) }}" target="_blank"
                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-white rounded-lg text-[11px] font-bold transition inline-flex items-center space-x-1">
                                <span>🖨️ Cetak</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">Tidak ada data barang yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $barangList->links() }}
        </div>
    </div>
</div>
