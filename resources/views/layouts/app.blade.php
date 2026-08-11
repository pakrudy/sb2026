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

                        @if($isSuperAdmin || $isAdminCabangIndo)
                        <a href="{{ route('input-barang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('input-barang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            ➕ Terima Barang
                        </a>
                        @endif

                        <a href="{{ route('daftar-barang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            📦 Status Barang
                        </a>

                        @if($isSuperAdmin || $isAdminPusat)
                        <a href="{{ route('sorting-pusat') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            🏢 Hub Pusat (Boxing)
                        </a>
                        @endif

                        @if($isSuperAdmin || $isAdminCabangHk)
                        <a href="{{ route('cabang-hk') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            🇭🇰 Cabang HK
                        </a>
                        @endif

                        @if($isSuperAdmin)
                        <a href="{{ route('kelola-cabang') }}" class="px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('kelola-cabang') ? 'bg-emerald-600/30 text-emerald-300 border border-emerald-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            ⚙️ Kelola Cabang
                        </a>
                        @endif
                    </div>

                    <!-- User Account & Mobile Toggle Button -->
                    <div class="flex items-center space-x-3">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs font-bold text-slate-100">{{ Auth::user()->name }}</div>
                            <div class="text-[10px] text-emerald-400 font-medium">
                                {{ Auth::user()->cabang ? Auth::user()->cabang->nama_cabang : 'Super Admin' }}
                            </div>
                        </div>

                        <!-- Desktop Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                            @csrf
                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-400 hover:bg-slate-800 transition" title="Keluar / Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>

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

                @if($isSuperAdmin || $isAdminCabangIndo)
                <a href="{{ route('input-barang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('input-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    ➕ Terima Barang Baru
                </a>
                @endif

                <a href="{{ route('daftar-barang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('daftar-barang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    📦 Status Barang
                </a>

                @if($isSuperAdmin || $isAdminPusat)
                <a href="{{ route('sorting-pusat') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('sorting-pusat') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    🏢 Hub Pusat (Boxing)
                </a>
                @endif

                @if($isSuperAdmin || $isAdminCabangHk)
                <a href="{{ route('cabang-hk') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('cabang-hk') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    🇭🇰 Cabang HK
                </a>
                @endif

                @if($isSuperAdmin)
                <a href="{{ route('kelola-cabang') }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('kelola-cabang') ? 'bg-emerald-600 text-white font-bold' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    ⚙️ Kelola Cabang
                </a>
                @endif

                <div class="pt-2 border-t border-slate-800">
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
