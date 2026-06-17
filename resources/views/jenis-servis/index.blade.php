@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">


{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Data Jenis Servis
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola daftar jenis servis yang tersedia di bengkel.
            </p>

        </div>

        <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">

            <p class="text-sm text-gray-500">
                Total Jenis Servis
            </p>

            <p class="text-2xl font-bold text-blue-600">
                {{ $jenisServis->count() }}
            </p>

        </div>

    </div>

</div>

{{-- Search & Button --}}
<div class="bg-white rounded-lg shadow p-4">

    <div class="flex items-center gap-4">

        <form method="GET" class="flex-1 flex gap-3">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau kategori..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">

                Cari

            </button>

        </form>

        <a
            href="/jenis-servis/create"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium whitespace-nowrap">

            + Tambah Jenis Servis

        </a>

    </div>

</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    No
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Kategori
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Nama Servis
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Harga Jasa
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Keterangan
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($jenisServis as $item)

            <tr class="border-t">

                <td class="p-3">
                    {{ $loop->iteration }}
                </td>

                <td class="p-3">

                    @if ($item->kategoriServis)

                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">

                            {{ $item->kategoriServis->nama_kategori }}

                        </span>

                    @else

                        -

                    @endif

                </td>

                <td class="p-3">
                    {{ $item->nama_servis }}
                </td>

                <td class="p-3">
                    Rp {{ number_format($item->harga_jasa, 0, ',', '.') }}
                </td>

                <td class="p-3">
                    {{ $item->keterangan ?? '-' }}
                </td>

                <td class="p-3 flex gap-2">

                    <a
                        href="/jenis-servis/{{ $item->id }}/edit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">

                        Edit

                    </a>

                    <form
                        action="/jenis-servis/{{ $item->id }}"
                        method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="6" class="text-center py-6 text-gray-500">

                    Data jenis servis belum tersedia

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>


</div>

@endsection
