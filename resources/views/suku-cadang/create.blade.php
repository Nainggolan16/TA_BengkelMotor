@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">


{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Suku Cadang
            </h1>

            <p class="text-gray-500 mt-2">
                Tambahkan data suku cadang baru ke dalam sistem.
            </p>

        </div>

    </div>

</div>

{{-- Form --}}
<div class="bg-white p-6 rounded shadow">

    <form action="/suku-cadang" method="POST">

        @csrf

        @if ($errors->any())

            <div class="mb-4 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">

                <ul class="list-disc list-inside">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <div class="grid md:grid-cols-2 gap-6">

            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Kode Barang
                </label>

                <input
                    type="text"
                    name="kode"
                    value="{{ old('kode') }}"
                    class="w-full border rounded p-2">

            </div>

            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Nama Suku Cadang
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama') }}"
                    class="w-full border rounded p-2">

            </div>

            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Stok Minimum
                </label>

                <input
                    type="number"
                    name="stok_minimum"
                    value="{{ old('stok_minimum', 5) }}"
                    class="w-full border rounded p-2">

            </div>

            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Harga Jual
                </label>

                <input
                    type="number"
                    name="harga_jual"
                    value="{{ old('harga_jual') }}"
                    class="w-full border rounded p-2">

            </div>

        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mt-6 mb-6">

            <p class="text-sm text-yellow-800">

                Setelah suku cadang dibuat, stok awal dimasukkan melalui menu
                <strong>Stok Masuk</strong>.

            </p>

        </div>

        <div class="flex gap-2">

            <button
                type="submit"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">

                Simpan

            </button>

            <a href="/suku-cadang"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                Batal

            </a>

        </div>

    </form>

</div>


</div>

@endsection
