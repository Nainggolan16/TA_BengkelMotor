<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenAkunController extends Controller
{
    /**
     * Display list of users.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('pemilik.manajemen-akun', [
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'role' => 'required|in:admin,pemilik',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama user harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'role.required' => 'Role harus dipilih',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return back()->with('success', 'User baru berhasil ditambahkan');
    }

    /**
     * Toggle user active status.
     */
    public function toggleStatus(User $user)
    {
        // Guard: pemilik tidak bisa nonaktifkan dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun Anda sendiri');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->name} berhasil {$status}");
    }

    /**
     * Reset user password to default.
     */
    public function resetPassword(User $user)
    {
        $user->update(['password' => Hash::make('bengkel123')]);

        return back()->with('success', "Password user {$user->name} berhasil direset ke 'bengkel123'");
    }
}
