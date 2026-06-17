@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Jenis Servis
            </h1>

            <p class="text-gray-500 mt-2">
                Tambahkan data jenis servis baru untuk bengkel.
            </p>

        </div>

    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow p-6">

        <form action="/jenis-servis" method="POST" class="space-y-6">

            @csrf

            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Kategori Servis *
                </label>

                <select
                    name="id_kategori_servis"
                    class="w-full border rounded p-2"
                    required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($kategoriServis as $kat)

                    <option
                        value="{{ $kat->id }}"
                        {{ old('id_kategori_servis') == $kat->id ? 'selected' : '' }}>

                        {{ $kat->nama_kategori }}

                    </option>

                    @endforeach

                </select>

                @error('id_kategori_servis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Nama Servis
                </label>

                <input
                    type="text"
                    name="nama_servis"
                    value="{{ old('nama_servis') }}"
                    class="w-full border rounded p-2">

                @error('nama_servis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Harga Jasa
                </label>

                <input
                    type="number"
                    name="harga_jasa"
                    value="{{ old('harga_jasa') }}"
                    class="w-full border rounded p-2">

                @error('harga_jasa')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div>

                <label class="block mb-2 text-sm font-medium text-gray-700">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    rows="4"
                    class="w-full border rounded p-2">{{ old('keterangan') }}</textarea>

                @error('keterangan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror

            </div>

            <div class="flex gap-2">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">

                    Simpan

                </button>

                <a
                    href="/jenis-servis"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection