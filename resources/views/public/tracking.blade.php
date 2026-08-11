<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pengiriman - Ekspedisi Sukses Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full font-sans antialiased text-slate-100 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950">
    <div class="min-h-full flex flex-col">
        <!-- Header -->
        <header class="border-b border-slate-800 bg-slate-950/80 backdrop-blur sticky top-0 z-50">
            <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
                <a href="{{ route('tracking') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-lg text-white tracking-tight">SUKSES BERSAMA</span>
                        <span class="block text-[10px] text-emerald-400 uppercase tracking-widest font-semibold">Ekspedisi Cargo ID ➔ HK</span>
                    </div>
                </a>

                <a href="{{ route('login') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-xl border border-slate-700 transition">
                    Login Staff ➔
                </a>
            </div>
        </header>

        <!-- Hero & Form Search -->
        <main class="flex-grow max-w-4xl w-full mx-auto px-4 py-12">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="inline-block px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-xs font-semibold mb-4">
                    ⚡ Lacak Pengiriman Indonesia ➔ Hong Kong
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Cek Posisi Paket & Status Barang Anda
                </h1>
                <p class="mt-3 text-slate-400 text-sm">
                    Masukkan Kode Barang / Resi (contoh: <code class="text-emerald-400 font-mono">ESB-20260810-0001</code>) atau Nomor WhatsApp Penerima.
                </p>
            </div>

            <!-- Search Form -->
            <form method="GET" action="{{ route('tracking') }}" class="max-w-xl mx-auto mb-12">
                <div class="relative flex items-center">
                    <input type="text" name="resi" value="{{ $resi }}" required
                        placeholder="Ketik Nomor Resi / WhatsApp Penerima..."
                        class="w-full pl-5 pr-36 py-4 rounded-2xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-base shadow-2xl transition">
                    <button type="submit"
                        class="absolute right-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold text-sm rounded-xl shadow-lg transition">
                        Lacak Paket
                    </button>
                </div>
            </form>

            <!-- Result Card -->
            @if($resi && !$barang)
            <div class="max-w-xl mx-auto bg-rose-950/20 border border-rose-800/40 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-2">🔍</div>
                <h3 class="text-base font-bold text-rose-300">Resi Tidak Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1">
                    Nomor resi "<span class="text-rose-400 font-mono">{{ $resi }}</span>" tidak terdaftar di sistem. Mohon periksa kembali kode resi Anda.
                </p>
            </div>
            @endif

            @if($barang)
            <div class="bg-slate-900/90 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
                <!-- Top Summary Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-800 gap-4">
                    <div>
                        <span class="text-xs font-mono uppercase tracking-widest text-slate-400">Kode Barang / Resi</span>
                        <h2 class="text-2xl font-black text-white font-mono tracking-wider">{{ $barang->kode_barang }}</h2>
                        <div class="mt-1 flex items-center space-x-2 text-xs">
                            <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 font-medium">
                                Cabang Asal: {{ $barang->cabangAsal->nama_cabang }} ({{ $barang->kategori_cabang_snapshot }})
                            </span>
                            <span class="text-slate-500">➔</span>
                            <span class="px-2.5 py-1 rounded-md bg-indigo-950 text-indigo-300 border border-indigo-800 font-medium">
                                HK: {{ $barang->cabangTujuan->nama_cabang }}
                            </span>
                        </div>
                    </div>

                    <div class="text-left sm:text-right">
                        <span class="text-xs text-slate-400 block">Status Saat Ini:</span>
                        @php
                            $statusBadges = [
                                'diterima_di_cabang' => ['bg-yellow-500/20 border-yellow-500/40 text-yellow-300', 'Diterima di Cabang'],
                                'dalam_perjalanan_ke_pusat' => ['bg-blue-500/20 border-blue-500/40 text-blue-300', 'Ke Hub Pusat'],
                                'disortir_di_pusat' => ['bg-purple-500/20 border-purple-500/40 text-purple-300', 'Disortir di Pusat'],
                                'dalam_pengiriman_hk' => ['bg-indigo-500/20 border-indigo-500/40 text-indigo-300', 'Pengiriman ke HK'],
                                'sampai_di_hk' => ['bg-teal-500/20 border-teal-500/40 text-teal-300', 'Tiba di Cabang HK'],
                                'sudah_diambil' => ['bg-emerald-500/20 border-emerald-500/40 text-emerald-300', 'Sudah Diambil'],
                            ];
                            $badge = $statusBadges[$barang->status] ?? ['bg-slate-800 text-slate-300', $barang->status];
                        @endphp
                        <span class="inline-block mt-1 px-4 py-1.5 rounded-full text-xs font-bold border {{ $badge[0] }}">
                            ● {{ $badge[1] }}
                        </span>
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-b border-slate-800 text-xs">
                    <div>
                        <span class="text-slate-500 block">Penerima</span>
                        <span class="font-bold text-slate-200 text-sm block mt-0.5">{{ $barang->nama_penerima }}</span>
                        <span class="text-slate-400 font-mono">{{ $barang->no_wa_penerima }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Pengirim</span>
                        <span class="font-bold text-slate-200 text-sm block mt-0.5">{{ $barang->nama_pengirim }}</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Berat Paket</span>
                        <span class="font-bold text-emerald-400 text-sm block mt-0.5">{{ number_format($barang->berat_kg, 2, ',', '.') }} kg</span>
                    </div>
                    <div>
                        <span class="text-slate-500 block">Biaya Kirim</span>
                        <span class="font-bold text-emerald-400 text-sm block mt-0.5">Rp {{ number_format($barang->biaya_kirim, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="pt-6">
                    <h3 class="text-sm font-bold text-slate-300 uppercase tracking-wider mb-6">Riwayat Perjalanan Barang</h3>
                    
                    <div class="relative pl-6 border-l-2 border-slate-800 space-y-6">
                        @foreach($barang->riwayatStatus as $log)
                        <div class="relative group">
                            <!-- Bullet Icon -->
                            <div class="absolute -left-[31px] top-1.5 w-4 h-4 rounded-full border-2 border-slate-900 bg-emerald-500 group-first:bg-emerald-400 group-first:ring-4 group-first:ring-emerald-500/20"></div>
                            
                            <div>
                                <div class="flex items-center space-x-3">
                                    <span class="font-bold text-sm text-slate-100">{{ $log->keterangan }}</span>
                                </div>
                                <span class="text-xs text-slate-400 block mt-1 font-mono">
                                    {{ $log->created_at->format('d M Y, H:i') }} WIB
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </main>
    </div>
</body>
</html>
