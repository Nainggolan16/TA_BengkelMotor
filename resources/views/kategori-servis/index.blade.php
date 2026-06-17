@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Data Kategori Servis
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola kategori servis untuk mengelompokkan jenis servis.
            </p>

        </div>

        <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">

            <p class="text-sm text-gray-500">
                Total Kategori
            </p>

            <p class="text-2xl font-bold text-blue-600">
                {{ $kategoriServis->count() }}
            </p>

        </div>

    </div>

</div>

{{-- Alert --}}
@if(session('success'))
<div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg shadow">
    {{ session('success') }}
</div>
@endif

{{-- Search & Button --}}
<div class="bg-white rounded-lg shadow p-4">

    <div class="flex flex-col md:flex-row gap-3 justify-between">

        <form method="GET" class="flex gap-2 flex-1">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kategori servis..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium">

                Cari

            </button>

        </form>

        <a
            href="/kategori-servis/create"
            class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium text-center">

            + Tambah Kategori

        </a>

    </div>

</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                        Nama Kategori
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                        Keterangan
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                        Jenis Servis
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($kategoriServis as $item)

                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $item->nama_kategori }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $item->keterangan ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-center">

                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-800">

                            {{ $item->jenis_servis_count }}

                        </span>

                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center gap-2">

                            <a
                                href="/kategori-servis/{{ $item->id }}/edit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">

                                Edit

                            </a>

                            <form
                                method="POST"
                                action="/kategori-servis/{{ $item->id }}"
                                onsubmit="return confirm('Hapus kategori ini?')">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm">

                                    Hapus

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" class="text-center py-10 text-gray-500">

                        Tidak ada data kategori servis.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

@endsection
