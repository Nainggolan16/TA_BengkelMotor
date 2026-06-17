@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Pemilik</h1>
        <p class="text-gray-600 mt-2">{{ now()->format('l, d F Y') }}</p>
    </div>

    <!-- 6 Kartu Metrik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Pendapatan Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">
                        Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-blue-500 text-4xl opacity-20">
                    💰
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Pendapatan Bulan Ini</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">
                        Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <div class="text-green-500 text-4xl opacity-20">
                    📊
                </div>
            </div>
        </div>

        <!-- Order Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Order Hari Ini</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-2">
                        {{ $orderHariIni }} Order
                    </p>
                </div>
                <div class="text-yellow-500 text-4xl opacity-20">
                    📋
                </div>
            </div>
        </div>

        <!-- Order Sedang Dikerjakan -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Sedang Dikerjakan</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">
                        {{ $orderDikerjakan }} Order
                    </p>
                </div>
                <div class="text-purple-500 text-4xl opacity-20">
                    🔧
                </div>
            </div>
        </div>

        <!-- Order Selesai Hari Ini -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Selesai Hari Ini</p>
                    <p class="text-2xl font-bold text-teal-600 mt-2">
                        {{ $orderSelesaiHariIni }} Order
                    </p>
                </div>
                <div class="text-teal-500 text-4xl opacity-20">
                    ✅
                </div>
            </div>
        </div>

        <!-- Stok Kritis -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 {{ $stokKritis > 0 ? 'border-red-500' : 'border-gray-300' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Stok Kritis</p>
                    <p class="text-2xl font-bold {{ $stokKritis > 0 ? 'text-red-600' : 'text-gray-600' }} mt-2">
                        {{ $stokKritis }} Item
                    </p>
                </div>
                <div class="text-red-500 text-4xl opacity-20">
                    ⚠️ 
                </div>
            </div>
        </div>

    </div>

    <!-- Grafik Pendapatan 7 Hari -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Grafik Pendapatan 7 Hari Terakhir</h2>
        <div class="relative h-80">
            <canvas id="grafikPendapatan"></canvas>
        </div>
    </div>

    <!-- Order Aktif -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Order Aktif (Terbaru)</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Plat Nomor</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Jenis Kendaraan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Status</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Tanggal Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($orderAktif as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-semibold text-blue-600">
                                {{ $order->kendaraan->plat_nomor }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->kendaraan->pelanggan->nama }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->kendaraan->nama_kendaraan }}
                            </td>
                            <td class="px-4 py-3">
                                @if($order->status == 'menunggu_pemeriksaan')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-yellow-500">
                                        Menunggu Pemeriksaan
                                    </span>
                                @elseif($order->status == 'proses_pengerjaan')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-purple-500">
                                        Proses Pengerjaan
                                    </span>
                                @elseif($order->status == 'selesai')
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-green-500">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $order->tanggal_masuk->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada order aktif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Servis Terlaris -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Servis Terlaris Bulan Ini</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Servis</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($servisTerlaris as $servis)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $servis->jenisServis->nama_servis }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                    {{ $servis->total }}x
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data servis
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stok Hampir Habis -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Stok Hampir Habis</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kode</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Sparepart</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Stok Saat Ini</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Stok Minimum</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($stokHampirHabis as $stok)
                        <tr class="hover:bg-gray-50 {{ $stok->stok < $stok->stok_minimum ? 'bg-red-50' : '' }}">
                            <td class="px-4 py-3 font-mono font-semibold text-gray-700">
                                {{ $stok->kode }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $stok->nama }}
                            </td>
                            <td class="px-4 py-3 {{ $stok->stok < $stok->stok_minimum ? 'text-red-600 font-bold' : '' }}">
                                {{ $stok->stok }} pcs
                            </td>
                            <td class="px-4 py-3">
                                {{ $stok->stok_minimum }} pcs
                            </td>
                            <td class="px-4 py-3">
                                @if($stok->stok < $stok->stok_minimum)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-red-500">
                                        Kritis
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-yellow-500">
                                        Waspada
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Stok terjaga dengan baik
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pelanggan Teraktif -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Pelanggan Teraktif Bulan Ini</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kendaraan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Total Order</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($pelangganTeraktif as $pelanggan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold">
                                {{ $pelanggan->kendaraan->pelanggan->nama }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $pelanggan->kendaraan->plat_nomor }} ({{ $pelanggan->kendaraan->nama_kendaraan }})
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-green-500">
                                    {{ $pelanggan->total_order }}x
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data pelanggan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data from controller
    const grafikData = @json($grafikPendapatan);
    
    // Extract labels (dates) and values (totals)
    const labels = grafikData.map(item => {
        const date = new Date(item.tanggal);
        return date.toLocaleDateString('id-ID', { weekday: 'short', month: 'short', day: 'numeric' });
    });
    
    const data = grafikData.map(item => parseFloat(item.total) || 0);
    
    // Create chart
    const ctx = document.getElementById('grafikPendapatan').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        font: { size: 12, weight: 'bold' }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>

@endsection

