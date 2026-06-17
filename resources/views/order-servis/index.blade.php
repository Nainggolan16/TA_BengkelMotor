@extends('layouts.app-admin')

@section('content')

<div class="flex justify-between mb-6">

    <h1 class="text-3xl font-bold">
        Order Servis
    </h1>

    <a href="/order-servis/create"
       class="bg-blue-500 text-white px-4 py-2 rounded">

        + Order Baru

    </a>

</div>

<div class="bg-white rounded shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-3">Kode</th>
                <th class="p-3">Pelanggan</th>
                <th class="p-3">Kendaraan</th>
                <th class="p-3">Servis</th>
                <th class="p-3">Status</th>
                <th class="p-3">Biaya</th>
                <th class="p-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @foreach($order as $item)

            <tr class="border-t">

                <td class="p-3">
                    {{ $item->kode_order }}
                </td>

                <td class="p-3">
                    {{ optional(optional($item->kendaraan)->pelanggan)->nama }}
                    <a href="/detail-suku-cadang/create/{{ $item->id }}"
                    class="bg-blue-500 text-white px-3 py-1 rounded">

                        Sparepart

                    </a>
                </td>

                <td class="p-3">
                    {{ optional($item->kendaraan)->merk }}
                    {{ optional($item->kendaraan)->tipe }}
                </td>

                <td class="p-3">
                    {{ optional($item->jenisServis)->nama_servis }}
                </td>

                <td class="p-3">

                    @if($item->status == 'menunggu_pemeriksaan')

                    <span class="bg-blue-400 text-white px-3 py-1 rounded">
                        Menunggu Pemeriksaan
                    </span>

                    @elseif($item->status == 'proses_pengerjaan')

                    <span class="bg-yellow-400 text-white px-3 py-1 rounded">
                        Proses Pengerjaan
                    </span>

                    @elseif($item->status == 'selesai')

                    <span class="bg-green-500 text-white px-3 py-1 rounded">
                        Selesai
                    </span>

                    @elseif($item->status == 'lunas')

                    <span class="bg-indigo-500 text-white px-3 py-1 rounded">
                        Lunas
                    </span>

                    @else

                    <span class="bg-gray-500 text-white px-3 py-1 rounded">
                        Unknown
                    </span>

                    @endif

                </td>

                <td class="p-3">
                    Rp {{ number_format($item->total_harga) }}
                </td>

                <td class="p-3 flex gap-2">
                    <a href="/nota-pembayaran/create/{{ $item->id }}"
                    class="bg-green-500 text-white px-3 py-1 rounded">

                        Bayar

                    </a>

                    <a href="/order-servis/{{ $item->id }}/edit"
                       class="bg-yellow-500 text-white px-3 py-1 rounded">

                        Edit

                    </a>

                    <form action="/order-servis/{{ $item->id }}"
                          method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            class="bg-red-500 text-white px-3 py-1 rounded">

                            Hapus

                        </button>

                    </form>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection