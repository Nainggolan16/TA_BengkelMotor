@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">


{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Kategori Servis
            </h1>

            <p class="text-gray-500 mt-2">
                Tambahkan kategori baru untuk mengelompokkan jenis servis.
            </p>

        </div>

    </div>

</div>

{{-- Error --}}
@if ($errors->any())

<div class="bg-red-100 text-red-700 p-4 rounded shadow">

    <strong>Error:</strong>

    <ul class="list-disc pl-5 mt-2">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>

</div>

@endif

{{-- Form --}}
<div class="bg-white p-6 rounded shadow">

    <form action="/kategori-servis"
          method="POST">

        @csrf

        <div class="mb-4">

            <label class="block mb-2 font-semibold">
                Nama Kategori
            </label>

            <input
                type="text"
                name="nama_kategori"
                value="{{ old('nama_kategori') }}"
                placeholder="Contoh: Servis Rutin"
                class="w-full border rounded p-2 @error('nama_kategori') border-red-500 @enderror"
                required
            >

            @error('nama_kategori')
            <p class="text-red-500 text-sm mt-1">
                {{ $message }}
            </p>
            @enderror

        </div>

        <div class="mb-6">

            <label class="block mb-2 font-semibold">
                Keterangan
            </label>

            <textarea
                name="keterangan"
                rows="4"
                placeholder="Masukkan keterangan kategori..."
                class="w-full border rounded p-2 @error('keterangan') border-red-500 @enderror"
            >{{ old('keterangan') }}</textarea>

            @error('keterangan')
            <p class="text-red-500 text-sm mt-1">
                {{ $message }}
            </p>
            @enderror

        </div>

        <div class="flex gap-2">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">

                Simpan

            </button>

            <a href="/kategori-servis"
               class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">

                Batal

            </a>

        </div>

    </form>

</div>

</div>

@endsection
