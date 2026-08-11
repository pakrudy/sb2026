<div>
    <x-slot name="title">Penerimaan & Pengambilan Cabang HK</x-slot>

    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">🇭🇰 Operasional Cabang Hong Kong</h1>
            <p class="text-sm text-slate-500 mt-1">Proses penerimaan Box dari pusat (input tanggal sampai) dan pencatatan pengambilan oleh customer (input tanggal diambil).</p>
        </div>

        <div class="flex items-center space-x-2 bg-slate-200 p-1 rounded-2xl text-xs font-bold">
            <button wire:click="$set('activeTab', 'penerimaan')"
                class="px-4 py-2 rounded-xl transition {{ $activeTab === 'penerimaan' ? 'bg-white text-slate-900 shadow' : 'text-slate-600 hover:text-slate-900' }}">
                📦 1. Terima Box / Transit
            </button>
            <button wire:click="$set('activeTab', 'pengambilan')"
                class="px-4 py-2 rounded-xl transition {{ $activeTab === 'pengambilan' ? 'bg-white text-slate-900 shadow' : 'text-slate-600 hover:text-slate-900' }}">
                🤝 2. Pengambilan Barang
            </button>
        </div>
    </div>

    @if($activeTab === 'penerimaan')
    <!-- TAB 1: PENERIMAAN BOX DARI PUSAT -->
    <div class="space-y-6">
        <!-- Success & WhatsApp Status Notification Card -->
        @if($lastReceivedBox)
        <div class="p-6 rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-emerald-950 text-white border-2 border-emerald-500/80 shadow-2xl relative overflow-hidden">
            <button wire:click="closeNotification" type="button" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-sm font-bold transition">
                ✕
            </button>

            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-500 text-slate-950 rounded-full font-black text-xs uppercase tracking-wider">
                        🎉 Box Berhasil Diterima di HK
                    </span>
                    <span class="text-xs font-mono text-emerald-300">
                        Kode Box: <strong class="text-white text-base font-extrabold">{{ $lastReceivedBox->kode_box }}</strong>
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-slate-900/70 p-4 rounded-2xl border border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px]">Cabang HK Penerima</span>
                        <strong class="text-emerald-300 block text-sm">{{ $lastReceivedBox->cabangTujuan->nama_cabang }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Total Paket Diterima</span>
                        <strong class="text-white block text-sm">{{ $lastReceivedBox->barang->count() }} Barang</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Waktu Tiba di HK</span>
                        <strong class="text-emerald-400 block text-sm font-mono">{{ $lastReceivedBox->tanggal_sampai ? $lastReceivedBox->tanggal_sampai->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }} WIB</strong>
                    </div>
                </div>

                <!-- WhatsApp Status Summary Card -->
                <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2">
                    <div class="flex items-center space-x-2 text-xs font-bold text-emerald-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>Status Notifikasi WhatsApp: {{ $lastReceivedWaCount }} Pesan "Barang Sampai HK" Berhasil Dikirim ke Penerima!</span>
                    </div>

                    <div class="mt-2 bg-slate-950 p-3 rounded-xl border border-slate-800 max-h-40 overflow-y-auto space-y-1.5 text-[11px] font-mono">
                        @foreach($lastReceivedBox->barang as $item)
                        <div class="flex justify-between items-center text-slate-300 border-b border-slate-900/80 pb-1 last:border-none">
                            <span>📦 <strong>{{ $item->kode_barang }}</strong> (Penerima: {{ $item->nama_penerima }})</span>
                            <span class="text-emerald-400">📲 {{ $item->no_wa_penerima }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-slate-900 mb-2">Box / Barang Dalam Perjalanan ke Hong Kong</h2>
            <p class="text-sm text-slate-500 mb-6">Klik "Terima Box" ketika fisik box tiba di lokasi cabang. Status barang akan berubah menjadi 'Sampai di HK' dan Notifikasi WA otomatis dikirim ke penerima.</p>

            <div class="mb-6 max-w-xs">
                <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal & Jam Sampai (Default: Sekarang)</label>
                <input type="datetime-local" wire:model="tanggal_sampai"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-mono font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($boxInTransit as $box)
                <div class="border border-slate-200 rounded-2xl p-5 bg-slate-50 space-y-4 hover:border-emerald-400 transition">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-sm font-black text-slate-900">{{ $box->kode_box }}</span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                            Transit Flight / Ship
                        </span>
                    </div>

                    <div class="text-xs text-slate-600 space-y-1">
                        <div>🇭🇰 Tujuan: <strong>{{ $box->cabangTujuan->nama_cabang }}</strong></div>
                        <div>📦 Jumlah Paket: <strong>{{ $box->barang->count() }} Barang</strong></div>
                    </div>

                    <!-- Items Preview -->
                    <div class="bg-white p-3 rounded-xl border border-slate-200 max-h-36 overflow-y-auto space-y-1 text-[11px]">
                        @foreach($box->barang as $b)
                        <div class="flex justify-between items-center text-slate-700">
                            <span class="font-mono font-bold">{{ $b->kode_barang }}</span>
                            <span>{{ $b->nama_penerima }} ({{ number_format($b->berat_kg, 2, ',', '.') }}kg)</span>
                        </div>
                        @endforeach
                    </div>

                    <button wire:click="terimaBox({{ $box->id }})"
                        class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition">
                        ✅ Terima Box & Kirim Notifikasi WA Sampai ➔
                    </button>
                </div>
                @empty
                <div class="col-span-2 text-center py-12 text-slate-400 text-sm bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    Tidak ada Box yang sedang transit ke cabang HK saat ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @else
    <!-- TAB 2: PENGAMBILAN BARANG OLEH CUSTOMER -->
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Daftar Barang Siap Diambil di HK</h2>
                    <p class="text-sm text-slate-500">Cari nomor resi atau nama penerima yang datang mengambil barang.</p>
                </div>

                <div class="w-full sm:w-72">
                    <input type="text" wire:model.live.debounce.300ms="searchResi" placeholder="Cari Kode Resi / Nama / WA..."
                        class="w-full px-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
            </div>

            <div class="mb-6 max-w-xs">
                <label class="block text-sm font-bold text-slate-700 mb-1">Tanggal & Jam Diambil</label>
                <input type="datetime-local" wire:model="tanggal_diambil"
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-mono font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b font-bold">
                        <tr>
                            <th class="p-4">Kode Barang</th>
                            <th class="p-4">Tanggal Sampai HK</th>
                            <th class="p-4">Penerima & WhatsApp</th>
                            <th class="p-4 text-right">Berat</th>
                            <th class="p-4 text-right">Biaya Bayar</th>
                            <th class="p-4 text-center">Aksi Pengambilan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangReadyList as $b)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-mono font-bold text-slate-900">{{ $b->kode_barang }}</td>
                            <td class="p-4 font-mono text-slate-600">
                                {{ $b->tanggal_sampai ? $b->tanggal_sampai->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-slate-900">{{ $b->nama_penerima }}</div>
                                <div class="text-sm text-slate-500 font-mono">{{ $b->no_wa_penerima }}</div>
                            </td>
                            <td class="p-4 text-right font-bold text-slate-900">{{ number_format($b->berat_kg, 2, ',', '.') }} kg</td>
                            <td class="p-4 text-right font-bold text-emerald-600">Rp {{ number_format($b->biaya_kirim, 0, ',', '.') }}</td>
                            <td class="p-4 text-center">
                                <button wire:click="prosesPengambilan({{ $b->id }})"
                                    class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl text-xs shadow transition">
                                    🤝 Proses Sudah Diambil ➔
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Tidak ada barang yang siap diambil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t">
                {{ $barangReadyList->links() }}
            </div>
        </div>

        <!-- History Goods Taken -->
        @if(count($barangTakenList) > 0)
        <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800">
            <h3 class="text-sm font-extrabold uppercase tracking-wider text-emerald-400 mb-4">10 Riwayat Pengambilan Terakhir</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-800 text-slate-300 font-bold border-b border-slate-700 uppercase">
                        <tr>
                            <th class="p-3">Kode Resi</th>
                            <th class="p-3">Nama Penerima</th>
                            <th class="p-3">Tgl Sampai</th>
                            <th class="p-3">Tgl Diambil</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($barangTakenList as $bt)
                        <tr>
                            <td class="p-3 font-mono font-bold text-white">{{ $bt->kode_barang }}</td>
                            <td class="p-3 text-slate-200">{{ $bt->nama_penerima }}</td>
                            <td class="p-3 font-mono text-slate-400">{{ $bt->tanggal_sampai ? $bt->tanggal_sampai->format('d/m/Y H:i') : '-' }}</td>
                            <td class="p-3 font-mono text-emerald-400 font-bold">{{ $bt->tanggal_diambil ? $bt->tanggal_diambil->format('d/m/Y H:i') : '-' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    Sudah Diambil
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
