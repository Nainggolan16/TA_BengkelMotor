@extends('layouts.app-pemilik')

@section('content')

<style>
    @media print {
        .ml-64 {
            margin-left: 0 !important;
            width: 100% !important;
        }
        .w-64 {
            display: none !important;
        }
        .print-hide {
            display: none !important;
        }
        body {
            background: white !important;
        }
        .bg-gray-100 {
            background: white !important;
        }
    }
</style>

<div class="space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 flex justify-between items-center print-hide">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-600 mt-2">Filter dan analisa pendapatan bengkel</p>
        </div>
        <button onclick="window.print()" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
            🖨️ Cetak / Export PDF
        </button>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-6 print-hide">
        <form method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Bulan</label>
                <select name="bulan" onchange="this.form.submit()" class="py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                            {{ Carbon\Carbon::createFromDate(2026, $m, 1)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun</label>
                <select name="tahun" onchange="this.form.submit()" class="py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for ($y = 2024; $y <= 2030; $y++)
                        <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <!-- 3 Kartu Metrik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-semibold">Total Pendapatan</p>
            <p class="text-2xl text-black-600 mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </p>
        </div>

        <!-- Total Order -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-semibold">Total Order</p>
            <p class="text-2xl text-black-600 mt-2">{{ $totalOrder }} Order</p>
        </div>

        <!-- Rata-rata Harian -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-semibold">Rata-rata Harian</p>
            <p class="text-2xl text-black-600 mt-2">
                Rp {{ number_format($rataHarian, 0, ',', '.') }}
            </p>
        </div>

    </div>

    <!-- Grafik Pendapatan Harian -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Grafik Pendapatan Harian</h2>
        <div class="relative h-80">
            <canvas id="grafikHarian"></canvas>
        </div>
    </div>

    <!-- Breakdown Metode Pembayaran -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Metode Pembayaran</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Metode Pembayaran</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Jumlah Transaksi</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Total Nilai</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Persentase</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($metodePembayaran as $metode)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-gray-700">
                                {{ ucfirst($metode->metode_pembayaran ?? 'Tidak Ditentukan') }}
                            </td>
                            <td class="px-4 py-3">{{ $metode->jumlah }} transaksi</td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($metode->total, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                {{ $totalPendapatan > 0 ? number_format(($metode->total / $totalPendapatan) * 100, 1, ',', '.') : 0 }}%
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data transaksi pembayaran
                            </td>
                        </tr>
                    @endforelse
                    <tr class="bg-blue-50 font-bold">
                        <td colspan="2" class="px-4 py-3">TOTAL</td>
                        <td class="px-4 py-3 text-right text-black-700">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-black-700">100%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail Transaksi -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Transaksi</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Kode Order</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Plat Nomor</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Pelanggan</th>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Tanggal Bayar</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Total Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($detailTransaksi as $transaksi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono font-semibold">
                                {{ $transaksi->kode_order }}
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold">
                                {{ $transaksi->kendaraan->plat_nomor }}
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold">
                                {{ $transaksi->kendaraan->pelanggan->nama }}
                            </td>
                            <td class="px-4 py-3 font-mono font-semibold">
                                {{ $transaksi->tanggal_bayar->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-right font-semibold">
                                Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Belum ada transaksi pembayaran pada periode ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($detailTransaksi->hasPages())
            <div class="mt-6">
                {{ $detailTransaksi->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const grafikData = @json($grafikHarian);
    
    const labels = grafikData.map(item => 'Hari ' + item.hari);
    const data = grafikData.map(item => parseFloat(item.total) || 0);
    
    const ctx = document.getElementById('grafikHarian').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                backgroundColor: '#10B981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: { font: { size: 12, weight: 'bold' } }
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
