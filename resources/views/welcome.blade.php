<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inv-Pro | Solusi Inventaris Kantor Modern</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; }
        .font-poppins { font-family: 'Poppins', sans-serif; }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }
        
        .hero-gradient {
            background: radial-gradient(circle at top right, #f8faff 0%, #ffffff 40%);
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px -2px rgba(79, 70, 229, 0.3);
        }
    </style>
</head>
<body class="hero-gradient text-slate-800">

    <nav class="fixed w-full z-50 glass-nav">
        <div class="max-w-6xl mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="bg-indigo-600 p-1.5 rounded-lg shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-xl font-poppins font-extrabold tracking-tight text-slate-900">INV-PRO</span>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="px-5 py-2 btn-primary text-white font-semibold rounded-xl text-xs tracking-wide">
                        MASUK
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-16 px-6">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 text-center lg:text-left">
                <div class="inline-block px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full mb-4">
                    <span class="text-indigo-600 text-[10px] font-bold uppercase tracking-widest">Internal Inventory v2.0</span>
                </div>
                <h1 class="text-4xl lg:text-5xl font-poppins font-extrabold leading-tight mb-5 text-slate-900">
                    Kelola Aset <br> <span class="text-indigo-600">Tanpa Ribet.</span>
                </h1>
                <p class="text-base text-slate-500 mb-8 leading-relaxed max-w-lg mx-auto lg:mx-0">
                    Pantau laptop, kursi, hingga perlengkapan kantor dalam satu dashboard. Pinjam dan kembalikan aset hanya dengan satu klik.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="{{ route('login') }}" class="px-7 py-3 btn-primary text-white font-bold rounded-xl text-sm text-center">
                        Mulai Kelola
                    </a>
                    <a href="#features" class="px-7 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-50 transition-all text-center">
                        Pelajari Fitur
                    </a>
                </div>
            </div>
            
            <div class="lg:w-1/2 relative">
                <div class="relative bg-white p-3 rounded-[2rem] shadow-xl border border-slate-100">
                    <div class="bg-slate-50 rounded-[1.5rem] p-6 border border-slate-50">
                        <div class="flex gap-1.5 mb-4">
                            <div class="w-2.5 h-2.5 rounded-full bg-rose-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                        </div>
                        <div class="space-y-3">
                            <div class="h-6 bg-slate-200 rounded-lg w-3/4 animate-pulse"></div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="h-20 bg-indigo-50 rounded-xl animate-pulse"></div>
                                <div class="h-20 bg-emerald-50 rounded-xl animate-pulse delay-75"></div>
                            </div>
                            <div class="h-24 bg-slate-100 rounded-xl animate-pulse delay-150"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-poppins font-extrabold mb-3">Kenapa Pakai Inv-Pro?</h2>
                <div class="w-12 h-1 bg-indigo-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Aman & Terkendali</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Hanya admin yang bisa mengubah data utama. User hanya bisa pinjam dan lapor.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Cepat & Responsif</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Desain minimalis yang dioptimalkan untuk kecepatan kerja tim Anda.</p>
                </div>

                <div class="p-8 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-all group">
                    <div class="w-10 h-10 bg-amber-500 text-white rounded-xl flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Data Akurat</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">Log aktivitas lengkap memudahkan audit aset setiap akhir bulan.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-8 border-t border-slate-100 text-center">
        <p class="text-slate-400 font-bold text-[10px] tracking-widest uppercase">© 2026 Inv-Pro Internal Team.</p>
    </footer>

</body>
</html>