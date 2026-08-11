<div>
    <x-slot name="title">Kantor Pusat Sorting & Boxing</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Gudang Sorting Kantor Pusat</h1>
        <p class="text-sm text-slate-500 mt-1">Barang dari seluruh cabang pengirim disortir dan dikemas ke dalam Box yang sama berdasarkan Cabang Tujuan Hong Kong.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Pack Box Baru -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between border-b pb-4 mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Kemas Barang ke Box / Manifest Baru</h2>
                        <p class="text-sm text-slate-500">Pilih cabang tujuan HK & centang barang yang akan dimasukkan ke box.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Cabang Tujuan Hong Kong</label>
                        <select wire:model.live="cabang_tujuan_id" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm font-semibold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            @foreach($cabangHk as $cb)
                            <option value="{{ $cb->id }}">🇭🇰 {{ $cb->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Kode Box (Otomatis)</label>
                        <input type="text" wire:model="kode_box" readonly
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm font-mono font-bold text-slate-700">
                    </div>
                </div>

                <!-- Table Unboxed Items -->
                <div class="border rounded-2xl overflow-hidden mb-6">
                    <div class="bg-slate-50 px-4 py-3 border-b text-sm font-bold text-slate-700 flex justify-between items-center">
                        <span>Pilih Barang untuk Dikelompokkan (Tujuan Sama)</span>
                        <span class="text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded font-mono">{{ count($selectedBarang) }} Dipilih</span>
                    </div>

                    <div class="max-h-80 overflow-y-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-100 text-slate-600 font-bold border-b">
                                <tr>
                                    <th class="p-3 w-10 text-center">#</th>
                                    <th class="p-3">Kode Resi</th>
                                    <th class="p-3">Cabang Asal</th>
                                    <th class="p-3">Penerima</th>
                                    <th class="p-3 text-right">Berat (kg)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse($barangAvailable as $b)
                                <tr class="hover:bg-slate-50">
                                    <td class="p-3 text-center">
                                        <input type="checkbox" value="{{ $b->id }}" wire:model.live="selectedBarang"
                                            class="rounded text-emerald-600 focus:ring-emerald-500">
                                    </td>
                                    <td class="p-3 font-mono font-bold text-slate-900">{{ $b->kode_barang }}</td>
                                    <td class="p-3">{{ $b->cabangAsal->nama_cabang }}</td>
                                    <td class="p-3 font-semibold">{{ $b->nama_penerima }}</td>
                                    <td class="p-3 text-right font-bold text-emerald-600">{{ number_format($b->berat_kg, 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-6 text-center text-slate-400">Tidak ada barang yang belum dikemas untuk cabang HK tujuan ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="createAndPackBox"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow transition"
                        @if(empty($selectedBarang)) disabled class="opacity-50 cursor-not-allowed" @endif>
                        📦 Pack Barang ke Box Baru ➔
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar List Box Aktif -->
        <div class="space-y-4">
            <div class="bg-slate-900 text-white rounded-3xl p-6 shadow-xl border border-slate-800">
                <h3 class="text-sm font-extrabold uppercase tracking-wider text-emerald-400 mb-4">Daftar Box Terkini</h3>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                    @forelse($boxActive as $box)
                    <div class="bg-slate-800/90 border border-slate-700/60 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-sm font-bold text-white">{{ $box->kode_box }}</span>
                            @if($box->status === 'dikemas')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">Dikemas</span>
                            @elseif($box->status === 'dalam_perjalanan')
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/20 text-blue-300 border border-blue-500/30">Transit HK</span>
                            @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Sampai HK</span>
                            @endif
                        </div>

                        <div class="text-xs text-slate-300 space-y-1">
                            <div>📍 Tujuan: <strong class="text-emerald-400">{{ $box->cabangTujuan->nama_cabang }}</strong></div>
                            <div>📦 Isi: <strong>{{ $box->barang->count() }} Paket Barang</strong></div>
                            <div class="text-xs text-slate-400 font-mono">&nbsp;Tgl Dikemas: {{ $box->tanggal_dikemas ? $box->tanggal_dikemas->format('d/m/Y H:i') : '-' }}</div>
                        </div>

                        @if($box->status === 'dikemas')
                        <button wire:click="kirimBox({{ $box->id }})"
                            class="w-full py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm rounded-xl shadow transition">
                            ✈️ Kirim Box ke Hong Kong ➔
                        </button>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-slate-500 text-center py-6">Belum ada Box yang dibuat.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
