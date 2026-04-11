@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto pb-10">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Tambah Barang Baru</h1>
                <p class="text-slate-500 text-xs font-medium uppercase tracking-widest mt-0.5">Input data inventaris ke sistem</p>
            </div>
            <a href="{{ route('admin.items.index') }}"
                class="flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:-translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                <span class="text-xs font-bold uppercase">Kembali</span>
            </a>
        </div>

        {{-- Form Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            {{-- PASTIIN ACTIONNYA KE STORE --}}
            <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="p-10">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Nama Barang --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Nama Barang</label>
                        <input type="text" name="name" required placeholder="Contoh: Laptop MacBook Pro"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 transition-all outline-none text-sm font-bold text-slate-700 shadow-sm">
                    </div>

                    {{-- Kategori --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Kategori</label>
                        <select name="category_id" required
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm font-bold text-slate-700 shadow-sm cursor-pointer">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stok Awal (SAMAKAN DENGAN DATABASE: total_stock) --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Stok Awal</label>
                        <input type="number" name="total_stock" required min="1" placeholder="0"
                            class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm font-bold text-slate-700 shadow-sm">
                    </div>

                    {{-- Gambar Barang --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Foto Barang</label>
                        <input type="file" name="image" accept="image/*"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-slate-900 file:text-white hover:file:bg-indigo-600 cursor-pointer transition-all shadow-sm">
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="mt-8 space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.15em] ml-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="4" placeholder="Tambahkan catatan spesifikasi atau kondisi barang..."
                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none text-sm font-bold text-slate-700 shadow-sm resize-none"></textarea>
                </div>

                {{-- Footer Form (Gak perlu tag form lagi di sini) --}}
                <div class="mt-10 flex items-center justify-end gap-3 border-t border-slate-100 pt-8">
                    <button type="reset"
                        class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-rose-500 transition-colors">
                        Reset Form
                    </button>
                    <button type="submit"
                        class="px-10 py-4 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-1 active:scale-95 transition-all">
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection