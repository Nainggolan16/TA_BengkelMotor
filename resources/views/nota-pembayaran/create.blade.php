@extends('layouts.app-admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Tambah Pembayaran
</h1>

<div class="bg-white p-6 rounded shadow">

    <form action="/nota-pembayaran"
          method="POST">

        @csrf

        <div class="mb-4">

            <label class="block mb-2">
                Order Servis
            </label>

            <select
                name="id_order"
                class="w-full border rounded p-2">

                @foreach($orderServis as $item)

                <option value="{{ $item->id }}">

                    {{ $item->kode_order }}
                    -
                    {{ $item->kendaraan->pelanggan->nama }}
                    -
                    Rp {{ number_format($item->total_harga) }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-2">
                Metode Bayar
            </label>

            <select
                name="metode_bayar"
                class="w-full border rounded p-2">

                <option value="tunai">
                    Tunai
                </option>

                <option value="transfer">
                    Transfer
                </option>

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-2">
                Status Bayar
            </label>

            <select
                name="status_bayar"
                class="w-full border rounded p-2">

                <option value="lunas">
                    Lunas
                </option>

                <option value="belum_lunas">
                    Belum Lunas
                </option>

            </select>

        </div>

        <button
            class="bg-green-500 text-white px-5 py-2 rounded">

            Simpan Pembayaran

        </button>

    </form>

</div>

@endsection