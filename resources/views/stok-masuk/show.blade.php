@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">


{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Detail Stok Masuk
            </h1>

            <p class="text-gray-500 mt-2">
                Informasi lengkap transaksi stok masuk suku cadang.
            </p>

        </div>

        <a
            href="/stok-masuk"
            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-3 rounded-lg font-medium">

            Kembali

        </a>

    </div>

</div>


{{-- Informasi Utama --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="grid md:grid-cols-2 gap-8">

        <div>

            <p class="text-sm text-gray-500 mb-1">
                Suku Cadang
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                {{ $stokMasuk->sukuCadang->nama }}
            </h2>

            <p class="text-gray-500 mt-1">
                Kode: {{ $stokMasuk->sukuCadang->kode }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500 mb-1">
                Pengguna Input
            </p>

            <h2 class="text-2xl font-bold text-gray-800">
                {{ $stokMasuk->pengguna->name }}
            </h2>

        </div>

    </div>

</div>


{{-- Statistik --}}
<div class="grid md:grid-cols-3 gap-6">

    <div class="bg-blue-50 border border-blue-100 rounded-lg p-5">

        <p class="text-sm text-gray-500">
            Jumlah Masuk
        </p>

        <p class="text-3xl font-bold text-blue-600 mt-2">
            +{{ $stokMasuk->jumlah }}
        </p>

        <p class="text-sm text-gray-500 mt-1">
            Unit
        </p>

    </div>

    <div class="bg-green-50 border border-green-100 rounded-lg p-5">

        <p class="text-sm text-gray-500">
            Sisa Stok Batch
        </p>

        <p class="text-3xl font-bold text-green-600 mt-2">
            {{ $stokMasuk->sisa_stok }}
        </p>

        <p class="text-sm text-gray-500 mt-1">
            Unit
        </p>

    </div>

    <div class="bg-purple-50 border border-purple-100 rounded-lg p-5">

        <p class="text-sm text-gray-500">
            Sudah Terpakai
        </p>

        <p class="text-3xl font-bold text-purple-600 mt-2">
            {{ $stokMasuk->jumlah - $stokMasuk->sisa_stok }}
        </p>

        <p class="text-sm text-gray-500 mt-1">
            Unit
        </p>

    </div>

</div>


{{-- Detail Transaksi --}}
<div class="bg-white rounded-lg shadow p-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-5">
        Detail Transaksi
    </h3>

    <div class="grid md:grid-cols-2 gap-8">

        <div>

            <p class="text-sm text-gray-500">
                Harga Beli
            </p>

            <p class="text-xl font-bold text-gray-800 mt-1">
                Rp {{ number_format($stokMasuk->harga_beli,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Total Nilai Stok
            </p>

            <p class="text-xl font-bold text-gray-800 mt-1">
                Rp {{ number_format($stokMasuk->harga_beli * $stokMasuk->jumlah,0,',','.') }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Tanggal Masuk
            </p>

            <p class="text-lg font-semibold text-gray-800 mt-1">
                {{ date('d M Y', strtotime($stokMasuk->tanggal)) }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Stok Saat Ini
            </p>

            <p class="text-lg font-semibold text-gray-800 mt-1">
                {{ $stokMasuk->sukuCadang->stok }} Unit
            </p>

        </div>

    </div>

</div>


{{-- Keterangan --}}
@if($stokMasuk->catatan)

<div class="bg-white rounded-lg shadow p-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-4">
        Keterangan
    </h3>

    <div class="bg-gray-50 border rounded-lg p-4 text-gray-700">

        {{ $stokMasuk->catatan }}

    </div>

</div>

@endif


{{-- Informasi Sistem --}}
<div class="bg-white rounded-lg shadow p-6">

    <h3 class="text-lg font-semibold text-gray-800 mb-5">
        Informasi Sistem
    </h3>

    <div class="grid md:grid-cols-2 gap-6">

        <div>

            <p class="text-sm text-gray-500">
                ID Batch
            </p>

            <p class="font-semibold text-gray-800 mt-1">
                #{{ $stokMasuk->id }}
            </p>

        </div>

        <div>

            <p class="text-sm text-gray-500">
                Dicatat Pada
            </p>

            <p class="font-semibold text-gray-800 mt-1">
                {{ $stokMasuk->tanggal_masuk ? $stokMasuk->tanggal_masuk->format('d-m-Y') : '-' }}
            </p>

        </div>

    </div>

</div>

</div>

@endsection
