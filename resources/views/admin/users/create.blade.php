@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Tambah User Baru</h2>
        <p class="text-slate-500 text-sm">Berikan akses sistem ke personil baru.</p>
    </div>

    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm max-w-2xl">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Role</label>
                <select name="role" class="w-full p-2 border rounded-lg">
                    <option value="operator">Operator</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full p-2 border rounded-lg" required>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Simpan User</button>
                <a href="{{ route('admin.users.index') }}" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-lg font-bold">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection