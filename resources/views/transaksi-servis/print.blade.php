@extends('layouts.app-admin')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">Nota Transaksi Servis</h1>
            <p class="text-sm text-gray-600">Cetak bukti pembayaran untuk pelanggan.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded">Cetak</button>
    </div>

    <div class="bg-white rounded shadow p-6 space-y-6">
        <div class="border-b pb-4">
            <h2 class="text-xl font-semibold">Nama Bengkel</h2>
            <p>Bengkel App</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="font-semibold">Kode Transaksi</div>
                <div>{{ $transaction->kode_order }}</div>
            </div>
            <div>
                <div class="font-semibold">Tanggal</div>
                <div>{{ $transaction->tanggal_bayar ?? $transaction->tanggal_selesai ?? $transaction->tanggal_masuk }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div>
                <div class="font-semibold">Nama Pelanggan</div>
                <div>{{ optional(optional($transaction->kendaraan)->pelanggan)->nama }}</div>
            </div>
            <div>
                <div class="font-semibold">Plat Nomor</div>
                <div>{{ optional($transaction->kendaraan)->plat_nomor }}</div>
            </div>
            <div>
                <div class="font-semibold">Nama Kendaraan</div>
                <div>{{ optional($transaction->kendaraan)->nama_kendaraan }}</div>
            </div>
            <div>
                <div class="font-semibold">Metode Pembayaran</div>
                <div>{{ $transaction->metode_pembayaran ?? '-' }}</div>
            </div>
        </div>

        <div>
            <h3 class="font-semibold">Daftar Jasa</h3>
            <div class="mt-3 space-y-2 text-sm">
                @forelse($transaction->detailServis as $detail)
                    <div class="flex justify-between border-b pb-2">
                        <div>{{ optional($detail->jenisServis)->nama_servis }}</div>
                        <div>Rp {{ number_format($detail->harga_jasa, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-gray-500">Tidak ada jasa.</div>
                @endforelse
            </div>
        </div>

        <div>
            <h3 class="font-semibold">Daftar Jasa Tambahan</h3>
            <div class="mt-3 space-y-2 text-sm">
                @forelse($transaction->jasaTambahan as $detail)
                    <div class="flex justify-between border-b pb-2">
                        <div>{{ $detail->nama_jasa }}</div>
                        <div>Rp {{ number_format($detail->biaya, 0, ',', '.') }}</div>
                    </div>
                @empty
                    <div class="text-gray-500">Tidak ada jasa tambahan.</div>
                @endforelse
            </div>
        </div>

        <div>
            <h3 class="font-semibold">Daftar Sparepart</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3">Nama Sparepart</th>
                            <th class="p-3">Qty</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaction->detailSukuCadang as $detail)
                        <tr class="border-t">
                            <td class="p-3">{{ optional($detail->sukuCadang)->nama }}</td>
                            <td class="p-3">{{ $detail->jumlah }}</td>
                            <td class="p-3">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                            <td class="p-3">Rp {{ number_format($detail->harga_jual * $detail->jumlah, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-3 text-gray-500">Tidak ada sparepart.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-50 rounded p-4 text-sm">
            <div class="flex justify-between py-2"><span>Total Jasa (standar)</span><span>Rp {{ number_format($transaction->biaya_jasa, 0, ',', '.') }}</span></div>
            <div class="flex justify-between py-2"><span>Tambahan Biaya Jasa</span><span>Rp {{ number_format($transaction->biaya_jasa_tambahan ?? 0, 0, ',', '.') }}</span></div>
            <div class="flex justify-between py-2"><span>Total Sparepart</span><span>Rp {{ number_format($transaction->total_harga - ($transaction->biaya_jasa + ($transaction->biaya_jasa_tambahan ?? 0)), 0, ',', '.') }}</span></div>
            <div class="border-t mt-2 pt-2 flex justify-between font-semibold"><span>Grand Total</span><span>Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</span></div>
        </div>
    </div>
</div>

@endsection
