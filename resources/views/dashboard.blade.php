@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-indigo-600 text-[10px] font-bold uppercase tracking-[0.2em] mb-1">Overview</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Executive Dashboard</h1>
        </div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            SISTEM AKTIF
        </div>
    </div>

    {{-- Pakai Stats Card yang sama dengan Items biar Konsisten --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200/60 shadow-sm flex items-center gap-4 hover-lift">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Barang</p>
                <p class="text-2xl font-black text-slate-900">{{ $products->count() }}</p>
            </div>
        </div>
        {{-- Tambahin stats lain sesuai kebutuhan di sini --}}
    </div>

    {{-- Tabel Peminjaman --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="p-6 border-b border-slate-50">
            <h2 class="font-black text-slate-800 tracking-tight">Daftar Inventaris Terkini</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50/50 border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-4 text-[11px] font-bold text-slate-400 uppercase">Informasi Barang</th>
                        <th class="px-8 py-4 text-center text-[11px] font-bold text-slate-400 uppercase">Sisa</th>
                        <th class="px-8 py-4 text-right text-[11px] font-bold text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($products as $product)
                    <tr class="group hover:bg-slate-50/80 transition-all">
                        <td class="px-8 py-4">
                            <span class="text-sm font-black text-slate-700">{{ $product->name }}</span>
                        </td>
                        <td class="px-8 py-4 text-center">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-xs font-bold text-indigo-600">{{ $product->stock }}</span>
                        </td>
                        <td class="px-8 py-4 text-right">
                             @if($product->stock > 0)
                                <form action="{{ route('products.borrow', $product->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-xl text-xs font-bold transition-all active:scale-95 shadow-md shadow-indigo-100">
                                        Borrow
                                    </button>
                                </form>
                             @else
                                <span class="text-xs font-bold text-slate-300 italic">Out of Stock</span>
                             @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection