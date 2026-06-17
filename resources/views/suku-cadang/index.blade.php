@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow p-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Data Suku Cadang
                </h1>

                <p class="text-gray-500 mt-2">
                    Kelola data stok dan harga suku cadang bengkel.
                </p>
            </div>

            <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">

                <p class="text-sm text-gray-500">
                    Total Suku Cadang
                </p>

                <p class="text-2xl font-bold text-blue-600">
                    {{ $sukuCadang->count() }}
                </p>

            </div>

        </div>

    </div>

    {{-- Search + Tambah --}}
    <div class="bg-white rounded-lg shadow p-4">

       <div class="flex items-center gap-4">

            <form action="/suku-cadang" method="GET" class="flex-1 flex gap-3">

                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari kode atau nama suku cadang..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">

                    Cari

                </button>

            </form>

            <a
                href="/suku-cadang/create"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium whitespace-nowrap">

                + Suku Cadang Baru

            </a>

        </div>

    </div>

    {{-- Success Message --}}
    @if(session('success'))

    <div class="bg-green-100 text-green-800 border border-green-200 rounded-lg p-4">

        {{ session('success') }}

    </div>

    @endif

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            No
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Kode
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Nama
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Stok
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Stok Minimum
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Harga Beli
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                            Harga Jual
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sukuCadang as $item)

                    <tr class="border-t hover:bg-gray-50 transition">

                        <td class="px-4 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-4 py-3 font-medium whitespace-nowrap">
                            {{ $item->kode }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->nama }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->stok }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->stok_minimum }}
                        </td>

                        <td class="py-3">
                            Rp {{ number_format($item->harga_beli,0,',','.') }}
                        </td>

                        <td class="px-2 py-3">
                            Rp {{ number_format($item->harga_jual,0,',','.') }}
                        </td>

                        <td class="px-4 py-3">

                            <div class="flex justify-center gap-2">

                                <a href="/suku-cadang/{{ $item->id }}/edit"
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">

                                    Edit

                                </a>

                                <form action="/suku-cadang/{{ $item->id }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus suku cadang ini?')">

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

                        <td colspan="8"
                            class="text-center py-10 text-gray-500">

                            Data suku cadang belum tersedia.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection