<div>
    <x-slot name="title">Input Penerimaan Barang Baru</x-slot>

    <!-- Header Title -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Form Penerimaan Barang (Cabang Asal)</h1>
            <p class="text-xs text-slate-500 mt-1">Input data barang yang diterima dari pengirim. Sistem akan menghitung Fee Cabang otomatis dan mengirimkan notifikasi WA ke penerima.</p>
        </div>
    </div>

    <!-- Success & WhatsApp Status Banner -->
    @if($lastInsertedBarang)
    <div class="mb-8 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-950 via-slate-900 to-emerald-950 text-white border-2 border-emerald-500/80 shadow-2xl relative overflow-hidden">
        <button wire:click="closeNotification" type="button" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-sm font-bold transition">
            ✕
        </button>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-4 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-emerald-500 text-slate-950 rounded-full font-black text-xs uppercase tracking-wider">
                        🎉 Barang Berhasil Diinput
                    </span>
                    <span class="text-xs font-mono text-emerald-300">
                        Kode Resi: <strong class="text-white text-base font-extrabold">{{ $lastInsertedBarang->kode_barang }}</strong>
                    </span>
                </div>

                <!-- Summary Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-900/60 p-4 rounded-2xl border border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px]">Penerima (HK)</span>
                        <strong class="text-slate-100 block">{{ $lastInsertedBarang->nama_penerima }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Tujuan HK</span>
                        <strong class="text-indigo-300 block">{{ $lastInsertedBarang->cabangTujuan->nama_cabang }}</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Berat & Fee</span>
                        <strong class="text-emerald-400 block">{{ number_format($lastInsertedBarang->berat_kg, 2, ',', '.') }} kg (Rp {{ number_format($lastInsertedBarang->fee_cabang, 0, ',', '.') }})</strong>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[10px]">Total Biaya</span>
                        <strong class="text-emerald-400 block">Rp {{ number_format($lastInsertedBarang->biaya_kirim, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- WhatsApp Status Indicator Box -->
                <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-xs font-bold">
                            @if($lastWaStatus)
                            <span class="inline-flex items-center text-emerald-400 space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <span>Status WA: BERHASIL TERKIRIM</span>
                            </span>
                            <span class="text-[10px] bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded font-mono border border-emerald-500/30">
                                {{ $lastWaLog && $lastWaLog->response_vendor === 'DEV_MODE_SIMULATED_SUCCESS' ? 'Mode Simulasi (Development)' : 'Gateway API Sent' }}
                            </span>
                            @else
                            <span class="inline-flex items-center text-rose-400 space-x-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                <span>Status WA: GAGAL TERKIRIM</span>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="text-xs text-slate-300">
                        Nomor WhatsApp Penerima: <strong class="font-mono text-emerald-400">{{ $lastInsertedBarang->no_wa_penerima }}</strong>
                    </div>

                    @if($lastWaLog)
                    <div x-data="{ openMsg: false }" class="mt-2 text-[11px]">
                        <button type="button" @click="openMsg = !openMsg" class="text-emerald-400 hover:text-emerald-300 font-semibold underline flex items-center space-x-1">
                            <span x-show="!openMsg">📱 Lihat Isi Pesan WhatsApp yang Dikirim ➔</span>
                            <span x-show="openMsg">📱 Sembunyikan Isi Pesan ⬆</span>
                        </button>
                        <div x-show="openMsg" x-transition class="mt-2 p-3 bg-slate-950 rounded-xl text-slate-300 font-mono text-[10px] whitespace-pre-wrap border border-slate-800 leading-relaxed">
                            {{ $lastWaLog->isi_pesan }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Print Action -->
            <div class="flex flex-col justify-center">
                <a href="{{ route('print.resi', $lastInsertedBarang->id) }}" target="_blank"
                    class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-slate-950 font-black text-xs rounded-2xl shadow-xl flex items-center justify-center space-x-2 transition transform active:scale-95 text-center">
                    <span class="text-base">🖨️</span>
                    <span>Cetak Resi Thermal Label (80mm)</span>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Card Container -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form wire:submit.prevent="simpan" class="space-y-8">
            
            <!-- Section 1: Cabang & Kategori Fee -->
            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">1. Cabang Pengirim</h3>
                    
                    <!-- Dynamic Badge ESB / ELP -->
                    <div class="px-3 py-1 rounded-full text-xs font-extrabold border shadow-sm {{ $kategori_cabang === 'ESB' ? 'bg-emerald-100 border-emerald-300 text-emerald-900' : 'bg-teal-100 border-teal-300 text-teal-900' }}">
                        Kategori {{ $kategori_cabang }} &bull; Fee Rp {{ number_format($fee_per_kg, 0, ',', '.') }} / kg
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Cabang Asal (Indonesia)</span>
                            @if($isCabangLocked)
                            <span class="text-[10px] bg-slate-200 text-slate-700 px-2 py-0.5 rounded font-normal lowercase tracking-normal">
                                🔒 otomatis cabang Anda (dikunci)
                            </span>
                            @endif
                        </label>
                        <select wire:model.live="cabang_asal_id"
                            @if($isCabangLocked) disabled @endif
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm @if($isCabangLocked) bg-slate-100/90 text-slate-700 cursor-not-allowed opacity-85 border-slate-300/80 @endif">
                            @foreach($cabangIndo as $cb)
                            <option value="{{ $cb->id }}">
                                {{ $cb->nama_cabang }} ({{ $cb->kategori_cabang }} - Fee Rp {{ number_format($cb->fee_per_kg, 0, ',', '.') }}/kg)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Cabang Tujuan (Hong Kong)</label>
                        <select wire:model="cabang_tujuan_id" class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 font-medium focus:ring-2 focus:ring-emerald-500 focus:outline-none text-sm">
                            @foreach($cabangHk as $cb)
                            <option value="{{ $cb->id }}">
                                🇭🇰 {{ $cb->nama_cabang }} ({{ $cb->alamat }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Pengirim & Penerima -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pengirim -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b pb-2">2. Data Pengirim (Indonesia)</h3>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Pengirim</label>
                        <input type="text" wire:model="nama_pengirim" required placeholder="Siti Aminah"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('nama_pengirim') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">No. WhatsApp Pengirim</label>
                        <input type="text" wire:model="no_wa_pengirim" required placeholder="081234567890"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('no_wa_pengirim') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Penerima -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider border-b pb-2">3. Data Penerima (Hong Kong)</h3>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Penerima (HK)</label>
                        <input type="text" wire:model="nama_penerima" required placeholder="Dewi Lestari"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('nama_penerima') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">No. WhatsApp Penerima (Notifikasi Otomatis)</label>
                        <input type="text" wire:model="no_wa_penerima" required placeholder="+85291234567 atau 0812xxx"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        <span class="text-[11px] text-emerald-600">⚡ WhatsApp notifikasi otomatis akan dikirim ke nomor ini saat disimpan.</span>
                        @error('no_wa_penerima') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Berat, Biaya, & Fee Calculation -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 text-white p-6 rounded-2xl space-y-6">
                <h3 class="text-sm font-bold text-emerald-400 uppercase tracking-wider">4. Rincian Berat & Biaya Kirim</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Berat Barang (Kg)</label>
                        <input type="number" step="0.01" min="0.1" wire:model.live="berat_kg" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-emerald-400 font-bold text-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('berat_kg') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Total Biaya Kirim (Rp)</label>
                        <input type="number" step="1000" min="0" wire:model="biaya_kirim" required
                            class="w-full px-4 py-3 rounded-xl bg-slate-950 border border-slate-700 text-emerald-400 font-bold text-lg focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                        @error('biaya_kirim') <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Estimasi Fee Cabang ({{ $kategori_cabang }})</label>
                        <div class="px-4 py-3 rounded-xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 font-bold text-lg">
                            Rp {{ number_format($fee_cabang, 0, ',', '.') }}
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-1">Rumus: {{ number_format((float)$berat_kg, 2, ',', '.') }} kg × Rp {{ number_format($fee_per_kg, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Catatan Khusus / Deskripsi Isi Barang</label>
                    <textarea wire:model="catatan" rows="2" placeholder="Contoh: Pakaian, makanan kering, dokumen..."
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100">
                <button type="submit"
                    class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition transform active:scale-95">
                    🚀 Simpan Data Barang & Kirim Notifikasi WA ➔
                </button>
            </div>
        </form>
    </div>
</div>
