@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow p-6">

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    Data Stok Masuk
                </h1>

                <p class="text-gray-500 mt-2">
                    Kelola riwayat stok suku cadang yang masuk ke gudang.
                </p>

            </div>

            <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">

                <p class="text-sm text-gray-500">
                    Total Transaksi
                </p>

                <p class="text-2xl font-bold text-blue-600">
                    {{ $stokMasuk->count() }}
                </p>

            </div>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))

    <div class="bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">

        {{ session('success') }}

    </div>

    @endif


    {{-- SEARCH --}}
    <div class="bg-white rounded-lg shadow p-4">

        <div class="flex items-center gap-4">

            <form method="GET" class="flex-1 flex gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari suku cadang..."
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500">

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">

                    Cari

                </button>

            </form>

            <a
                href="/stok-masuk/create"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium whitespace-nowrap">

                + Stok Masuk Baru

            </a>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="bg-white rounded-lg shadow overflow-hidden">

            <table class="w-full text-sm">  

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            No
                        </th>

                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Suku Cadang
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                            Jumlah Masuk
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                            Sisa Stok
                        </th>

                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Harga Beli
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                            Tanggal
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                            Pengguna
                        </th>

                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($stokMasuk as $item)

                    <tr class="border-t hover:bg-gray-50">

                        <td class="px-3 py-3">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-3 py-3">

                            <div class="font-semibold text-gray-800">

                                {{ $item->sukuCadang->nama }}

                            </div>

                            <div class="text-xs text-gray-500">

                                {{ $item->sukuCadang->kode }}

                            </div>

                        </td>

                        <td class="px-3 py-3 text-center">

                            <span class="font-semibold text-green-600">

                                +{{ $item->jumlah }}

                            </span>

                        </td>

                        <td class="px-3 py-3 text-center">

                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">

                                {{ $item->sisa_stok }}

                            </span>

                        </td>

                        <td class="px-3 py-3 whitespace-nowrap">

                            Rp {{ number_format($item->harga_beli, 0, ',', '.') }}

                        </td>

                        <td class="px-3 py-3 text-center whitespace-nowrap">

                            {{ date('d M Y', strtotime($item->tanggal)) }}

                        </td>

                        <td class="px-3 py-3 text-center whitespace-nowrap">

                            {{ $item->pengguna->name }}

                        </td>

                        <td class="px-3 py-3 text-center whitespace-nowrap">

                            <a
                                href="/stok-masuk/{{ $item->id }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm">

                                Lihat

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8" class="text-center py-8 text-gray-500">

                            Data stok masuk belum tersedia

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection