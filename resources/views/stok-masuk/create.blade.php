@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- =========================
     HEADER
========================== --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Stok Masuk
            </h1>

            <p class="text-gray-500 mt-2">
                Tambahkan stok suku cadang yang baru masuk ke gudang.
            </p>

        </div>

    </div>

</div>


{{-- =========================
     VALIDATION ERROR
========================== --}}
@if ($errors->any())

<div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">

    <ul class="list-disc pl-5">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>

@endif


{{-- =========================
     FORM
========================== --}}
<div class="bg-white rounded-lg shadow p-6">

    <form action="/stok-masuk" method="POST">

        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- SUKU CADANG --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-medium text-gray-700">
                    Suku Cadang
                </label>

                <select
                    id="sukuCadangSelect"
                    name="id_suku_cadang">

                    <option value="">
                        Pilih suku cadang...
                    </option>

                    @foreach($sukuCadang as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('id_suku_cadang') == $item->id ? 'selected' : '' }}>

                        {{ $item->kode }} - {{ $item->nama }}

                    </option>

                    @endforeach

                </select>

                @error('id_suku_cadang')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            {{-- JUMLAH MASUK --}}
            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Jumlah Masuk
                </label>

                <input
                    type="number"
                    name="jumlah_masuk"
                    min="1"
                    value="{{ old('jumlah_masuk') }}"
                    class="w-full border rounded-lg p-2">

                @error('jumlah_masuk')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            {{-- TANGGAL MASUK --}}
            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Tanggal Masuk
                </label>

                <input
                    type="date"
                    name="tanggal_masuk"
                    value="{{ old('tanggal_masuk', date('Y-m-d')) }}"
                    class="w-full border rounded-lg p-2">

                @error('tanggal_masuk')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            {{-- HARGA BELI --}}
            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Harga Beli
                </label>

                <input
                    type="number"
                    name="harga_beli"
                    min="0"
                    step="0.01"
                    value="{{ old('harga_beli') }}"
                    class="w-full border rounded-lg p-2">

                @error('harga_beli')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            {{-- HARGA JUAL --}}
            <div>

                <label class="block mb-2 font-medium text-gray-700">
                    Harga Jual Baru
                </label>

                <input
                    type="number"
                    name="harga_jual"
                    min="0"
                    step="0.01"
                    value="{{ old('harga_jual') }}"
                    class="w-full border rounded-lg p-2">

                @error('harga_jual')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>


            {{-- KETERANGAN --}}
            <div class="md:col-span-2">

                <label class="block mb-2 font-medium text-gray-700">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="4"
                    class="w-full border rounded-lg p-2">{{ old('keterangan') }}</textarea>

                @error('keterangan')

                <p class="text-red-500 text-sm mt-1">
                    {{ $message }}
                </p>

                @enderror

            </div>

        </div>


        {{-- INFO --}}
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">

            <p class="text-sm text-yellow-800">

                Setelah stok masuk disimpan, stok suku cadang akan otomatis bertambah
                dan harga beli terakhir akan diperbarui.

            </p>

        </div>


        {{-- =========================
             BUTTON ACTION
        ========================== --}}
        <div class="mt-6 flex gap-2">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">

                Simpan

            </button>

            <a
                href="/stok-masuk"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                Batal

            </a>

        </div>

    </form>

</div>

</div>

<style>

.ts-control{
    border:1px solid #d1d5db !important;
    border-radius:0.5rem !important;
    min-height:42px;
}

.ts-wrapper.single .ts-control{
    padding:8px 12px !important;
}

</style>

<script>

document.addEventListener('DOMContentLoaded', function () {

    new TomSelect('#sukuCadangSelect', {

        create: false,
        placeholder: 'Cari suku cadang...',
        searchField: ['text']

    });

});

</script>

@endsection
