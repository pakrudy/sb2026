<x-app-layout>
    <x-slot name="title">Dashboard Overview</x-slot>

    @php
        $user = Auth::user();
        $isCabangUser = $user && $user->cabang_id && !$user->isSuperAdmin();
        $userCabang = $user ? $user->cabang : null;
        $isHkBranchUser = $isCabangUser && $userCabang && $userCabang->tipe_lokasi === 'hongkong';
        $isPusatUser = $isCabangUser && $userCabang && $userCabang->kategori_cabang === 'PUSAT';

        $barangQuery = \App\Models\Barang::query();
        if ($isCabangUser) {
            if ($isHkBranchUser) {
                $barangQuery->where('cabang_tujuan_id', $user->cabang_id);
            } else {
                $barangQuery->where('cabang_asal_id', $user->cabang_id);
            }
        }

        $totalBarang = (clone $barangQuery)->count();
        $totalKg = (clone $barangQuery)->sum('berat_kg');

        if ($isCabangUser) {
            $userFeeTotal = (clone $barangQuery)->sum('fee_cabang');
        } else {
            $totalFeeEsb = \App\Models\Barang::where('kategori_cabang_snapshot', 'ESB')->sum('fee_cabang');
            $totalFeeElp = \App\Models\Barang::where('kategori_cabang_snapshot', 'ELP')->sum('fee_cabang');
        }

        $barangProgres = (clone $barangQuery)->whereNotIn('status', ['sudah_diambil'])->count();
        $barangDone = (clone $barangQuery)->where('status', 'sudah_diambil')->count();

        $recentBarang = (clone $barangQuery)->with(['cabangAsal', 'cabangTujuan'])->latest()->take(6)->get();
    @endphp

    <!-- Welcome Hero Banner -->
    <div class="mb-8 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
            <svg class="w-72 h-72 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>

        <div class="relative z-10">
            <span class="inline-block px-3 py-1 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-full text-xs font-semibold mb-3">
                📍 Logistik Express Indonesia ➔ Hong Kong
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ $user->name }}!</h1>
            
            @if($isCabangUser && $userCabang)
            <p class="mt-2 text-slate-300 text-sm max-w-2xl">
                Dashboard Operasional <strong>{{ $userCabang->nama_cabang }}</strong> 
                @if(!$isHkBranchUser)
                . Kategori Cabang: <strong class="text-emerald-400">{{ $userCabang->kategori_cabang }} (Fee Rp {{ number_format($userCabang->fee_per_kg, 0, ',', '.') }}/kg)</strong>.
                @else
                (Lokasi Tujuan Hong Kong).
                @endif
            </p>
            @else
            <p class="mt-2 text-slate-300 text-sm max-w-2xl">
                Aplikasi Pengelolaan Pengiriman Barang "Ekspedisi Sukses Bersama".
            </p>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                @if(!$isHkBranchUser && !$isPusatUser)
                <a href="{{ route('input-barang') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-xl shadow-lg transition">
                    ➕ Input Penerimaan Barang Baru
                </a>
                @endif

                <a href="{{ route('daftar-barang') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 transition">
                    📦 Lihat Data Barang {{ $isCabangUser ? 'Cabang Saya' : '' }}
                </a>

                @if(!$isCabangUser || $isPusatUser || $user->isAdminPusat())
                <a href="{{ route('sorting-pusat') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 transition">
                    🏢 Sortir & Packing Box Pusat
                </a>
                @endif

                @if(!$isCabangUser || $isHkBranchUser || $user->isAdminCabangHk())
                <a href="{{ route('cabang-hk') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl shadow-lg transition">
                    🇭🇰 Pengambilan Barang HK
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 {{ $isCabangUser ? ($isHkBranchUser ? 'lg:grid-cols-2' : 'lg:grid-cols-3') : 'lg:grid-cols-4' }} gap-5 mb-8">
        <!-- Card 1: Total Barang -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    Total Barang {{ $isCabangUser ? 'Cabang' : '' }}
                </span>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalBarang) }}</div>
                <span class="text-[11px] text-slate-500 mt-1 block">Total kg: <strong class="text-emerald-600">{{ number_format($totalKg, 2, ',', '.') }} kg</strong></span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl font-bold">
                📦
            </div>
        </div>

        @if($isCabangUser && $userCabang && !$isHkBranchUser)
        <!-- Single Card for Indonesia Origin Branch Admin: Fee Cabang Saya -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">
                    Total Fee Cabang {{ $userCabang->kategori_cabang }}
                </span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($userFeeTotal, 0, ',', '.') }}</div>
                <span class="text-[11px] text-slate-500 mt-1 block">Tarif: Rp {{ number_format($userCabang->fee_per_kg, 0, ',', '.') }} / kg</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center text-xs font-black">
                {{ $userCabang->kategori_cabang }}
            </div>
        </div>
        @elseif(!$isCabangUser)
        <!-- Card 2: Fee Cabang ESB (Super Admin) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Fee Cabang ESB</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($totalFeeEsb, 0, ',', '.') }}</div>
                <span class="text-[11px] text-slate-500 mt-1 block">Fee Rp 1.000 / kg</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center text-xs font-black">
                ESB
            </div>
        </div>

        <!-- Card 3: Fee Cabang ELP (Super Admin) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-teal-600 uppercase tracking-wider">Fee Cabang ELP</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">Rp {{ number_format($totalFeeElp, 0, ',', '.') }}</div>
                <span class="text-[11px] text-slate-500 mt-1 block">Fee Rp 2.000 / kg</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center text-xs font-black">
                ELP
            </div>
        </div>
        @endif

        <!-- Card 4: Aktif Dalam Perjalanan / Proses -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Aktif Dalam Proses</span>
                <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($barangProgres) }}</div>
                <span class="text-[11px] text-emerald-600 font-semibold mt-1 block">Selesai: {{ number_format($barangDone) }} barang</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl font-bold">
                ✈️
            </div>
        </div>
    </div>

    <!-- Recent Shipments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Pengiriman Terbaru {{ $isCabangUser && $userCabang ? "({$userCabang->nama_cabang})" : '(Seluruh Cabang)' }}
                </h2>
                <p class="text-xs text-slate-500">
                    6 data pengiriman barang terakhir {{ $isCabangUser ? ($isHkBranchUser ? 'yang ditujukan ke cabang HK Anda.' : 'yang diterima di cabang Anda.') : 'yang diinput ke aplikasi.' }}
                </p>
            </div>
            <a href="{{ route('daftar-barang') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                Lihat Semua Data ➔
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-100 font-bold">
                    <tr>
                        <th class="p-4">Kode Resi</th>
                        <th class="p-4">Cabang Asal (Kategori)</th>
                        <th class="p-4">Tujuan HK</th>
                        <th class="p-4">Penerima</th>
                        <th class="p-4 text-right">Berat (kg)</th>
                        <th class="p-4 text-right">Fee Cabang</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentBarang as $b)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="p-4 font-mono font-bold text-slate-900">{{ $b->kode_barang }}</td>
                        <td class="p-4">
                            <span class="font-medium text-slate-800">{{ $b->cabangAsal->nama_cabang }}</span>
                            <span class="ml-1 inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $b->kategori_cabang_snapshot === 'ESB' ? 'bg-emerald-100 text-emerald-800' : 'bg-teal-100 text-teal-800' }}">
                                {{ $b->kategori_cabang_snapshot }}
                            </span>
                        </td>
                        <td class="p-4 font-medium text-indigo-700">{{ $b->cabangTujuan->nama_cabang }}</td>
                        <td class="p-4">
                            <div class="font-bold text-slate-900">{{ $b->nama_penerima }}</div>
                            <div class="text-[11px] text-slate-500 font-mono">{{ $b->no_wa_penerima }}</div>
                        </td>
                        <td class="p-4 text-right font-bold text-slate-800">{{ number_format($b->berat_kg, 2, ',', '.') }} kg</td>
                        <td class="p-4 text-right font-bold text-emerald-600">Rp {{ number_format($b->fee_cabang, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @php
                                $statusBadges = [
                                    'diterima_di_cabang' => ['bg-yellow-100 text-yellow-800', 'Diterima Cabang'],
                                    'dalam_perjalanan_ke_pusat' => ['bg-blue-100 text-blue-800', 'Ke Hub Pusat'],
                                    'disortir_di_pusat' => ['bg-purple-100 text-purple-800', 'Sortir Pusat'],
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
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-slate-400 text-xs">Belum ada data barang di cabang ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
