<div class="space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">📋 Laporan Status Barang</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar barang yang sudah atau belum diambil beserta tanggal pengiriman.</p>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap gap-4 items-end bg-gray-50 border border-gray-200 rounded-xl p-4">

        {{-- Filter Bulan --}}
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Bulan Kirim</label>
            <input type="month" wire:model.live="filterBulan"
                   class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
        </div>

        {{-- Filter Status --}}
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Status Pengambilan</label>
            <select wire:model.live="filterStatus"
                    class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="semua">Semua</option>
                <option value="diambil">Sudah Diambil</option>
                <option value="belum_diambil">Belum Diambil</option>
            </select>
        </div>

        {{-- Pencarian --}}
        <div class="flex flex-col gap-1 flex-1 min-w-[200px]">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Cari</label>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Kode barang, nama pengirim, penerima..."
                   class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
        </div>

        {{-- Reset --}}
        @if($filterBulan || $search || $filterStatus !== 'semua')
        <button wire:click="$set('search', ''); $set('filterStatus', 'semua');"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition">
            Reset Filter
        </button>
        @endif
    </div>

    {{-- Summary Badges --}}
    <div class="flex flex-wrap gap-3">
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-3 py-1 text-sm font-medium">
            ✅ Sudah Diambil: <strong>{{ $barangs->where('tanggal_diambil', '!=', null)->count() }}</strong>
        </span>
        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-3 py-1 text-sm font-medium">
            ⏳ Belum Diambil: <strong>{{ $barangs->where('tanggal_diambil', null)->count() }}</strong>
        </span>
        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-3 py-1 text-sm font-medium">
            📦 Total: <strong>{{ $barangs->total() }}</strong>
        </span>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
        <table class="min-w-full bg-white text-gray-800 text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Kode Barang</th>
                    <th class="px-4 py-3 text-left">Pengirim</th>
                    <th class="px-4 py-3 text-left">Penerima</th>
                    <th class="px-4 py-3 text-left">Cabang Tujuan</th>
                    <th class="px-4 py-3 text-center">Tgl Kirim</th>
                    <th class="px-4 py-3 text-center">Tgl Sampai</th>
                    <th class="px-4 py-3 text-center">Tgl Diambil</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barangs as $b)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-mono font-semibold text-emerald-700">{{ $b->kode_barang }}</td>
                    <td class="px-4 py-3">{{ $b->nama_pengirim }}</td>
                    <td class="px-4 py-3">{{ $b->nama_penerima }}</td>
                    <td class="px-4 py-3">{{ optional($b->cabangTujuan)->nama_cabang ?? '-' }}</td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        {{ $b->tanggal_pengiriman ? $b->tanggal_pengiriman->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        {{ $b->tanggal_sampai ? $b->tanggal_sampai->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        {{ $b->tanggal_diambil ? $b->tanggal_diambil->format('d/m/Y') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($b->tanggal_diambil)
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                ✅ Sudah Diambil
                            </span>
                        @else
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                ⏳ Belum Diambil
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                        Tidak ada data untuk filter yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($barangs->hasPages())
    <div class="mt-4">
        {{ $barangs->links() }}
    </div>
    @endif

</div>
