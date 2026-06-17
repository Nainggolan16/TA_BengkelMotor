@extends('layouts.app-admin')

@section('content')

<div class="space-y-6">

{{-- Header --}}
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Data Transaksi Servis
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola transaksi servis dan riwayat pelayanan pelanggan.
            </p>

        </div>

        <div class="bg-blue-50 px-4 py-3 rounded-lg border border-blue-100">

            <p class="text-sm text-gray-500">
                Total Transaksi
            </p>

            <p class="text-2xl font-bold text-blue-600">
                {{ $transactions->count() }}
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
                placeholder="Cari nomor transaksi..."
                class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-blue-500">

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium">

                Cari

            </button>

        </form>

        <a
            href="/transaksi-servis/create"
            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium whitespace-nowrap">

            + Buat Transaksi

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
                    Kode Transaksi
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Tanggal Masuk
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Pelanggan
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Kendaraan
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Status
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Total Harga
                </th>

                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($transactions as $transaction)

            <tr class="border-t hover:bg-gray-50">

                <td class="px-4 py-4">
                    {{ $loop->iteration }}
                </td>

                <td class="px-4 py-4">

                    <span class="font-semibold text-gray-800">
                        {{ $transaction->kode_order }}
                    </span>

                </td>

                <td class="px-4 py-4 text-gray-600">

                    {{ \Carbon\Carbon::parse($transaction->tanggal_masuk)->format('d M Y') }}

                </td>

                <td class="px-4 py-4">

                    {{ optional(optional($transaction->kendaraan)->pelanggan)->nama ?? '-' }}

                </td>

                <td class="px-4 py-4">

                    <div class="font-medium">
                        {{ optional($transaction->kendaraan)->plat_nomor }}
                    </div>

                    <div class="text-sm text-gray-500">
                        {{ optional($transaction->kendaraan)->nama_kendaraan }}
                    </div>

                </td>

                <td class="px-4 py-4">

                    @php

                        $status = $transaction->status;

                        $badge = 'bg-gray-100 text-gray-800';

                        if($status == 'menunggu_pemeriksaan')
                            $badge = 'bg-yellow-100 text-yellow-800';

                        elseif($status == 'proses_pengerjaan')
                            $badge = 'bg-blue-100 text-blue-800';

                        elseif($status == 'selesai')
                            $badge = 'bg-green-100 text-green-800';

                        elseif($status == 'sudah_dibayar')
                            $badge = 'bg-purple-100 text-purple-800';

                    @endphp

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">

                        {{ ucwords(str_replace('_', ' ', $status)) }}

                    </span>

                </td>

                <td class="px-1 py-2 font-medium text-gray-800">

                    Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}

                </td>

                <td class="px-4 py-4">

                    <a
                        href="/transaksi-servis/{{ $transaction->id }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">

                        Detail

                    </a>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="8" class="text-center py-10 text-gray-500">

                    Data transaksi servis belum tersedia

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection
