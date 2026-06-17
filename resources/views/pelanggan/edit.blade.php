@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Edit Data Pelanggan
            </h1>
            <p class="text-gray-500 mt-1">
                Perbarui informasi pelanggan bengkel.
            </p>
        </div>

    </div>

    {{-- Error Validation --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('pelanggan.update', $pelanggan->id) }}"
              method="POST"
              class="space-y-5">

            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Pelanggan
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $pelanggan->nama) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    name="no_telepon"
                    value="{{ old('no_telepon', $pelanggan->no_telepon) }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring focus:ring-blue-200"
                    required
                >
            </div>

            <div class="pt-4 flex gap-3">
                <button
                    type="submit"
                    class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition">
                    Update Data
                </button>

                <a href="{{ route('pelanggan.index') }}"
                   class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium transition">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

@endsection