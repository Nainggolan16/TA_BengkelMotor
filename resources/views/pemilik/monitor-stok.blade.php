@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800">Monitor Stok Suku Cadang</h1>
        <p class="text-gray-600 mt-2">Pantau ketersediaan suku cadang dan riwayat stok masuk</p>
    </div>

    <!-- 4 Kartu Ringkasan -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Item -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-semibold">Total Item</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $ringkasan['total_item'] }}</p>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm font-semibold">Stok Kritis</p>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $ringkasan['stok_kritis'] }}</p>
        </div>

        <!-- Perlu Restock -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500">
            <p class="text-gray-600 text-sm font-semibold">Perlu Restock</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">{{ $ringkasan['perlu_restock'] }}</p>
        </div>

        <!-- Total Nilai Stok -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-semibold">Total Nilai Stok</p>
            <p class="text-2xl font-bold text-green-600 mt-2">
                Rp {{ number_format($ringkasan['total_nilai'], 0, ',', '.') }}
            </p>
        </div>

    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Stok</h2>
        <div class="flex gap-3 flex-wrap">
            <a href="?filter=semua" class="px-6 py-2 rounded-lg font-semibold transition {{ $filter === 'semua' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                📦 Semua
            </a>
            <a href="?filter=kritis" class="px-6 py-2 rounded-lg font-semibold transition {{ $filter === 'kritis' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                ⚠️ Stok Kritis
            </a>
            <a href="?filter=aman" class="px-6 py-2 rounded-lg font-semibold transition {{ $filter === 'aman' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                ✅ Stok Aman
            </a>
        </div>
    </div>

    <!-- Tabel Suku Cadang -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            @if($filter === 'kritis')
                Suku Cadang - Stok Kritis
            @elseif($filter === 'aman')
                Suku Cadang - Stok Aman
            @else
                Semua Suku Cadang
            @endif
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kode</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Suku Cadang</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Stok Minimum</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Harga Jual</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Progress Stok</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($sukuCadang as $stok)
                        @php
                            $persentase = $stok->stok_minimum > 0 ? min(($stok->stok / $stok->stok_minimum) * 100, 100) : 100;
                            $statusBadge = '';
                            if ($stok->stok == 0) {
                                $statusBadge = 'Habis';
                                $statusColor = 'bg-red-500';
                            } elseif ($stok->stok <= $stok->stok_minimum) {
                                $statusBadge = 'Kritis';
                                $statusColor = 'bg-orange-500';
                            } else {
                                $statusBadge = 'Aman';
                                $statusColor = 'bg-green-500';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-semibold text-gray-700">
                                {{ $stok->kode }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $stok->nama }}
                            </td>
                            <td class="px-4 py-3 text-center font-semibold">
                                <span class="{{ $stok->stok == 0 ? 'text-red-600' : ($stok->stok <= $stok->stok_minimum ? 'text-orange-600' : 'text-green-600') }}">
                                    {{ $stok->stok }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $stok->stok_minimum }} pcs
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($stok->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $persentase }}%"></div>
                                </div>
                                <span class="text-xs text-gray-600 mt-1 block">{{ number_format($persentase, 1, ',', '.') }}%</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white {{ $statusColor }}">
                                    {{ $statusBadge }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada suku cadang dalam kategori filter ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Stok Masuk -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Riwayat Stok Masuk (10 Terakhir)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Barang</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Jumlah</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Harga Beli</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Subtotal</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($riwayatStokMasuk as $riwayat)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold">
                                {{ $riwayat->sukuCadang->nama }}
                                <span class="text-xs text-gray-600 block">{{ $riwayat->sukuCadang->kode }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                    {{ $riwayat->jumlah }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                Rp {{ number_format($riwayat->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($riwayat->jumlah * $riwayat->harga_beli, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $riwayat->tanggal->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Belum ada riwayat stok masuk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
