@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800">Monitor Order Aktif</h1>
        <p class="text-gray-600 mt-2">Pantau status dan progress order servis</p>
    </div>

    <!-- 4 Kartu Ringkasan (Hari Ini) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Menunggu Pemeriksaan -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-semibold">Menunggu Pemeriksaan</p>
            <p class="text-2xl font-bold text-yellow-600 mt-2">{{ $ringkasan['menunggu'] }}</p>
        </div>

        <!-- Proses Pengerjaan -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-semibold">Proses Pengerjaan</p>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $ringkasan['proses'] }}</p>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-semibold">Selesai</p>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $ringkasan['selesai'] }}</p>
        </div>

        <!-- Total Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-semibold">Total Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $ringkasan['total'] }}</p>
        </div>

    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6 space-y-4">
        <h2 class="text-lg font-bold text-gray-800">Filter</h2>
        
        <!-- Filter Tanggal (Tabs) -->
        <div class="flex gap-3 flex-wrap">
            <a href="?tanggal=hari_ini&status={{ $status }}" class="px-6 py-2 rounded-lg font-semibold transition {{ $tanggal === 'hari_ini' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                📅 Hari Ini
            </a>
            <a href="?tanggal=minggu_ini&status={{ $status }}" class="px-6 py-2 rounded-lg font-semibold transition {{ $tanggal === 'minggu_ini' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                📆 Minggu Ini
            </a>
            <a href="?tanggal=bulan_ini&status={{ $status }}" class="px-6 py-2 rounded-lg font-semibold transition {{ $tanggal === 'bulan_ini' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                📊 Bulan Ini
            </a>
        </div>

        <!-- Filter Status (Dropdown) -->
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status Order</label>
            <form method="GET" class="flex gap-2 items-end">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <select name="status" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="menunggu_pemeriksaan" {{ $status === 'menunggu_pemeriksaan' ? 'selected' : '' }}>Menunggu Pemeriksaan</option>
                    <option value="proses_pengerjaan" {{ $status === 'proses_pengerjaan' ? 'selected' : '' }}>Proses Pengerjaan</option>
                    <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Tabel Order -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Order</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kode Order</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Plat Nomor</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Jenis Servis</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Status</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Total Harga</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Tanggal Masuk</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-semibold text-blue-600">
                                {{ $order->kode_order }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->kendaraan->pelanggan->nama }}
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold">
                                {{ $order->kendaraan->plat_nomor }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                @if($order->detailServis->count() > 0)
                                    <ul class="space-y-1">
                                        @foreach($order->detailServis->take(2) as $servis)
                                            <li class="text-gray-700">• {{ $servis->jenisServis->nama_servis }}</li>
                                        @endforeach
                                        @if($order->detailServis->count() > 2)
                                            <li class="text-gray-600 italic">+{{ $order->detailServis->count() - 2 }} lainnya</li>
                                        @endif
                                    </ul>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($order->status == 'menunggu_pemeriksaan')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-yellow-500">
                                        Menunggu
                                    </span>
                                @elseif($order->status == 'proses_pengerjaan')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-purple-500">
                                        Proses
                                    </span>
                                @elseif($order->status == 'selesai')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-green-500">
                                        Selesai
                                    </span>
                                @elseif($order->status == 'sudah_dibayar')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                        Dibayar
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->tanggal_masuk->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('pemilik.monitor-order.show', $order->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                    Lihat →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada order dengan filter ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>

@endsection
