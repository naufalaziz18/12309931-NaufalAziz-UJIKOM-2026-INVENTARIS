<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Inv-Pro System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        .bg-pattern {
            background-color: #fcfdfe;
            background-image: radial-gradient(#e2e8f0 0.8px, transparent 0.8px);
            background-size: 20px 20px;
        }
        /* Animasi halus pas loading */
        .btn-loading {
            pointer-events: none;
            opacity: 0.8;
        }
    </style>
</head>

<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-[380px]">
        <div class="text-center mb-6">
            <div class="inline-flex bg-indigo-600 p-2 rounded-xl shadow-lg mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h1 class="text-2xl font-poppins font-extrabold text-slate-900 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-400 font-semibold mt-1 text-[11px] uppercase tracking-widest">Akses Dashboard Inventaris</p>
        </div>

        <div class="bg-white rounded-[1.5rem] shadow-xl shadow-slate-200/60 p-7 border border-slate-100">

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4" id="loginForm">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-4 rounded-lg">
                        <p class="text-[11px] font-bold text-red-600 uppercase tracking-tight">
                            {{ $errors->first() }}
                        </p>
                    </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email Kantor</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-xl outline-none transition-all font-semibold text-sm text-slate-700 placeholder:text-slate-300 placeholder:font-normal"
                        placeholder="nama@perusahaan.com">
                </div>

                <div>
                    <div class="flex justify-between mb-1.5 ml-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Password</label>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 focus:border-indigo-500 focus:bg-white rounded-xl outline-none transition-all font-semibold text-sm text-slate-700 placeholder:text-slate-300"
                        placeholder="••••••••">
                </div>

                <button type="submit" id="btnSubmit"
                    class="w-full py-3.5 bg-indigo-600 text-white font-poppins font-bold rounded-xl shadow-md shadow-indigo-100 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all active:scale-[0.98] text-sm tracking-wide flex items-center justify-center gap-2">
                    <span id="btnText">MASUK SEKARANG</span>
                </button>
            </form>
        </div>

        <p class="text-center mt-8 text-slate-400 text-[10px] font-bold uppercase tracking-widest opacity-60">
            INV-PRO SYSTEM &copy; 2026
        </p>
    </div>

    <script>
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('btnSubmit');
        const btnText = document.getElementById('btnText');

        form.addEventListener('submit', function() {
            // Tambahin feedback visual pas klik masuk
            btn.classList.add('btn-loading');
            btnText.innerText = 'MEMPROSES...';
            
            // Mencegah double click
            setTimeout(() => {
                btn.disabled = true;
            }, 50);
        });

        console.log('CSRF Token: {{ csrf_token() }}');
    </script>
</body>
</html>