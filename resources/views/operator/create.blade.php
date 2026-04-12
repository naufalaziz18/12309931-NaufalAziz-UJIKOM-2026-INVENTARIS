@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-8">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('operator.borrow.index') }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-slate-800 transition-all group">
                <div class="p-2 rounded-xl group-hover:bg-white group-hover:shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-10">
                {{-- Header --}}
                <div class="mb-10 flex justify-between items-start">
                    <div>
                        <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Peminjaman Baru</h1>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                            Input data peminjam & daftar barang
                        </p>
                    </div>
                </div>

                {{-- Mulai Form --}}
                <form action="{{ route('operator.borrow.store.alt') }}" method="POST" id="main-form" class="space-y-8">
                    @csrf
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                            <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2">Terjadi Kesalahan:
                            </p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li class="text-xs font-bold text-rose-500">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-12 gap-6 pb-8 border-b border-dashed border-slate-100">
                        {{-- Nama Peminjam --}}
                        <div class="col-span-8 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama
                                Peminjam</label>
                            <input type="text" name="borrower_name" required placeholder="Masukkan nama lengkap..."
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 rounded-2xl outline-none text-xs font-bold text-slate-700 transition-all">
                        </div>

                        {{-- Estimasi Kembali (Gue pindahin ke dalem form biar aman) --}}
                        <div class="col-span-4 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Estimasi
                                Kembali</label>
                            <input type="date" name="return_date" required
                                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 rounded-2xl outline-none text-[10px] font-bold text-slate-700 transition-all">
                        </div>
                    </div>

                    {{-- List Barang Dinamis --}}
                    <div id="items-container" class="space-y-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Barang yang
                                Dipinjam</label>
                            <button type="button" onclick="addItem()"
                                class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-emerald-100 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Barang
                            </button>
                        </div>

                        {{-- Row Barang 1 --}}
                        <div class="item-row bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 space-y-4">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-8">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Pilih
                                        Barang</label>
                                    <select name="items[0][product_id]" required
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                                        <option value="" disabled selected>Pilih Barang</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} (Stok: {{ $product->total_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-4">
                                    <label
                                        class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Jumlah</label>
                                    <input type="number" name="items[0][quantity]" min="1" value="1" required
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 text-center transition-all">
                                </div>
                            </div>
                            <div>
                                <label
                                    class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Keterangan</label>
                                <input type="text" name="items[0][note]" placeholder="Contoh: Untuk keperluan Lab..."
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full py-5 bg-slate-900 text-white rounded-[1.8rem] text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-slate-200 hover:bg-emerald-600 hover:scale-[1.01] transition-all duration-300">
                            Konfirmasi Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let itemCount = 1;
        function addItem() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = "item-row bg-slate-50/50 p-6 rounded-[2rem] border border-slate-100 space-y-4 animate-in fade-in slide-in-from-top-4 duration-500 relative";
            newRow.innerHTML = `
                    <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-8 h-8 bg-white border border-slate-200 text-red-500 rounded-full flex items-center justify-center shadow-sm hover:bg-red-50 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-8">
                            <label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Pilih Barang</label>
                            <select name="items[${itemCount}][product_id]" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                                <option value="" disabled selected>Pilih Barang</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stok: {{ $product->total_stock }}) 
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Jumlah</label>
                            <input type="number" name="items[${itemCount}][quantity]" min="1" value="1" required
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 text-center transition-all">
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-400 uppercase mb-1 block ml-1">Keterangan Barang Ini</label>
                        <input type="text" name="items[${itemCount}][note]" placeholder="Catatan spesifik..."
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl outline-none text-xs font-bold text-slate-700 focus:ring-2 focus:ring-emerald-500/10 transition-all">
                    </div>
                `;
            container.appendChild(newRow);
            itemCount++;
        }
    </script>
@endsection