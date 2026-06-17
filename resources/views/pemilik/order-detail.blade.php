@extends('layouts.app-pemilik')

@section('content')

<div class="space-y-6">

    <!-- Header dengan Tombol Kembali -->
    <div class="bg-white rounded-lg shadow p-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Order Servis</h1>
            <p class="text-gray-600 mt-2">{{ $order->kode_order }}</p>
        </div>
        <a href="{{ route('pemilik.monitor-order') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg">
            ← Kembali
        </a>
    </div>

    <!-- Info Pelanggan & Kendaraan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Pelanggan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Pelanggan</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Nama Pelanggan</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->kendaraan->pelanggan->nama }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No. Telepon</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->kendaraan->pelanggan->no_telepon }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->kendaraan->pelanggan->alamat }}</p>
                </div>
            </div>
        </div>

        <!-- Kendaraan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Kendaraan</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Plat Nomor</p>
                    <p class="text-lg font-mono font-bold text-gray-800">{{ $order->kendaraan->plat_nomor }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Kendaraan</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->kendaraan->nama_kendaraan }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tahun</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $order->kendaraan->tahun }}</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Info Order -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Status Order</h2>
            @if($order->status == 'menunggu_pemeriksaan')
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold text-white bg-yellow-500">
                    Menunggu Pemeriksaan
                </span>
            @elseif($order->status == 'proses_pengerjaan')
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold text-white bg-purple-500">
                    Proses Pengerjaan
                </span>
            @elseif($order->status == 'selesai')
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold text-white bg-green-500">
                    Selesai
                </span>
            @elseif($order->status == 'sudah_dibayar')
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold text-white bg-blue-500">
                    Sudah Dibayar
                </span>
            @else
                <span class="inline-block px-4 py-2 rounded-full text-lg font-semibold text-white bg-gray-500">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            @endif
            <p class="text-sm text-gray-600 mt-4">Tanggal Masuk: <span class="font-semibold">{{ $order->tanggal_masuk->format('d M Y H:i') }}</span></p>
            @if($order->tanggal_selesai)
                <p class="text-sm text-gray-600">Tanggal Selesai: <span class="font-semibold">{{ $order->tanggal_selesai->format('d M Y H:i') }}</span></p>
            @endif
        </div>

        <!-- Metode & Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Metode Pembayaran</h2>
            <p class="text-lg font-semibold text-gray-800">{{ ucfirst($order->metode_pembayaran ?? '-') }}</p>
            @if($order->tanggal_bayar)
                <p class="text-sm text-gray-600 mt-4">Tanggal Bayar: <span class="font-semibold">{{ $order->tanggal_bayar->format('d M Y') }}</span></p>
            @endif
        </div>

        <!-- Keluhan -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Keluhan Pelanggan</h2>
            <p class="text-gray-700">{{ $order->keluhan ?? 'Tidak ada keluhan' }}</p>
        </div>

    </div>

    <!-- Catatan Servis -->
    @if($order->catatan_servis)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Catatan Servis</h2>
            <p class="text-gray-700">{{ $order->catatan_servis }}</p>
        </div>
    @endif

    <!-- Daftar Servis -->
    @if($order->detailServis->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Servis</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Servis</th>
                            <th class="px-4 py-3 text-right text-gray-700 font-semibold">Harga Jasa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->detailServis as $servis)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $servis->jenisServis->nama_servis }}</td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    Rp {{ number_format($servis->harga_jasa, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Daftar Suku Cadang -->
    @if($order->detailSukuCadang->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Suku Cadang yang Digunakan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Suku Cadang</th>
                            <th class="px-4 py-3 text-center text-gray-700 font-semibold">Qty</th>
                            <th class="px-4 py-3 text-right text-gray-700 font-semibold">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-gray-700 font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->detailSukuCadang as $suku)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    {{ $suku->sukuCadang->nama }}
                                    <span class="text-xs text-gray-600 block">{{ $suku->sukuCadang->kode }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">{{ $suku->qty }} pcs</td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($suku->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    Rp {{ number_format($suku->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Jasa Tambahan -->
    @if($order->detailJasaTambahan->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Jasa Tambahan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold">Nama Jasa</th>
                            <th class="px-4 py-3 text-right text-gray-700 font-semibold">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($order->detailJasaTambahan as $jasa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $jasa->nama_jasa ?? 'Jasa Tambahan' }}</td>
                                <td class="px-4 py-3 text-right font-semibold">
                                    Rp {{ number_format($jasa->harga_jasa, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Summary Biaya -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Biaya</h2>
        <div class="space-y-3 max-w-md">
            <div class="flex justify-between pb-2 border-b">
                <span class="text-gray-700">Biaya Jasa Servis:</span>
                <span class="font-semibold">Rp {{ number_format($order->biaya_jasa, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between pb-2 border-b">
                <span class="text-gray-700">Biaya Suku Cadang:</span>
                <span class="font-semibold">
                    Rp {{ number_format($order->detailSukuCadang->sum('subtotal'), 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between pb-2 border-b">
                <span class="text-gray-700">Biaya Jasa Tambahan:</span>
                <span class="font-semibold">
                    Rp {{ number_format($order->biaya_jasa_tambahan, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between pt-2 border-t-2 border-t-gray-800 text-lg">
                <span class="font-bold">Total Harga:</span>
                <span class="font-bold text-green-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

</div>

@endsection
