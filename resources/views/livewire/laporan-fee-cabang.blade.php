<div class="space-y-6">

    {{-- Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">💰 Laporan Fee Cabang</h1>
        <p class="text-sm text-gray-500 mt-1">Total fee yang menjadi hak masing-masing cabang berdasarkan periode pengiriman.</p>
    </div>

    {{-- Filter Bar --}}
    <div class="flex flex-wrap gap-4 items-end bg-gray-50 border border-gray-200 rounded-xl p-4">

        {{-- Filter Bulan --}}
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Bulan</label>
            <select wire:model.live="month"
                    class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filter Tahun --}}
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Tahun</label>
            <select wire:model.live="year"
                    class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                @foreach(range(date('Y') - 5, date('Y') + 1) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>

        {{-- Filter Kategori --}}
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Jenis Cabang</label>
            <select wire:model.live="filterKategori"
                    class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                <option value="semua">Semua (ESB & ELP)</option>
                <option value="ESB">ESB</option>
                <option value="ELP">ELP</option>
            </select>
        </div>

        {{-- Search Nama Cabang --}}
        <div class="flex flex-col gap-1 flex-1 min-w-[200px]">
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Cari Cabang</label>
            <input type="text" wire:model.live.debounce.400ms="search"
                   placeholder="Nama cabang..."
                   class="rounded-lg border border-gray-300 bg-white text-gray-800 px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
        </div>

        {{-- Reset --}}
        @if($search || $filterKategori !== 'semua')
        <button wire:click="$set('search', ''); $set('filterKategori', 'semua');"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium transition self-end">
            Reset Filter
        </button>
        @endif
    </div>

    {{-- Summary Badges --}}
    <div class="flex flex-wrap gap-3 items-center">
        <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-3 py-1 text-sm font-medium">
            📦 Jumlah Cabang: <strong>{{ count($fees) }}</strong>
        </span>
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-3 py-1 text-sm font-medium">
            💵 Total Keseluruhan: <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
        </span>
    </div>

    {{-- Tabel --}}
    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
        <table class="min-w-full bg-white text-gray-800 text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">Nama Cabang</th>
                    <th class="px-4 py-3 text-center">Jenis</th>
                    <th class="px-4 py-3 text-right">Total Fee (Rp)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($fees as $row)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium">{{ $row['cabang'] }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($row['kategori'] === 'ESB')
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">ESB</span>
                        @elseif($row['kategori'] === 'ELP')
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">ELP</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-mono font-semibold {{ $row['total_fee'] > 0 ? 'text-emerald-700' : 'text-gray-400' }}">
                        {{ number_format($row['total_fee'], 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                        Tidak ada data untuk filter yang dipilih.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if(count($fees) > 0)
            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                <tr>
                    <td colspan="2" class="px-4 py-3 text-right font-bold text-gray-700">Grand Total</td>
                    <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700 text-base">
                        Rp {{ number_format($grandTotal, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>
