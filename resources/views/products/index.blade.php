@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Executive Dashboard</h1>
        <p class="text-slate-500 text-xs font-medium uppercase tracking-widest mt-0.5">Inventory Oversight System</p>
    </div>
    <div class="flex items-center gap-2 text-xs font-bold text-slate-400 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
        <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
        DATA TER-UPDATE
    </div>
</div>

@php
    // Kalkulasi Global untuk Stats Row
    $globalBorrowed = $products->sum(fn($p) => $p->borrows->where('status', 'dipinjam')->sum('quantity'));
    $globalAvailable = $products->sum('total_stock');
    $globalMaxCapacity = $globalAvailable + $globalBorrowed;
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-indigo-600 p-5 rounded-2xl text-white shadow-lg shadow-indigo-200">
        <p class="opacity-80 text-[10px] font-bold uppercase tracking-wider mb-1">Total Jenis Barang</p>
        <h3 class="text-3xl font-black">{{ $products->count() }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Unit Tersedia</p>
        <h3 class="text-3xl font-black text-slate-800">{{ $globalAvailable }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Unit Dipinjam</p>
        <h3 class="text-3xl font-black text-rose-500">{{ $globalBorrowed }}</h3>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Kapasitas Maks</p>
        <h3 class="text-3xl font-black text-slate-800">{{ $globalMaxCapacity }}</h3>
    </div>
</div>

{{-- Tabel Monitoring --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 text-[10px] uppercase text-slate-400 font-bold border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Informasi Barang</th>
                    <th class="px-6 py-4 text-center">Tersedia (Sisa)</th>
                    <th class="px-6 py-4 text-center">Dipinjam</th>
                    <th class="px-6 py-4 text-center">Total Kapasitas</th>
                    <th class="px-6 py-4 text-right">Kondisi Stok</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($products as $product)
                @php
                    // Hitung peminjaman aktif sesuai status 'dipinjam'
                    $totalBorrowed = $product->borrows->where('status', 'dipinjam')->sum('quantity');
                    
                    // Kapasitas Maksimal = Sisa di tangan + Yang lagi dipinjam
                    $kapasitasMaks = $product->total_stock + $totalBorrowed;
                    
                    // Persentase ketersediaan
                    $percentage = $kapasitasMaks > 0 ? ($product->total_stock / $kapasitasMaks) * 100 : 0;
                    
                    $color = $percentage < 20 ? 'text-rose-500' : ($percentage < 50 ? 'text-amber-500' : 'text-emerald-500');
                @endphp
                <tr class="hover:bg-slate-50/50 transition-all">
                    <td class="px-6 py-4">
                        <p class="text-slate-800 font-bold text-sm leading-tight">{{ $product->name }}</p>
                        <p class="text-[9px] text-slate-400 uppercase">{{ $product->category->name ?? 'No Category' }}</p>
                    </td>
                    <td class="px-6 py-4 text-center font-bold">
                        <span class="text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md text-xs">
                            {{ $product->total_stock }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-bold">
                        <span class="{{ $totalBorrowed > 0 ? 'text-rose-600' : 'text-slate-300' }} text-sm">
                            {{ $totalBorrowed }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center text-slate-400 text-xs">
                        {{ $kapasitasMaks }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-black {{ $color }}">{{ number_format($percentage, 0) }}%</span>
                            <div class="w-16 h-1 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                <div class="h-full {{ str_replace('text', 'bg', $color) }}" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection