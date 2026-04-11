<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventaris | INV-PRO</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        h1, label { font-family: 'Poppins', sans-serif; }
        input[type="file"]::file-selector-button {
            background-color: #4f46e5;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 700;
            font-size: 10px;
            cursor: pointer;
            margin-right: 0.8rem;
            transition: all 0.2s;
        }
    </style>
</head>

<body class="bg-[#F1F5F9] h-screen w-screen flex items-center justify-center p-2">

    <div class="w-full max-w-xl flex flex-col">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold text-[10px] mb-2 transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            KEMBALI KE DASHBOARD
        </a>

        <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            {{-- HEADER --}}
            <div class="bg-slate-900 px-6 py-4 text-white flex justify-between items-center border-b border-white/5">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-500/20 p-2 rounded-lg border border-indigo-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-indigo-400 text-[9px] font-bold uppercase tracking-widest">Editor Mode</h2>
                        <h1 class="text-lg font-extrabold tracking-tight">Edit Detail Barang</h1>
                    </div>
                </div>
                <div class="bg-slate-800/50 px-3 py-1.5 rounded-lg border border-slate-700 font-mono text-[10px] font-bold text-indigo-300">
                    {{ $product->sku }}
                </div>
            </div>

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-3.5">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div>
                    <label class="block text-slate-700 font-bold mb-1 text-[10px] uppercase">Nama Barang</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                        class="w-full bg-slate-50 border-slate-200 px-3 py-2.5 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-700 font-semibold border text-sm"
                        placeholder="Contoh: MacBook Pro M3" required>
                </div>

                {{-- FOTO --}}
                <div>
                    <label class="block text-slate-700 font-bold mb-1 text-[10px] uppercase">Update Foto (Opsional)</label>
                    <div class="bg-slate-50 border border-dashed border-slate-300 p-2.5 rounded-xl flex items-center">
                        <input type="file" name="image" accept="image/*"
                            class="w-full text-[10px] text-slate-500 font-medium cursor-pointer">
                    </div>
                    @if($product->image)
                        <p class="text-[9px] text-slate-400 mt-1 italic leading-tight">*Pilih file baru jika ingin mengganti foto yang sudah ada</p>
                    @endif
                </div>

                {{-- STOK (Full Width) --}}
                <div>
                    <label class="block text-slate-700 font-bold mb-1 text-[10px] uppercase">Stok Tersedia</label>
                    <div class="relative w-full">
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" 
                            class="w-full bg-slate-50 border-slate-200 px-3 py-2.5 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-700 font-bold border text-sm"
                            required>
                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold text-[10px]">Unit / Pcs</span>
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label class="block text-slate-700 font-bold mb-1 text-[10px] uppercase">Deskripsi</label>
                    <textarea name="description" rows="2"
                        class="w-full bg-slate-50 border-slate-200 px-3 py-2 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium border text-xs"
                        placeholder="Tambahkan catatan jika perlu...">{{ old('description', $product->description) }}</textarea>
                </div>

                {{-- BUTTONS --}}
                <div class="pt-2 flex gap-2">
                    <button type="submit" 
                        class="flex-[2] bg-indigo-600 text-white font-extrabold py-3.5 rounded-xl hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all active:scale-95 text-[11px] uppercase tracking-wider">
                        Update Inventaris
                    </button>
                    <a href="{{ route('products.index') }}" 
                        class="flex-1 bg-slate-100 text-slate-500 font-extrabold py-3.5 rounded-xl hover:bg-slate-200 transition-all text-center text-[11px] uppercase tracking-wider">
                        Batal
                    </a>
                </div>
            </form>
        </div>
        
        <p class="text-center text-slate-400 text-[9px] mt-3 font-medium uppercase tracking-widest opacity-70">
            Automatic System Log Entry • 2026
        </p>
    </div>

</body>
</html>