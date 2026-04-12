@extends('layouts.app')

@section('content')
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-indigo-600 text-[10px] font-bold uppercase tracking-[0.2em] mb-1">Inventory Management</p>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kategori Barang</h1>
        </div>
        <div class="flex items-center gap-3">
    {{-- Tombol PDF --}}
    <a href="{{ route('admin.categories.export-pdf') }}"
        class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm active:scale-95">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        Export PDF
    </a>
            <a href="{{ route('admin.categories.export') }}"
                class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>

            <a href="{{ route('admin.categories.create') }}"
                class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-100 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Add Kategori
            </a>  
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider w-20 text-center">No</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">PJ Divisi</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Total Produk</th>
                        <th class="px-8 py-5 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($categories as $category)
                        <tr class="group hover:bg-slate-50/80 transition-all">
                            <td class="px-8 py-5 text-center text-sm font-bold text-slate-400">{{ $loop->iteration }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 tracking-tight">{{ $category->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 uppercase tracking-wider">
                                    {{ $category->division_pj }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    {{ $category->products_count ?? 0 }} Items
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-1">
                                    {{-- TOMBOL EDIT --}}
                                    <button type="button" 
                                        onclick="openEditModal('{{ $category->id }}', '{{ $category->name }}', '{{ $category->division_pj }}')" 
                                        class="relative z-10 p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-xl transition-all cursor-pointer" 
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    
                                    <form id="del-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete('{{ $category->id }}')" 
                                            class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-8 py-12 text-center text-slate-400">Belum ada data kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-5 border-t border-slate-50 bg-slate-50/30 text-center">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Showing {{ $categories->count() }} Categories</p>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditCategory" class="fixed inset-0 z-[9999] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
            <div class="relative bg-white rounded-[2rem] shadow-2xl max-w-lg w-full p-8 border border-slate-100">
                <div class="mb-6 text-left">
                    <h3 class="text-2xl font-black text-slate-900">Edit Kategori</h3>
                    <p class="text-sm text-slate-500">Ubah informasi kategori di bawah ini.</p>
                </div>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4 text-left">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2">Nama Kategori</label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-indigo-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2">PJ Divisi</label>
                            <input type="text" name="division_pj" id="edit_division_pj" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-slate-700 outline-none focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeEditModal()" class="flex-1 py-3 font-bold text-slate-500 bg-slate-100 rounded-xl">Batal</button>
                        <button type="submit" class="flex-1 py-3 font-bold text-white bg-indigo-600 rounded-xl shadow-lg">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openEditModal(id, name, pj) {
        const modal = document.getElementById('modalEditCategory');
        const form = document.getElementById('editForm');
        
        if (modal && form) {
            form.action = `/admin/categories/${id}`;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_division_pj').value = pj;
            modal.classList.remove('hidden');
        }
    }

    function closeEditModal() {
        document.getElementById('modalEditCategory').classList.add('hidden');
    }

    function confirmDelete(id) {
        if (confirm('Yakin ingin menghapus?')) {
            document.getElementById('del-' + id).submit();
        }
    }
</script>
@endpush