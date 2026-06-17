@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div>

        <h1 class="text-3xl font-bold text-gray-800">
            Edit Kendaraan
        </h1>

        <p class="text-gray-500 mt-2">
            Perbarui data kendaraan pelanggan bengkel.
        </p>

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
<div class="bg-white rounded-lg shadow p-6">

    <form
        action="{{ route('kendaraan.update', $kendaraan->id) }}"
        method="POST"
        class="space-y-6">

        @csrf
        @method('PUT')

        <div class="grid gap-6 md:grid-cols-2">

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Pelanggan
                </label>

                <select
                    name="id_pelanggan"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none">

                    <option value="">
                        Pilih Pelanggan
                    </option>

                    @foreach($pelanggan as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ old('id_pelanggan', $kendaraan->id_pelanggan) == $item->id ? 'selected' : '' }}>

                        {{ $item->nama }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Plat Nomor
                </label>

                <input
                    type="text"
                    name="plat_nomor"
                    value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
                    placeholder="B 1234 ABC">

            </div>

            <div class="md:col-span-2">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kendaraan
                </label>

                <input
                    type="text"
                    name="nama_kendaraan"
                    value="{{ old('nama_kendaraan', $kendaraan->nama_kendaraan) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none"
                    placeholder="Honda Beat / Yamaha NMAX">

            </div>

        </div>

        <div class="flex gap-2 pt-2">

            <button
                type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-medium">

                Update

            </button>

            <a
                href="{{ route('kendaraan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg font-medium">

                Batal

            </a>

        </div>

    </form>

</div>

</div>

@endsection
