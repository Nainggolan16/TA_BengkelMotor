@extends('layouts.app-admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Tambah Order Servis
</h1>

<div class="bg-white p-6 rounded shadow">

    <form action="/order-servis"
          method="POST">

        @csrf

        <!-- Kendaraan -->
        <div class="mb-4">

            <label class="block mb-2">
                Kendaraan
            </label>

            <select
                name="id_kendaraan"
                class="w-full border rounded p-2">

                @foreach($kendaraan as $item)

                <option value="{{ $item->id }}">

                    {{ optional($item->pelanggan)->nama }}
                    -
                    {{ optional($item)->merk }}
                    {{ optional($item)->tipe }}
                    ({{ optional($item)->plat_nomor }})

                </option>

                @endforeach

            </select>

        </div>

        <!-- Jenis Servis -->
        <div class="mb-4">

            <label class="block mb-2">
                Jenis Servis
            </label>

            <select
                name="id_jenis_servis"
                class="w-full border rounded p-2">

                @foreach($jenisServis as $item)

                <option value="{{ $item->id }}">

                    {{ $item->nama_servis }}
                    -
                    Rp {{ number_format($item->harga_jasa) }}

                </option>

                @endforeach

            </select>

        </div>

        <!-- Keluhan -->
        <div class="mb-4">

            <label class="block mb-2">
                Keluhan
            </label>

            <textarea
                name="keluhan"
                rows="4"
                class="w-full border rounded p-2"></textarea>

        </div>

        <button
            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded">

            Simpan Order

        </button>

    </form>

</div>

@endsection