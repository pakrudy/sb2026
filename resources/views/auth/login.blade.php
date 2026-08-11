<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ekspedisi Sukses Bersama</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full font-sans antialiased text-slate-100 flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 px-4">
    <div class="max-w-md w-full">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-2xl shadow-emerald-500/20 mx-auto mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <h1 class="text-2xl font-extrabold tracking-tight text-white">EKSPEDISI SUKSES BERSAMA</h1>
            <p class="text-xs text-emerald-400 font-semibold tracking-widest uppercase mt-1">Sistem Logistik Indonesia ➔ Hong Kong</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/80 border border-slate-800 rounded-3xl p-8 shadow-2xl backdrop-blur-xl">
            <h2 class="text-lg font-bold text-slate-200 mb-6 text-center">Login Portal Staf & Admin</h2>

            @if($errors->any())
            <div class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 p-3 rounded-xl text-xs">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">Email Staf</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                        placeholder="contoh: admin@esb.com">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2 uppercase tracking-wider">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-slate-950/60 border border-slate-800 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-400">
                        <input type="checkbox" name="remember" class="rounded bg-slate-950 border-slate-800 text-emerald-500 focus:ring-emerald-500">
                        <span>Ingat Saya</span>
                    </label>
                    <a href="{{ route('tracking') }}" class="text-emerald-400 hover:underline">Halaman Lacak Resi Publik</a>
                </div>

                <button type="submit"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold rounded-xl text-sm shadow-lg shadow-emerald-500/25 transition transform active:scale-95">
                    Masuk ke Dashboard ➔
                </button>
            </form>
<!--
            <div class="mt-8 pt-6 border-t border-slate-800 text-center">
                <p class="text-xs text-slate-500">Demo Akun Credential:</p>
                <div class="mt-2 text-[11px] text-slate-400 space-y-1 font-mono">
                    <p>• Super Admin: <span class="text-emerald-400">admin@esb.com</span> / password</p>
                    <p>• Admin Indo ESB: <span class="text-emerald-400">esb.jkt@esb.com</span> / password</p>
                    <p>• Admin Indo ELP: <span class="text-emerald-400">elp.bdg@esb.com</span> / password</p>
                    <p>• Admin HK: <span class="text-emerald-400">hk.central@esb.com</span> / password</p>
                </div>
            </div>-->
        </div>
    </div>
</body>
</html>
