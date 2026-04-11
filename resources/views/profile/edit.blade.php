@extends('layouts.app') 

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- Header --}}
    <div class="mb-5 ml-1">
        <h2 class="text-xl font-bold text-slate-800 tracking-tight">Pengaturan Profil</h2>
        <p class="text-xs text-slate-500">Kelola informasi akun dan keamanan kata sandi Anda.</p>
    </div>

    {{-- Container Dua Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
        
        {{-- Section 1: Update Profil --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Informasi Pribadi</h3>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                        required>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" 
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all active:scale-95">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- Section 2: Ganti Password --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="p-2 bg-rose-50 rounded-lg text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Keamanan Akun</h3>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Password Saat Ini</label>
                    <input type="password" name="current_password" 
                        class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Password Baru</label>
                        <input type="password" name="password" 
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 ml-1">Konfirmasi</label>
                        <input type="password" name="password_confirmation" 
                            class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" 
                        class="px-5 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-xl shadow-md transition-all active:scale-95">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection