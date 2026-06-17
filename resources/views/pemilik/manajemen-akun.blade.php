@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Akun</h1>
        <p class="text-gray-600 mt-2">Kelola akun pengguna aplikasi bengkel</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel User -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar User</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Email</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Role</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Status</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($user->role === 'admin')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-red-500">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                        Pemilik
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($user->is_active)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-green-500">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-red-500">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center flex-wrap">
                                    <form method="POST" action="{{ route('pemilik.manajemen-akun.toggle', $user) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} user ini?')" 
                                            class="px-3 py-1 text-xs font-semibold rounded {{ $user->is_active ? 'bg-red-500 hover:bg-red-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                            {{ $user->is_active ? '❌ Nonaktifkan' : '✓ Aktifkan' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('pemilik.manajemen-akun.reset-password', $user) }}" style="display:inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Reset password user ini ke bengkel123?')" 
                                            class="px-3 py-1 text-xs font-semibold rounded bg-blue-500 hover:bg-blue-600 text-white">
                                            🔑 Reset Password
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada user lain
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Tambah User -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Tambah User Baru</h2>
        
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded p-4 mb-4">
                <p class="text-red-700 font-semibold mb-2">Error validasi:</p>
                <ul class="text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pemilik.manajemen-akun.store') }}" class="space-y-4 max-w-2xl">
            @csrf

            <!-- Nama -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama User</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                <select name="role" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('role') border-red-500 @enderror">
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pemilik" {{ old('role') === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Button -->
            <div>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                    ➕ Tambah User
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
