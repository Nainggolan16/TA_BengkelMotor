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
            <h1 class="text-3xl font-bold text-gray-800">Laporan Servis</h1>
            <p class="text-gray-600 mt-2">Analisa aktivitas servis bengkel</p>
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
                <select name="bulan" onchange="this.form.submit()" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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

        <!-- Total Order -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-semibold">Total Order</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $totalOrder }} Order</p>
        </div>

        <!-- Order Selesai -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-semibold">Selesai</p>
            <p class="text-2xl font-bold text-green-600 mt-2">
                {{ $selesai }} Order
                @if($totalOrder > 0)
                    <span class="text-sm text-gray-600">({{ number_format(($selesai / $totalOrder) * 100, 1, ',', '.') }}%)</span>
                @endif
            </p>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-600">
            <p class="text-gray-600 text-sm font-semibold">Total Pendapatan</p>
            <p class="text-2xl font-bold text-green-700 mt-2">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </p>
        </div>

    </div>

    <!-- Distribusi Status Order -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Distribusi Status Order</h2>
        <div class="flex flex-wrap gap-4">
            @forelse($statusDistribusi as $status)
                <div class="flex items-center gap-2">
                    @if($status->status == 'menunggu_pemeriksaan')
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-yellow-500">
                            Menunggu: {{ $status->jumlah }}
                        </span>
                    @elseif($status->status == 'proses_pengerjaan')
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-purple-500">
                            Proses: {{ $status->jumlah }}
                        </span>
                    @elseif($status->status == 'selesai')
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-green-500">
                            Selesai: {{ $status->jumlah }}
                        </span>
                    @elseif($status->status == 'sudah_dibayar')
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-blue-500">
                            Dibayar: {{ $status->jumlah }}
                        </span>
                    @else
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-semibold text-white bg-gray-500">
                            {{ ucfirst(str_replace('_', ' ', $status->status)) }}: {{ $status->jumlah }}
                        </span>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Belum ada data order</p>
            @endforelse
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
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Jumlah</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($servisTerlaris as $servis)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold">
                                {{ $servis->jenisServis->nama_servis }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-blue-500">
                                    {{ $servis->jumlah }}x
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                Rp {{ number_format($servis->pendapatan, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data servis
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Spare Part Terbanyak Dipakai -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Spare Part Terbanyak Dipakai</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Spare Part</th>
                        <th class="px-4 py-3 text-center text-gray-700 font-semibold">Qty Terpakai</th>
                        <th class="px-4 py-3 text-right text-gray-700 font-semibold">Total Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($sparePartTerbanyak as $part)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold">
                                {{ $part->sukuCadang->nama }}
                                <span class="text-xs text-gray-600 block">{{ $part->sukuCadang->kode }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold text-white bg-purple-500">
                                    {{ $part->total_qty }} pcs
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-green-600">
                                Rp {{ number_format($part->total_nilai, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                Belum ada data spare part
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
