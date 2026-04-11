<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Menampilkan daftar Admin
     */
    public function index()
    {
        $users = User::where('role', 'admin')->latest()->paginate(10);
        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Data Admin'
        ]);
    }

    public function indexAdmin()
    {
        $users = User::where('role', 'admin')->paginate(10);

        // Kirim variabel 'title' di sini
        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Data Admin' // Ini yang bikin error kalau gak ada
        ]);
    }

    public function indexOperator()
    {
        $users = User::where('role', 'operator')->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Data Operator'
        ]);
    }

    /**
     * Export Excel berdasarkan role
     */
    public function exportExcel($role)
    {
        $fileName = 'data-' . $role . '-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new UsersExport($role), $fileName);
    }

    /**
     * Form Tambah User
     */
    public function create()
    {
        return view('admin.users.create', [
            'title' => 'Tambah User'
        ]);
    }

    /**
     * Simpan User Baru (Oleh Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,operator',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan Form Edit Profil (Diri Sendiri)
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update Profil & Password (Diri Sendiri)
     * Digunakan oleh Admin & Operator
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // 2. Kalau password diisi, validasi password lama & baru
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules);

        // 3. Update Name & Email
        $user->name = $request->name;
        $user->email = $request->email;

        // 4. Update Password kalau ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil dan password berhasil diperbarui!');
    }

    /**
     * Hapus User
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}