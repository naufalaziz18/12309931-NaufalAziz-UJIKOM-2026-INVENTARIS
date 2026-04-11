@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-end mb-8">
        <div>
            <a href="{{ route('products.index') }}"
                class="text-indigo-600 text-xs font-bold uppercase tracking-widest hover:text-indigo-800 transition-all flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Inventory
            </a>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Lending Records</h1>
            <p class="text-slate-400 text-xs font-medium">Monitoring data peminjaman untuk item: <span
                    class="text-indigo-600 uppercase">{{ $product->name }}</span></p>
        </div>

        {{-- TOMBOL EXCEL NYA DISINI BRAY --}}
        <a href="{{ route('products.export', $product->id) }}"
            class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl shadow-lg shadow-emerald-100 transition-all active:scale-95 text-xs font-black uppercase tracking-widest">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/50 text-[10px] uppercase text-slate-500 font-black border-b border-slate-100">
                    <tr>
                        <th class="px-8 py-5">#</th>
                        <th class="px-4 py-5">Item</th>
                        <th class="px-4 py-5 text-center">Total</th>
                        <th class="px-4 py-5">Name</th>
                        <th class="px-4 py-5">Ket.</th>
                        <th class="px-4 py-5">Date</th>
                        <th class="px-4 py-5 text-center">Returned</th>
                        <th class="px-8 py-5 text-right">Edited By</th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-50">
                    @forelse($product->borrows as $borrow)
                        <tr class="group hover:bg-slate-50/50 transition-all text-sm">
                            {{-- 1. # --}}
                            <td class="px-8 py-6 font-bold text-slate-400 italic">
                                {{ $loop->iteration }}
                            </td>

                            {{-- 2. Item --}}
                            <td class="px-4 py-6 font-bold text-slate-700 uppercase">
                                {{ $product->name }}
                            </td>

                            {{-- 3. Total --}}
                            <td class="px-4 py-6 text-center font-black text-slate-900">
                                {{ $borrow->quantity }}
                            </td>

                            {{-- 4. Name --}}
                            <td class="px-4 py-6 font-medium text-slate-600">
                                {{ $borrow->borrower_name }}
                            </td>

                            {{-- 5. Ket. --}}
                            <td class="px-4 py-6 text-slate-400 text-xs italic">
                                {{ $borrow->description ?? '-' }}
                            </td>

                            {{-- 6. Date --}}
                            <td class="px-4 py-6 text-slate-600">
                                {{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d F, Y') }}
                            </td>

                            {{-- 7. Status (Sesuai database lo: 'dikembalikan' vs 'dipinjam') --}}
                            <td class="px-4 py-6 text-center">
                                @if($borrow->status == 'dikembalikan')
                                    {{-- Status: Sudah Pulang --}}
                                    <div
                                        class="px-3 py-1 border border-emerald-200 text-emerald-500 rounded text-[10px] font-bold uppercase inline-block">
                                        returned
                                    </div>
                                @else
                                    {{-- Status: Selain 'dikembalikan', berarti masih dipinjam --}}
                                    <div
                                        class="px-3 py-1 border border-amber-200 text-amber-500 rounded text-[10px] font-bold inline-block uppercase">
                                        not returned
                                    </div>
                                @endif
                            </td>

                            {{-- 8. Edited By --}}
                            <td class="px-8 py-6 text-right font-black text-slate-900 lowercase italic">
                                {{ auth()->user()->name ?? 'operator wikrama' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="px-8 py-20 text-center text-slate-300 font-bold uppercase italic tracking-widest">
                                --- No Records Found ---
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection