@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">


{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Data Pelanggan
            </h1>

            <p class="text-gray-500 mt-2">
                Daftar pelanggan yang telah melakukan transaksi servis.
            </p>
        </div>

        <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">
            <p class="text-sm text-gray-500">
                Total Pelanggan
            </p>

            <p class="text-2xl font-bold text-blue-600">
                {{ $pelanggan->count() }}
            </p>
        </div>

    </div>
</div>

{{-- Search --}}
<div class="bg-white rounded-lg shadow p-4">

    <form action="{{ route('pelanggan.index') }}" method="GET">

        <div class="flex gap-2">

            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau nomor telepon pelanggan..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-blue-500 focus:outline-none"
            >

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium">

                Cari

            </button>

        </div>

    </form>

</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">

    <div class="px-6 py-4 border-b">
        <h2 class="font-semibold text-gray-700">
            Daftar Pelanggan
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                        No
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                        Nama
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                        Nomor Telepon
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-500">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($pelanggan as $item)

                <tr class="border-t hover:bg-gray-50 transition">

                    <td class="px-4 py-3">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $item->nama }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $item->no_telepon }}
                    </td>

                    <td class="px-4 py-3">

                        <div class="flex justify-center gap-2">

                            <a
                                href="{{ route('pelanggan.edit', $item->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm">

                                Edit

                            </a>

                            <form
                                action="{{ route('pelanggan.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">

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

                        Belum ada data pelanggan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
</div>

@endsection
