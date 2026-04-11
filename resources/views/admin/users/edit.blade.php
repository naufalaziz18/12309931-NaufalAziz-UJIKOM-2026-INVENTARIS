@extends('layouts.app')

@section('content')
<style>
    /* Full Height & No Scroll */
    .main-wrapper {
        background-color: #f4f7fe;
        height: 100vh; /* Paksa tinggi layar pas */
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .top-bar {
        background: #fff;
        padding: 1.5rem 2.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e3e8f0;
        flex-shrink: 0;
    }

    /* Form Area */
    .content-area {
        padding: 2rem 2.5rem;
        flex-grow: 1;
        overflow-y: auto; /* Kalau layar kekecilan baru scroll di sini */
    }

    .form-box {
        background: #fff;
        border-radius: 15px;
        border: 1px solid #edf2f7;
        box-shadow: 0 10px 25px rgba(0,0,0,0.02);
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
    }

    .form-header {
        background: #1a202c; /* Navy Pro */
        padding: 1.2rem 2rem;
        color: #fff;
    }

    .form-body {
        padding: 2rem;
    }

    /* Grid System Manual buat Input */
    .input-grid {
        display: grid;
        grid-template-columns: 1fr 1fr; /* Bagi dua kolom */
        gap: 1.5rem;
    }

    .form-group { margin-bottom: 0; }

    .form-label {
        font-weight: 700;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        transition: 0.3s;
        font-size: 0.95rem;
    }

    .form-input:focus {
        outline: none;
        border-color: #4e73df;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    /* Action Buttons */
    .footer-actions {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel {
        padding: 12px 25px;
        border-radius: 10px;
        font-weight: 700;
        text-decoration: none;
        color: #718096;
        background: #edf2f7;
        transition: 0.2s;
    }

    .btn-cancel:hover { background: #e2e8f0; color: #1a202c; }

    .btn-update {
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 700;
        color: #fff;
        background: #4e73df;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    }

    .btn-update:hover { background: #2e59d9; transform: translateY(-2px); }

    /* Responsive Kolom jadi 1 kalo layar HP */
    @media (max-width: 768px) {
        .input-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="main-wrapper">
    <div class="top-bar">
        <div>
            <span style="color: #4e73df; font-weight: 800; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">User Settings</span>
            <h2 style="margin: 0; font-weight: 800;">Konfigurasi Akun</h2>
        </div>
        <div style="font-size: 13px; color: #a0aec0;">
            ID Pengguna: <span style="font-weight: bold; color: #1a202c;">#{{ $user->id }}</span>
        </div>
    </div>

    <div class="content-area">
        <div class="form-box">
            <div class="form-header">
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-user-edit me-3" style="font-size: 1.2rem; opacity: 0.8;"></i>
                    <h5 style="margin: 0; font-weight: 700; font-size: 1rem;">Perbarui Informasi {{ $title }}</h5>
                </div>
            </div>

            <div class="form-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="input-grid">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Level Akses</label>
                            <select name="role" class="form-input">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator System</option>
                                <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Staff Operator</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="password" class="form-input" placeholder="Isi hanya jika ingin ganti">
                        </div>
                    </div>

                    <div class="footer-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-update">
                            <i class="fas fa-save me-2"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection