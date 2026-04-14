@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Daftar Peminjaman</h1>
                <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-0.5">Monitoring Arus Barang
                    Keluar</p>
            </div>

            <div class="flex items-center gap-2">
                {{-- TOMBOL EKSPOR PDF (TAMBAHAN) --}}
                <a href="{{ route('operator.borrow.exportPdf') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-rose-600 hover:text-white transition-all shadow-sm active:scale-95">
                    <i class="fa-solid fa-file-pdf text-xs"></i>
                    PDF
                </a>

                {{-- Tombol Ekspor Excel --}}
                <a href="{{ route('operator.borrow.export') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95">
                    <i class="fa-solid fa-file-excel text-xs"></i>
                    Excel
                </a>

                {{-- Tombol Tambah --}}
                <a href="{{ route('operator.borrow.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Tambah
                </a>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto p-4">
                <table class="w-full border-separate border-spacing-y-3">
                    <thead>
                        <tr class="text-[10px] uppercase text-slate-400 font-black tracking-widest">
                            <th class="px-6 py-2 text-left">Peminjam & Barang</th>
                            <th class="px-6 py-2 text-center">Jumlah</th>
                            <th class="px-6 py-2 text-center">Periode</th>
                            <th class="px-6 py-2 text-center">Status</th>
                            <th class="px-6 py-2 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrows as $borrow)
                            <tr class="group hover:translate-x-1 transition-all duration-300">
                                {{-- User & Product Info --}}
                                <td
                                    class="px-6 py-5 bg-slate-50/50 group-hover:bg-white border-y border-l border-transparent group-hover:border-slate-200 rounded-l-[1.5rem]">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-sm">
                                            <span
                                                class="text-indigo-600 font-black text-sm">{{ strtoupper(substr($borrow->borrower_name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-800 leading-tight">
                                                {{ $borrow->borrower_name }}
                                            </p>
                                            <p class="text-[10px] font-bold text-indigo-500 uppercase mt-1">
                                                {{ $borrow->product->name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Qty --}}
                                <td
                                    class="px-6 py-5 bg-slate-50/50 group-hover:bg-white border-y border-transparent group-hover:border-slate-200 text-center">
                                    <span class="text-sm font-black text-slate-700">{{ $borrow->quantity }}</span>
                                    <span class="block text-[9px] text-slate-400 font-bold uppercase">Units</span>
                                </td>

                                {{-- Dates --}}
                                <td
                                    class="px-6 py-5 bg-slate-50/50 group-hover:bg-white border-y border-transparent group-hover:border-slate-200 text-center">
                                    <div class="flex flex-col items-center">
                                        {{-- Tanggal Pinjam + Jam --}}
                                        <span class="text-[10px] font-bold text-slate-600">
                                            {{ $borrow->created_at->format('d/m/Y') }}
                                            <span
                                                class="text-[9px] text-indigo-400 ml-0.5">{{ $borrow->created_at->format('H:i') }}
                                                WIB</span>
                                        </span>

                                        <i class="fa-solid fa-arrow-down text-[8px] my-0.5 text-slate-300"></i>

                                        {{-- Tenggat Kembali --}}
                                        <span class="text-[10px] font-black text-amber-600">
                                            {{ \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Status Badge --}}
                                <td
                                    class="px-6 py-5 bg-slate-50/50 group-hover:bg-white border-y border-transparent group-hover:border-slate-200 text-center">
                                    @if($borrow->status == 'dipinjam')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 text-rose-600 rounded-full text-[9px] font-black uppercase tracking-tighter border border-rose-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600 animate-pulse"></span>
                                            On Loan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-full text-[9px] font-black uppercase tracking-tighter border border-emerald-100">
                                            <i class="fa-solid fa-check"></i>
                                            Settled
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td
                                    class="px-6 py-5 bg-slate-50/50 group-hover:bg-white border-y border-r border-transparent group-hover:border-slate-200 rounded-r-[1.5rem] text-right">
                                    @if($borrow->status == 'dipinjam')
                                        <button type="button"
                                            onclick="confirmReturn('{{ $borrow->id }}', '{{ $borrow->product->name }}')"
                                            class="w-9 h-9 inline-flex items-center justify-center bg-white text-slate-400 hover:bg-emerald-500 hover:text-white hover:rotate-[360deg] rounded-xl transition-all duration-500 shadow-sm border border-slate-100 group/btn">
                                            <i class="fa-solid fa-rotate-left text-xs"></i>
                                        </button>

                                        <form id="return-form-{{ $borrow->id }}"
                                            action="{{ route('operator.borrow.return', $borrow->id) }}" method="POST"
                                            class="hidden">
                                            @csrf @method('PATCH')
                                        </form>
                                    @else
                                        <div class="w-9 h-9 inline-flex items-center justify-center text-emerald-300">
                                            <i class="fa-solid fa-circle-check text-xl"></i>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center opacity-20">
                                        <i class="fa-solid fa-box-open text-5xl mb-4"></i>
                                        <p class="text-xs font-black uppercase tracking-widest">Belum ada transaksi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 & FontAwesome --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        function confirmReturn(id, productName) {
            Swal.fire({
                title: 'Kembalikan Barang?',
                html: `Apakah barang <b>${productName}</b> sudah diterima kembali dengan kondisi baik?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo-600
                cancelButtonColor: '#94a3b8', // Slate-400
                confirmButtonText: 'Ya, Kembalikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                borderRadius: '1.5rem',
                customClass: {
                    title: 'text-xl font-black text-slate-800',
                    htmlContainer: 'text-sm text-slate-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebentar biar keren
                    Swal.fire({
                        title: 'Memproses...',
                        didOpen: () => { Swal.showLoading() },
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        borderRadius: '1.5rem'
                    });
                    document.getElementById('return-form-' + id).submit();
                }
            })
        }
    </script>
@endsection