@extends('layouts.app-admin')

@section('content')

<div class="flex justify-between mb-6">

    <h1 class="text-3xl font-bold">
        Nota Pembayaran
    </h1>

    <a href="/nota-pembayaran/create"
       class="bg-blue-500 text-white px-4 py-2 rounded">

        + Pembayaran Baru

    </a>

</div>

<div class="bg-white rounded shadow overflow-hidden">

    <table class="w-full">

        <thead class="bg-gray-100">

            <tr>

                <th class="p-3">No</th>
                <th class="p-3">Pelanggan</th>
                <th class="p-3">Total</th>
                <th class="p-3">Metode</th>
                <th class="p-3">Status</th>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @foreach($nota as $item)

            <tr class="border-t">

                <td class="p-3">
                    {{ $loop->iteration }}
                </td>

                <td class="p-3">
                    {{ $item->orderServis->kendaraan->pelanggan->nama }}
                </td>

                <td class="p-3">
                    Rp {{ number_format($item->total) }}
                </td>

                <td class="p-3">
                    {{ $item->metode_bayar }}
                </td>

                <td class="p-3">

                    @if($item->status_bayar == 'lunas')

                        <span class="bg-green-500 text-white px-3 py-1 rounded">

                            Lunas

                        </span>

                    @else

                        <span class="bg-red-500 text-white px-3 py-1 rounded">

                            Belum Lunas

                        </span>

                    @endif

                </td>

                <td class="p-3">
                    {{ $item->dibayar_pada }}
                </td>

                <td class="p-3 flex gap-2">

                    <a href="/nota-pembayaran/{{ $item->id }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded">

                        Detail

                    </a>

                    <a href="/nota-pembayaran/print/{{ $item->id }}"
                        class="bg-green-500 text-white px-3 py-1 rounded">

                            Print

                    </a>

                    <form
                        action="/nota-pembayaran/{{ $item->id }}"
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