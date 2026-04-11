<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inv-Pro | Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box !important;
            transition: none !important;
        }

        [x-cloak] {
            display: none !important;
        }

        html {
            overflow-y: scroll;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F5F9;
            min-height: 100vh;
        }

        h1,
        h2,
        th {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-fixed {
            width: 240px !important;
            min-width: 240px !important;
            max-width: 240px !important;
            position: fixed !important;
            height: 100vh !important;
            left: 0;
            top: 0;
            z-index: 50;
        }

        .main-content {
            margin-left: 240px !important;
            width: calc(100% - 240px) !important;
            min-height: 100vh;
        }

        .swal-custom-popup {
            border-radius: 2.5rem !important;
            padding: 2.5rem !important;
        }

        .swal-confirm-btn {
            background-color: #4f46e5 !important;
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 700 !important;
        }

        .swal-cancel-btn {
            background-color: #f1f5f9 !important;
            color: #64748b !important;
            border-radius: 1rem !important;
            padding: 0.8rem 2rem !important;
            font-weight: 700 !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased">

    <div class="flex">

        {{-- SIDEBAR --}}
        <aside class="sidebar-fixed bg-slate-900 text-white p-5 flex flex-col shadow-2xl">

            {{-- BRAND LOGO --}}
            <div class="px-6 py-8">
                <h1 class="text-2xl font-black tracking-tighter italic">
                    INV<span class="text-indigo-500">-</span>PRO
                </h1>
                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-[0.3em] mt-1">Management System</p>
            </div>

            <div class="flex-grow overflow-y-auto">
                {{-- MENU DASHBOARD (SEMUA ROLE) --}}
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('products.index') ? 'text-white bg-slate-800' : 'text-slate-400' }} hover:text-white hover:bg-slate-800 rounded-2xl transition-all group mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Dashboard</span>
                </a>

                {{-- MENU KHUSUS OPERATOR --}}
                @if(auth()->user()->role == 'operator')
                    <a href="{{ route('operator.borrow.index') }}"
                        class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('operator.borrow.*') ? 'text-white bg-slate-800' : 'text-slate-400' }} hover:text-white hover:bg-slate-800 rounded-2xl transition-all group mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Peminjaman</span>
                    </a>
                @endif

                {{-- MENU KHUSUS ADMIN --}}
                @if(auth()->user()->role == 'admin')
                    <a href="{{ route('admin.items.index') }}"
                        class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('admin.items.*') ? 'text-white bg-slate-800' : 'text-slate-400' }} hover:text-white hover:bg-slate-800 rounded-2xl transition-all group mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Items</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center gap-3 px-6 py-3 {{ request()->routeIs('admin.categories.*') ? 'text-white bg-slate-800' : 'text-slate-400' }} hover:text-white hover:bg-slate-800 rounded-2xl transition-all group mb-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Categories</span>
                    </a>
                @endif

                {{-- MENU USERS (DROPDOWN) - FIXED STATE --}}
                {{-- MENU USERS (DROPDOWN) --}}
                <div x-data="{ open: {{ (request()->is('*profile*') || request()->is('*admin/users*')) ? 'true' : 'false' }} }"
                    class="mb-1">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-6 py-3 transition-all group rounded-2xl {{ (request()->is('*profile*') || request()->is('*admin/users*')) ? 'text-white bg-slate-800' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Users</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" :class="open ? 'rotate-180' : ''"
                            class="h-3 w-3 transition-transform duration-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition class="pl-12 pr-6 mt-1 space-y-1">
                        {{-- PROFIL SAYA (Semua Role Bisa Akses) --}}
                        <a href="{{ route('profile.edit') }}"
                            class="block py-2 text-[9px] font-bold uppercase tracking-widest transition-colors {{ request()->routeIs('profile.edit') ? 'text-indigo-400' : 'text-slate-500 hover:text-white' }}">
                            Profil Saya
                        </a>

                        {{-- SECTION ADMIN SLIM --}}
                        @if(auth()->user()->role == 'admin')
                                            <div class="mt-4 pt-3 border-t border-slate-800/40 space-y-0.5">

                                                <a href="{{ route('admin.users.index') }}"
                                                    class="flex items-center px-4 py-1.5 text-[11px] font-medium transition-all
                                                                        {{ request()->routeIs('admin.users.index')
                            ? 'text-indigo-400 border-l-2 border-indigo-400 bg-indigo-400/5'
                            : 'text-slate-500 hover:text-slate-200 hover:bg-slate-800/30 border-l-2 border-transparent' }}">
                                                    Data Admin
                                                </a>

                                                <a href="{{ route('admin.users.operator') }}"
                                                    class="flex items-center px-4 py-1.5 text-[11px] font-medium transition-all
                                                                        {{ request()->routeIs('admin.users.operator')
                            ? 'text-indigo-400 border-l-2 border-indigo-400 bg-indigo-400/5'
                            : 'text-slate-500 hover:text-slate-200 hover:bg-slate-800/30 border-l-2 border-transparent' }}">
                                                    Data Operator
                                                </a>
                                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BAGIAN BAWAH: USER INFO & LOGOUT --}}
            <div class="mt-auto pt-6 space-y-2 border-t border-slate-800">
                <div class="px-2">
                    <div class="bg-slate-800/40 rounded-2xl p-3 border border-slate-700/30 flex items-center gap-3">
                        <div
                            class="h-9 w-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white shadow-lg shadow-indigo-500/20 text-xs text-uppercase">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-white truncate leading-none mb-1">
                                {{ auth()->user()->name }}
                            </p>
                            <div class="flex items-center gap-1.5">
                                <span class="h-1 w-1 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[8px] font-black uppercase tracking-wider text-slate-500">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-6 py-3 text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 rounded-2xl transition-all group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em]">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="main-content p-4 md:p-8">
            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false,
                    customClass: { popup: 'swal-custom-popup' }
                });
            @endif

            window.confirmDelete = function(id) {
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        popup: 'swal-custom-popup',
                        confirmButton: 'swal-confirm-btn ml-3',
                        cancelButton: 'swal-cancel-btn'
                    }
                }).then((res) => {
                    if (res.isConfirmed) document.getElementById('del-' + id).submit();
                });
            }
        });
    </script>
</body>

</html>