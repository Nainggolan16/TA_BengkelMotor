@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Edit Jenis Servis
                </h1>

                <p class="text-gray-500 mt-2">
                    Perbarui data jenis servis bengkel.
                </p>
            </div>
            
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow p-6">

        @if ($errors->any())
            <div class="mb-4 rounded border border-red-200 bg-red-50 p-4 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/jenis-servis/{{ $jenisServis->id }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-medium text-gray-700">
                    Kategori Servis
                </label>

                <select name="id_kategori_servis"
                        class="w-full border rounded-lg p-2 @error('id_kategori_servis') border-red-500 @enderror"
                        required>

                    <option value="">-- Pilih Kategori --</option>

                    @foreach($kategoriServis as $kat)
                        <option value="{{ $kat->id }}"
                            {{ old('id_kategori_servis', $jenisServis->id_kategori_servis) == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach

                </select>

                @error('id_kategori_servis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">
                    Nama Servis
                </label>

                <input type="text"
                       name="nama_servis"
                       value="{{ old('nama_servis', $jenisServis->nama_servis) }}"
                       class="w-full border rounded-lg p-2 @error('nama_servis') border-red-500 @enderror"
                       required>

                @error('nama_servis')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">
                    Harga Jasa
                </label>

                <input type="number"
                       name="harga_jasa"
                       value="{{ old('harga_jasa', $jenisServis->harga_jasa) }}"
                       class="w-full border rounded-lg p-2 @error('harga_jasa') border-red-500 @enderror"
                       required>

                @error('harga_jasa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block mb-2 font-medium text-gray-700">
                    Keterangan
                </label>

                <textarea name="keterangan"
                          rows="4"
                          class="w-full border rounded-lg p-2 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $jenisServis->keterangan) }}</textarea>

                @error('keterangan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-2 pt-2">

                <button type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg">
                    Update
                </button>

                <a href="/jenis-servis"
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection