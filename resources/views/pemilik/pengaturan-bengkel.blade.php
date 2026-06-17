@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800">Pengaturan Bengkel</h1>
        <p class="text-gray-600 mt-2">Kelola informasi dan konfigurasi bengkel motor</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            ✗ {{ session('error') }}
        </div>
    @endif

    <!-- Form Pengaturan -->
    <div class="bg-white rounded-lg shadow p-6">
        
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

        <form method="POST" action="{{ route('pemilik.pengaturan.update') }}" class="space-y-6 max-w-3xl">
            @csrf
            @method('PUT')

            <!-- Nama Bengkel -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bengkel</label>
                <input type="text" name="nama_bengkel" value="{{ $pengaturan->get('nama_bengkel')?->value ?? old('nama_bengkel') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_bengkel') border-red-500 @enderror">
                @error('nama_bengkel')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Nama bengkel yang ditampilkan di nota dan dokumen</p>
            </div>

            <!-- Alamat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Bengkel</label>
                <textarea name="alamat" rows="3" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('alamat') border-red-500 @enderror">{{ $pengaturan->get('alamat')?->value ?? old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Alamat lengkap bengkel motor Anda</p>
            </div>

            <!-- No Telepon -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ $pengaturan->get('no_telepon')?->value ?? old('no_telepon') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telepon') border-red-500 @enderror">
                @error('no_telepon')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Nomor telepon yang dapat dihubungi</p>
            </div>

            <!-- Jam Operasional -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Operasional</label>
                <input type="text" name="jam_operasional" value="{{ $pengaturan->get('jam_operasional')?->value ?? old('jam_operasional') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jam_operasional') border-red-500 @enderror"
                    placeholder="Contoh: 08.00 - 17.00 WIB">
                @error('jam_operasional')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Format: HH.MM - HH.MM WIB</p>
            </div>

            <!-- Catatan Nota -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan di Nota Pembayaran</label>
                <textarea name="catatan_nota" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('catatan_nota') border-red-500 @enderror">{{ $pengaturan->get('catatan_nota')?->value ?? old('catatan_nota') }}</textarea>
                @error('catatan_nota')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Catatan yang muncul di bagian bawah nota pembayaran</p>
            </div>

            <!-- Divider -->
            <hr class="border-gray-300">

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded p-4">
                <p class="text-sm text-blue-800">
                    <strong>Informasi:</strong> Data pengaturan ini akan digunakan saat admin mencetak nota pembayaran. 
                    Pastikan semua informasi akurat dan lengkap.
                </p>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg">
                    💾 Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
