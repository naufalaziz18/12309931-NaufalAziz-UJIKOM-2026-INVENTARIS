@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Management Items</h1>
            <p class="text-slate-400 text-xs font-medium">Kelola ketersediaan stok dan pantau data peminjaman barang.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Tombol PDF Baru --}}
            <a href="{{ route('admin.items.export.pdf') }}"
                class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Export PDF
            </a>
            <a href="{{ route('admin.items.export.all') }}"
                class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
            <a href="{{ route('products.create') }}"
                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Add Item
            </a>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 text-[10px] uppercase text-slate-500 font-black border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">#</th>
                        <th class="px-4 py-5">Preview</th>
                        <th class="px-4 py-5">Category</th>
                        <th class="px-4 py-5">Item Name</th>
                        <th class="px-4 py-5 text-center">Available Stock</th>
                        <th class="px-4 py-5 text-center">Lending Status</th>
                        <th class="px-8 py-5 text-right tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                        <tr class="group hover:bg-indigo-50/30 transition-all">
                            <td class="px-8 py-6">
                                <span
                                    class="text-xs font-bold text-slate-400 group-hover:text-indigo-600 transition-colors italic">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            {{-- KOLOM GAMBAR BARU --}}
                            <td class="px-4 py-6">
                                <div
                                    class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 border-2 border-white shadow-sm group-hover:border-indigo-100 transition-all">
                                    @if($product->image)
                                        {{-- Tambahkan cursor-zoom-in dan onclick --}}
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-cover cursor-zoom-in"
                                            onclick="previewImage('{{ asset('storage/' . $product->image) }}', '{{ $product->name }}')">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-50 text-slate-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-6">
                                <span
                                    class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-[10px] font-black uppercase tracking-wider group-hover:bg-white transition-all">
                                    {{ $product->category->name ?? 'General' }}
                                </span>
                            </td>

                            <td class="px-4 py-6">
                                <p
                                    class="text-sm font-black text-slate-800 tracking-tight leading-none uppercase group-hover:text-indigo-600 transition-colors">
                                    {{ $product->name }}
                                </p>
                            </td>

                            <td class="px-4 py-6 text-center">
                                <div class="inline-flex items-center px-3 py-1 bg-indigo-50 rounded-xl">
                                    <span class="text-sm font-black text-indigo-600">
                                        {{ $product->total_stock ?? 0 }}
                                    </span>
                                </div>
                            </td>



                            <td class="px-4 py-6 text-center">
                                @php
                                    // Paksa hitung pake count() PHP kalau Eloquent Collection-nya bandel
                                    // Kita hitung SEMUA riwayat peminjaman yang ada di collection tersebut
                                    $historyCount = count($product->borrows); 
                                @endphp

                                @if($historyCount > 0)
                                    <a href="{{ route('products.lendings.details', $product->id) }}"
                                        class="group/link inline-flex flex-col items-center">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-600 rounded-2xl font-black text-sm group-hover/link:bg-rose-600 group-hover/link:text-white transition-all shadow-sm border border-rose-100 group-hover/link:rotate-6">
                                            {{ $historyCount }}
                                        </div>
                                        <span
                                            class="text-[9px] text-rose-400 font-bold mt-1 uppercase tracking-widest group-hover/link:text-rose-600">
                                            Records
                                        </span>
                                    </a>
                                @else
                                    <div class="flex flex-col items-center opacity-30 grayscale">
                                        <div
                                            class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-400 rounded-2xl font-black text-sm border border-slate-200">
                                            0
                                        </div>
                                        <span class="text-[9px] text-slate-400 font-bold mt-1 uppercase tracking-widest">
                                            Empty
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="bg-white border border-slate-200 text-slate-600 hover:border-indigo-600 hover:text-indigo-600 px-4 py-2 rounded-xl text-[10px] font-black tracking-widest transition-all shadow-sm uppercase">
                                        Edit
                                    </a>
                                    <form id="del-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}"
                                        method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $product->id }}')"
                                            class="bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white p-2.5 rounded-xl transition-all shadow-sm active:scale-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-200" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-slate-300 font-black italic text-sm tracking-widest uppercase">--- NO ITEMS
                                        FOUND IN STORAGE ---</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .swal-custom-popup {
            border-radius: 2.5rem !important;
            padding: 2.5rem !important;
            border: 8px solid #f8fafc;
        }

        .swal-confirm-btn {
            background-color: #4f46e5 !important;
            color: white !important;
            border-radius: 1.2rem !important;
            padding: 0.8rem 2.5rem !important;
            font-weight: 800 !important;
            font-size: 0.8rem !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .swal-cancel-btn {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            border-radius: 1.2rem !important;
            padding: 0.8rem 2.5rem !important;
            font-weight: 800 !important;
            font-size: 0.8rem !important;
            text-transform: uppercase;
            margin-right: 10px;
        }
    </style>

    <script>
        window.previewImage = function (url, name) {
            Swal.fire({
                title: name,
                imageUrl: url,
                imageAlt: name,
                showConfirmButton: false,
                showCloseButton: true,
                background: '#ffffff',
                customClass: {
                    popup: 'swal-custom-popup', // Biar tetep pake style rounded lo yang keren
                    image: 'rounded-2xl border-4 border-slate-50 shadow-lg'
                }
            });
        }
    </script>
@endsection