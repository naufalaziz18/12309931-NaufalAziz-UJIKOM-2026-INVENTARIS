@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
    <div class="w-full pb-6">
        <div class="max-w-md mx-auto mb-4">
            <a href="{{ route('admin.categories.index') }}"
                class="inline-flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-bold text-[9px] tracking-widest transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" />
                </svg>
                KEMBALI KE LIST
            </a>
        </div>

        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
                {{-- Header --}}
                <div class="bg-slate-900 px-8 py-5 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-500/20 p-2 rounded-lg border border-emerald-500/30">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h1 class="text-lg font-black tracking-tight">Tambah Kategori</h1>
                    </div>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST" class="p-8 space-y-5">
                    @csrf

                    {{-- NAMA KATEGORI --}}
                    <div class="space-y-1">
                        <label class="block text-slate-400 font-bold text-[10px] uppercase tracking-widest ml-1">Nama
                            Kategori</label>
                        <input type="text" name="name"
                            class="w-full bg-slate-50 border-slate-200 px-4 py-2.5 rounded-xl focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none border text-sm font-bold text-slate-700 transition-all shadow-sm"
                            placeholder="Contoh: Elektronik / ATK" required>
                    </div>

                    {{-- DESKRIPSI SINGKAT (DIVISI PJ) --}}
                    <div class="space-y-1">
                        <label class="block text-slate-400 font-bold text-[10px] uppercase tracking-widest ml-1">Divisi
                            PJ</label>
                        <textarea name="division_pj" rows="3"
                            class="w-full bg-slate-50 border-slate-200 px-4 py-2.5 rounded-xl border text-sm text-slate-600 font-medium focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-500 outline-none transition-all resize-none shadow-sm"
                            placeholder="Tulis Nama PJ nya!" required></textarea>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="pt-2 flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-indigo-600 text-white font-black py-3 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 active:scale-95 text-[10px] uppercase tracking-widest">
                            SIMPAN KATEGORI
                        </button>
                        <a href="{{ route('admin.categories.index') }}"
                            class="px-6 bg-slate-100 text-slate-500 font-black py-3 rounded-xl text-center text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            BATAL
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection