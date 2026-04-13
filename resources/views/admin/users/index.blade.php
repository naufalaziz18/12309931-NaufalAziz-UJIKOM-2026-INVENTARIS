@extends('layouts.app')

@section('content')
    <style>
        /* RESET & SYNC WITH SIDEBAR */
        .full-wrapper {
            background-color: #f4f7fe;
            min-height: 100vh;
            padding: 0;
            /* Menghilangkan padding utama agar full width */
        }

        /* HEADER FULL WIDTH */
        .header-section {
            background: #fff;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e3e6f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0;
        }

        .title-area h2 {
            font-weight: 800;
            color: #1a202c;
            /* Gelap elegan */
            margin: 0;
            font-size: 1.5rem;
        }

        /* CARD FULL WIDTH */
        .main-card {
            background: #fff;
            border: none;
            border-radius: 0;
            /* Kotak mentok agar terlihat menyatu */
            box-shadow: none;
            width: 100%;
        }

        /* TABLE STYLING SINKRON SIDEBAR */
        .inv-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inv-table thead {
            background-color: #1a202c;
            /* Warna gelap senada sidebar INV-PRO */
            color: #ffffff;
        }

        .inv-table th {
            padding: 1.2rem 2rem;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .inv-table td {
            padding: 1.2rem 2rem;
            border-bottom: 1px solid #f1f5f9;
            color: #4a5568;
        }

        /* HOVER INTERAKTIF */
        .inv-table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .inv-table tbody tr:hover {
            background-color: #f8faff;
            box-shadow: inset 8px 0 0 #4e73df;
            /* Garis biru saat di hover */
        }

        /* BADGE & AVATAR */
        .avatar-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #1a202c;
            /* Senada sidebar */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 1rem;
        }

        .badge-status {
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .status-admin {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-operator {
            background: #e0e7ff;
            color: #4338ca;
        }

        /* ACTION BUTTONS */
        .action-btn {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-edit:hover {
            border-color: #f59e0b;
            color: #f59e0b;
            background: #fffbeb;
        }

        .btn-delete:hover {
            border-color: #ef4444;
            color: #ef4444;
            background: #fef2f2;
        }

        .btn-add-pro {
            background: #4e73df;
            color: white !important;
            padding: 8px 16px;
            /* Ukuran dikurangin dikit */
            border-radius: 6px;
            /* Lebih kotak dikit biar tegas */
            font-weight: 600;
            /* Font jangan terlalu tebal biar gak 'berat' */
            font-size: 13px;
            /* Ukuran teks lebih kecil */
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            border: none;
        }

        .swal-title-custom {
            font-size: 18px !important;
            font-weight: 700 !important;
            color: #1e293b !important;
        }

        .swal-popup-custom {
            border-radius: 12px !important;
        }

        /* Tombol Excel khusus biar beda warnanya tapi tetep satu style */
        .btn-excel {
            background: #10b981;
        }

        .btn-add-pro:hover {
            background: #1a202c;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="full-wrapper">
        <div class="header-section">
            <div class="title-area">
                <h6
                    style="color: #4e73df; font-weight: bold; font-size: 11px; text-transform: uppercase; margin-bottom: 4px;">
                    System Management
                </h6>
                <h2>Daftar {{ $title }}</h2>
            </div>

            <div style="display: flex; gap: 8px;">
                {{-- TOMBOL EXPORT PDF (TAMBAHAN BARU) --}}
                {{-- Pakai logic yang sama buat nentuin role yang di-export --}}
                <a href="{{ route('admin.admin.users.export-pdf', ['role' => str_contains(strtolower($title), 'admin') ? 'admin' : 'operator']) }}"
                    class="btn-add-pro" style="background-color: #e11d48;"> {{-- Warna Merah Rose --}}
                    <i class="fas fa-file-pdf me-2" style="font-size: 14px;"></i>
                    PDF
                </a>

                {{-- TOMBOL EXPORT EXCEL (Sudah Ada) --}}
                <a href="{{ route('admin.admin.users.export', str_contains(strtolower($title), 'admin') ? 'admin' : 'operator') }}"
                    class="btn-add-pro btn-excel">
                    <i class="fas fa-file-excel me-2" style="font-size: 14px;"></i>
                    Excel
                </a>

                {{-- TOMBOL TAMBAH (Sudah Ada) --}}
                <a href="{{ route('admin.users.create', ['role' => str_contains(strtolower($title), 'admin') ? 'admin' : 'operator']) }}"
                    class="btn-add-pro">
                    <i class="fas fa-plus me-2" style="font-size: 12px;"></i>
                    Tambah {{ str_replace('Data ', '', $title) }}
                </a>
            </div>
        </div>

        <div class="main-card">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>User Information</th>
                        <th>Access Role</th>
                        <th>Registration Date</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr onclick="window.location='#';">
                            <td style="font-weight: bold; color: #94a3b8;">#{{ $loop->iteration }}</td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <div class="avatar-icon">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ $user->name }}</div>
                                        <div style="font-size: 12px; color: #64748b;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $user->role == 'admin' ? 'status-admin' : 'status-operator' }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 13px; font-weight: 600; color: #1e293b;">
                                    {{ $user->created_at->format('M d, Y') }}
                                </div>
                                <div style="font-size: 11px; color: #94a3b8;">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td style="text-align: center;" onclick="event.stopPropagation();">
                                <div style="display: flex; justify-content: center; gap: 10px;">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="action-btn btn-edit"
                                        title="Edit">
                                        <i class="fas fa-pen" style="font-size: 12px;"></i>
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin:0;"
                                        class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="action-btn btn-delete delete-btn">
                                            <i class="fas fa-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 100px; text-align: center; color: #94a3b8;">
                                <i class="fas fa-user-slash fa-3x mb-3"></i>
                                <p>Data {{ $title }} belum tersedia.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 2rem; background: #fff; border-top: 1px solid #f1f5f9;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: #64748b; font-size: 13px;">
                    Showing <b>{{ $users->count() }}</b> of <b>{{ $users->total() ?? $users->count() }}</b> total entries
                </div>
                <div>
                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        {{ $users->links() }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Hapus data ini bray?',
                    text: "Data yang dihapus nggak bakal bisa balik lagi lho!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', // Warna merah sesuai btn-delete kamu
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    background: '#ffffff',
                    customClass: {
                        title: 'swal-title-custom',
                        popup: 'swal-popup-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection