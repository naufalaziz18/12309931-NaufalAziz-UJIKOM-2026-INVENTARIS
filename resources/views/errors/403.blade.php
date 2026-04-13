<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Dibatasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-md w-full text-center">
        <div class="relative mb-8">
            <div class="absolute inset-0 bg-indigo-200 blur-3xl opacity-30 rounded-full"></div>
            <div
                class="relative inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl shadow-xl border border-slate-100">
                <span class="text-5xl">🔒</span>
            </div>
        </div>

        <h1 class="text-7xl font-extrabold text-slate-900 tracking-tighter mb-2">403</h1>
        <h2 class="text-2xl font-bold text-slate-800 mb-4">Area Terlarang!</h2>
        <p class="text-slate-500 leading-relaxed mb-10">
            Ups! Sepertinya Anda tersesat. Akun Anda tidak memiliki izin untuk melihat halaman ini.
            <span class="block font-semibold text-indigo-600 mt-2">Role Anda:
                {{ auth()->user()->role ?? 'Guest' }}</span>
        </p>

        <div class="flex flex-col gap-3">
            @auth
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('admin.items.index') }}"
                        class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                        Kembali ke Panel Admin
                    </a>
                @elseif(auth()->user()->role == 'operator')
                    <a href="{{ route('operator.borrow.index') }}"
                        class="px-8 py-4 bg-slate-800 text-white rounded-2xl font-bold text-sm uppercase tracking-widest shadow-lg shadow-slate-200 hover:bg-slate-900 transition-all">
                        Kembali ke Panel Operator
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                        Kembali ke Login
                    </a>
                @endif
            @endauth

            @guest
                <a href="{{ route('login') }}"
                    class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-sm uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                    Masuk ke Akun Anda (Login)
                </a>
            @endguest

            <button onclick="window.location.href='{{ route('login') }}'"
                class="text-slate-400 text-[10px] font-bold uppercase tracking-widest hover:text-slate-600 transition-colors mt-4">
                &larr; Batalkan dan Ke Login
            </button>
        </div>
    </div>

</body>

</html>