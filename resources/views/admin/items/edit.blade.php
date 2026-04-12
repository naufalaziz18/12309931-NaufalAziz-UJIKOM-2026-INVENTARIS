@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Edit Informasi Barang</h1>
            <p class="text-slate-500 text-xs font-medium uppercase tracking-widest mt-0.5">Update data inventaris: {{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.items.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            <span class="text-xs font-bold uppercase">Batal</span>
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Barang --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Nama Barang</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-semibold text-slate-700">
                </div>

                {{-- Kategori --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Kategori</label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-semibold text-slate-700">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stok Saat Ini --}}
<div class="space-y-2">
    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Stok Saat Ini</label>
    {{-- Ganti $product->stock jadi $product->total_stock --}}
    <input type="number" name="stock" value="{{ $product->total_stock }}" required
        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-sm font-semibold text-slate-700">
</div>

                {{-- Update Foto --}}
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Ganti Foto (Opsional)</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:uppercase file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer">
                </div>
            </div>

            <div class="mt-10 flex items-center justify-end gap-3 border-t border-slate-100 pt-8">
                <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl text-[11px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection