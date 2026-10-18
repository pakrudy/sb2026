<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ekspedisi Sukses Bersama' }} - Express Indo to HK</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN Fallback & Vite -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        hk: {
                            50: '#eef2ff',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50">
    <div class="min-h-full flex flex-col" x-data="{ mobileMenuOpen: false }">
        <!-- Top Header Navigation Bar -->
        <nav class="bg-gradient-to-r from-slate-900 via-slate-800 to-emerald-950 text-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Brand Logo -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-md group-hover:scale-105 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <span class="font-extrabold text-base sm:text-lg tracking-tight bg-gradient-to-r from-white via-emerald-200 to-teal-300 bg-clip-text text-transparent">
                                    SUKSES BERSAMA
                                </span>
                                <span class="block text-[9px] sm:text-[10px] uppercase tracking-widest text-emerald-400 font-semibold">
                                    Expedisi Cargo Indo ➔ HK
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation Links -->
                    @auth
                    @php
                        $navUser = Auth::user();
                        $isSuperAdmin = $navUser && $navUser->isSuperAdmin();
                        $isAdminCabangIndo = $navUser && ($navUser->isAdminCabangIndo() || ($navUser->cabang && $navUser->cabang->tipe_lokasi === 'indonesia'));
                        $isAdminPusat = $navUser && ($navUser->isAdminPusat() || ($navUser->cabang && $navUser->cabang->kategori_cabang === 'PUSAT'));
                        $isAdminCabangHk = $navUser && ($navUser->isAdminCabangHk() || ($navUser->cabang && $navUser->cabang->tipe_lokasi === 'hongkong'));
                    @endphp

                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            Dashboard
                        </a>

                        {{-- === Desktop: Super Admin sees a grouped dropdown === --}}
                        @if($isSuperAdmin)
                        <div class="relative group">
                            <button class="px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-1 transition {{ (request()->routeIs('input-barang') || request()->routeIs('daftar-barang') || request()->routeIs('sorting-pusat') || request()->routeIs('cabang-hk')) ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                📦 Proses Barang
                                <svg class="w-3 h-3 transform group-hover:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="absolute left-0 mt-1 hidden group-hover:block bg-slate-900 border border-slate-800 rounded-lg shadow-lg z-50 min-w-max">
                                <a href="{{ route('input-barang') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('input-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">➕ Terima Barang</a>
                                <a href="{{ route('daftar-barang') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">📦 Status Barang</a>
                                <a href="{{ route('sorting-pusat') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">🏢 Hub Pusat (Boxing)</a>
                                <a href="{{ route('cabang-hk') }}" class="block px-4 py-2 text-sm {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">🇭🇰 Cabang HK</a>
                            </div>
                        </div>
                        @else
                        {{-- Non-super-admin: flat links per role --}}
                        @if($isAdminCabangIndo)
                        <a href="{{ route('input-barang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('input-barang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            ➕ Terima Barang
                        </a>
                        @endif

                        <a href="{{ route('daftar-barang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            📦 Status Barang
                        </a>

                        @if($isAdminPusat)
                        <a href="{{ route('sorting-pusat') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            🏢 Hub Pusat (Boxing)
                        </a>
                        @endif

                        @if($isAdminCabangHk)
                        <a href="{{ route('cabang-hk') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            🇭🇰 Cabang HK
                        </a>
                        @endif
                        @endif

                        @if($isSuperAdmin)
                        <a href="{{ route('kelola-cabang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('kelola-cabang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            ⚙️ Kelola Cabang
                        </a>
                        @endif
                    @if($isSuperAdmin)
                    <div class="relative group">
    <button class="px-3 py-2 rounded-lg text-sm font-medium flex items-center gap-1 transition {{ (request()->routeIs('laporan-fee-cabang') || request()->routeIs('laporan-status-barang')) ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        📊 Laporan
        <svg class="w-3 h-3 transform group-hover:rotate-180 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div class="absolute left-0 mt-1 hidden group-hover:block bg-slate-900 border border-slate-800 rounded-lg shadow-lg">
        <a href="{{ route('laporan-fee-cabang') }}" class="block px-3 py-2 text-sm {{ request()->routeIs('laporan-fee-cabang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Fee Cabang</a>
        <a href="{{ route('laporan-status-barang') }}" class="block px-3 py-2 text-sm {{ request()->routeIs('laporan-status-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Status Barang</a>
    </div>
</div>
@endif
</div>

                    <!-- User Dropdown Menu -->
                    <div class="flex items-center gap-2">
                        <div class="relative" x-data="{ userMenuOpen: false }" @keydown.escape.window="userMenuOpen = false">
                            <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-slate-800 transition group">
                                <!-- Avatar -->
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-400 flex items-center justify-center text-white font-extrabold text-sm shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="text-right hidden sm:block">
                                    <div class="text-xs font-bold text-slate-100 leading-tight">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-emerald-400 font-medium">
                                        {{ Auth::user()->cabang ? Auth::user()->cabang->nama_cabang : 'Super Admin' }}
                                    </div>
                                </div>
                                <svg class="w-3 h-3 text-slate-400 hidden sm:block transition-transform" :class="userMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Panel -->
                            <div x-show="userMenuOpen"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                @click.outside="userMenuOpen = false"
                                style="display:none"
                                class="absolute right-0 mt-2 w-56 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden z-50">

                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-slate-800">
                                    <div class="text-xs font-bold text-slate-100">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono truncate">{{ Auth::user()->email }}</div>
                                </div>

                                <div class="py-1.5">
                                    <!-- Ganti Password — semua user -->
                                    <a href="{{ route('ganti-password') }}"
                                        @click="userMenuOpen = false"
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('ganti-password') ? 'bg-slate-800 text-white' : '' }}">
                                        <span class="text-base">🔐</span>
                                        <span class="font-medium">Ganti Password</span>
                                    </a>

                                    @if($isSuperAdmin)
                                    <!-- Kelola User — super admin only -->
                                    <a href="{{ route('kelola-user') }}"
                                        @click="userMenuOpen = false"
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition {{ request()->routeIs('kelola-user') ? 'bg-slate-800 text-white' : '' }}">
                                        <span class="text-base">👥</span>
                                        <span class="font-medium">Kelola Akun User</span>
                                    </a>
                                    @endif
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-slate-800 py-1.5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 transition">
                                            <span class="text-base">🚪</span>
                                            <span class="font-medium">Keluar / Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Hamburger Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-slate-300 hover:text-white hover:bg-slate-800 focus:outline-none md:hidden transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @else
                    <div>
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-semibold text-sm shadow transition">
                            Login Staff / Admin
                        </a>
                    </div>
                    @endauth

                </div>
            </div>

            <!-- Mobile Dropdown Navigation Menu -->
            @auth
            <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-slate-900 border-b border-slate-800 px-4 pt-2 pb-4 space-y-2 shadow-2xl" style="display: none;">
                <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 mb-2 flex items-center justify-between">
                    <span>Navigasi {{ Auth::user()->cabang ? Auth::user()->cabang->nama_cabang : 'Super Admin' }}</span>
                    <span class="text-emerald-400 font-mono">{{ Auth::user()->name }}</span>
                </div>
                <a href="{{ route('dashboard') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    📊 Dashboard
                </a>

                @if($isSuperAdmin)
                {{-- Super Admin: grouped submenu --}}
                <div class="space-y-1">
                    <span class="block px-3 py-2.5 text-sm font-medium text-slate-300">📦 Proses Barang</span>
                    <a href="{{ route('input-barang') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('input-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">➕ Terima Barang</a>
                    <a href="{{ route('daftar-barang') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">📦 Status Barang</a>
                    <a href="{{ route('sorting-pusat') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">🏢 Hub Pusat (Boxing)</a>
                    <a href="{{ route('cabang-hk') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">🇭🇰 Cabang HK</a>
                </div>
                @else
                {{-- Non-super-admin: flat links per role --}}
                @if($isAdminCabangIndo)
                <a href="{{ route('input-barang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('input-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    ➕ Terima Barang Baru
                </a>
                @endif

                <a href="{{ route('daftar-barang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    📦 Status Barang
                </a>

                @if($isAdminPusat)
                <a href="{{ route('sorting-pusat') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    🏢 Hub Pusat (Boxing)
                </a>
                @endif

                @if($isAdminCabangHk)
                <a href="{{ route('cabang-hk') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    🇭🇰 Cabang HK
                </a>
                @endif
                @endif

                @if($isSuperAdmin)
                <a href="{{ route('kelola-cabang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('kelola-cabang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    ⚙️ Kelola Cabang
                </a>
                <a href="{{ route('kelola-user') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('kelola-user') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    👥 Kelola Akun User
                </a>
                @endif

                <div class="pt-2 border-t border-slate-800 space-y-1">
                @if($isSuperAdmin)
                    <div class="space-y-1">
    <span class="block px-3 py-2.5 text-sm font-medium text-slate-300">📊 Laporan</span>
    <a href="{{ route('laporan-fee-cabang') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('laporan-fee-cabang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Fee Cabang</a>
    <a href="{{ route('laporan-status-barang') }}" class="block pl-6 pr-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('laporan-status-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">Status Barang</a>
</div>
                    @endif
                    <a href="{{ route('ganti-password') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('ganti-password') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        🔐 Ganti Password
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-medium text-rose-400 hover:bg-rose-950/40 hover:text-rose-300 transition flex items-center space-x-2">
                            <span>🚪 Keluar / Logout</span>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </nav>

        <!-- Main Content Area -->
        <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">✅</span>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <span class="text-xl">⚠️</span>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 border-t border-slate-800 text-slate-400 py-6 text-center text-xs">
            <div class="max-w-7xl mx-auto px-4">
                <p>&copy; {{ date('Y') }} <strong>Ekspedisi Sukses Bersama</strong>. Sistem Pengiriman Cargo Indonesia ➔ Hong Kong.</p>
<!--                <p class="mt-1 text-slate-500">Dual Category Fee System: ESB (Rp 1.000/kg) &bull; ELP (Rp 2.000/kg)</p>-->
            </div>
        </footer>
    </div>

    @livewireScripts
</body>
</html>
