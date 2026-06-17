@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-3xl font-bold text-gray-800">
        Dashboard Admin
    </h1>

    <p class="text-gray-500 mt-2">
        Selamat datang kembali. Kelola transaksi dan operasional bengkel dari sini.
    </p>

    <p class="text-sm text-gray-400 mt-1">
        {{ now()->format('l, d F Y') }}
    </p>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-gray-500 text-white p-5 rounded-lg shadow">
        <h2 class="text-sm uppercase tracking-wide opacity-80">
            Total Pelanggan
        </h2>

        <p class="text-4xl font-bold mt-3">
            {{ $totalPelanggan }}
        </p>
    </div>

    <div class="bg-gray-500 text-white p-5 rounded-lg shadow">
        <h2 class="text-sm uppercase tracking-wide opacity-80">
            Total Kendaraan
        </h2>

        <p class="text-4xl font-bold mt-3">
            {{ $totalKendaraan }}
        </p>
    </div>

    <div class="bg-gray-500 text-black p-5 rounded-lg shadow">
        <h2 class="text-sm uppercase tracking-wide opacity-80">
            Total Transaksi
        </h2>

        <p class="text-4xl font-bold mt-3">
            {{ $totalOrder }}
        </p>
    </div>

    <div class="bg-gray-500 text-black p-5 rounded-lg shadow">
        <h2 class="text-sm uppercase tracking-wide opacity-80">
            Total Sparepart
        </h2>

        <p class="text-4xl font-bold mt-3">
            {{ $totalSparepart }}
        </p>
    </div>

</div>

{{-- Informasi Sistem --}}
<div class="bg-white rounded-lg shadow p-6">

    <h2 class="text-xl font-semibold text-gray-800 mb-3">
        Informasi Sistem
    </h2>

    <div class="space-y-2 text-gray-600">

        <p>
            Sistem Informasi Manajemen Bengkel Motor
        </p>

        <p>
            Gunakan menu di sebelah kiri untuk mengelola pelanggan,
            kendaraan, stok suku cadang, transaksi servis, dan laporan.
        </p>

    </div>

</div>
</div>

@endsection
