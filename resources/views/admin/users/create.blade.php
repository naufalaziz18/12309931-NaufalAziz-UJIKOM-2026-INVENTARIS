@extends('layouts.app')

@section('content')
<style>
    .form-container {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .input-group-custom {
        margin-bottom: 1.5rem;
    }

    .input-custom {
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        border: 2px solid #f1f5f9;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .input-custom:focus {
        outline: none;
        border-color: #4f46e5;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        transform: translateY(-1px);
    }

    .label-custom {
        display: block;
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        margin-left: 4px;
    }

    .btn-save-pro {
        background: #4f46e5;
        color: white;
        padding: 16px 32px;
        border-radius: 14px;
        font-weight: 700;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
    }

    .btn-save-pro:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.5);
    }

    .header-create {
        background: linear-gradient(to right, #ffffff, #f8fafc);
        padding: 2rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="full-wrapper" style="padding: 2rem;">
    <div class="form-container" style="max-width: 900px; margin: 0 auto;">
        
        <div class="header-create">
            <div class="title-area">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                    <span style="width: 12px; height: 12px; background: #4f46e5; border-radius: 4px;"></span>
                    <h6 style="color: #4f46e5; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin: 0;">
                        User Account Creation
                    </h6>
                </div>
                <h2 style="margin: 0; font-weight: 800; color: #1e293b;">Tambah {{ ucfirst($role) }} Baru</h2>
            </div>
            
            <a href="{{ $role == 'admin' ? route('admin.users.index') : route('admin.users.operator') }}" 
               style="text-decoration: none; color: #64748b; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; background: #f1f5f9; transition: 0.3s;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div style="padding: 2.5rem;">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    
                    {{-- Nama --}}
                    <div style="grid-column: span 2;" class="input-group-custom">
                        <label class="label-custom">Nama Lengkap</label>
                        <input type="text" name="name" required placeholder="Contoh: Budi Santoso" class="input-custom">
                        @error('name') <span style="color: #ef4444; font-size: 12px; font-weight: 600;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email --}}
                    <div style="grid-column: span 2;" class="input-group-custom">
                        <label class="label-custom">Alamat Email</label>
                        <input type="email" name="email" required placeholder="budi@example.com" class="input-custom">
                        @error('email') <span style="color: #ef4444; font-size: 12px; font-weight: 600;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password --}}
                    <div class="input-group-custom">
                        <label class="label-custom">Password Baru</label>
                        <input type="password" name="password" required placeholder="Minimal 8 karakter" class="input-custom">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="input-group-custom">
                        <label class="label-custom">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Konfirmasi password" class="input-custom">
                    </div>

                </div>

                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn-save-pro">
                        <i class="fas fa-check-circle"></i>
                        Simpan Data {{ ucfirst($role) }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection