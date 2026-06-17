@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Edit Suku Cadang
            </h1>

            <p class="text-gray-500 mt-2">
                Perbarui informasi suku cadang bengkel.
            </p>

        </div>

    </div>

</div>

{{-- Error --}}
@if ($errors->any())

<div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">

    <ul class="list-disc list-inside">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif

{{-- Form --}}
<div class="bg-white p-6 rounded-lg shadow">

    <form action="/suku-cadang/{{ $sukuCadang->id }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Kode --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Kode Barang
                </label>

                <input
                    type="text"
                    name="kode"
                    value="{{ old('kode', $sukuCadang->kode) }}"
                    class="w-full border rounded-lg p-2">

            </div>

            {{-- Nama --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Suku Cadang
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $sukuCadang->nama) }}"
                    class="w-full border rounded-lg p-2">

            </div>

            {{-- Stok --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Stok Saat Ini
                </label>

                <input
                    type="text"
                    value="{{ $sukuCadang->stok }}"
                    readonly
                    class="w-full border rounded-lg p-2 bg-gray-100">

                <small class="text-gray-500">
                    Stok hanya dapat berubah melalui menu Stok Masuk dan Transaksi Servis.
                </small>

            </div>

            {{-- Stok Minimum --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Stok Minimum
                </label>

                <input
                    type="number"
                    name="stok_minimum"
                    value="{{ old('stok_minimum', $sukuCadang->stok_minimum) }}"
                    class="w-full border rounded-lg p-2">

            </div>

            {{-- Harga Beli --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Harga Beli Terakhir
                </label>

                <input
                    type="text"
                    readonly
                    value="Rp {{ number_format($sukuCadang->harga_beli,0,',','.') }}"
                    class="w-full border rounded-lg p-2 bg-gray-100">

                    <small class="text-gray-500">
                    Harga beli terakhir diperbarui saat stok masuk baru ditambahkan.
                    </small>
            </div>

            {{-- Harga Jual --}}
            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Harga Jual
                </label>

                <input
                    type="number"
                    name="harga_jual"
                    value="{{ old('harga_jual', $sukuCadang->harga_jual) }}"
                    class="w-full border rounded-lg p-2">

            </div>

        </div>

        {{-- Tombol --}}
        <div class="flex gap-2 mt-6">

            <button
                type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">

                Update

            </button>

            <a href="/suku-cadang"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">

                Batal

            </a>

        </div>

    </form>

</div>

</div>

@endsection
