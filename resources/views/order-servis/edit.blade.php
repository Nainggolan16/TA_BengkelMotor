@extends('layouts.app-admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Edit Order Servis
</h1>

<div class="bg-white p-6 rounded shadow">

    <form action="/order-servis/{{ $order->id }}"
          method="POST">

        @csrf
        @method('PUT')

        <!-- Kendaraan -->
        <div class="mb-4">

            <label class="block mb-2">
                Kendaraan
            </label>

            <select
                name="id_kendaraan"
                class="w-full border rounded p-2">

                @foreach($kendaraan as $item)

                <option
                    value="{{ $item->id }}"
                    {{ $order->id_kendaraan == $item->id ? 'selected' : '' }}>

                    {{ optional($item->pelanggan)->nama }}
                    -
                    {{ optional($item)->merk }}
                    {{ optional($item)->tipe }}

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

                <option
                    value="{{ $item->id }}"
                    {{ $order->id_jenis_servis == $item->id ? 'selected' : '' }}>

                    {{ $item->nama_servis }}

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
                class="w-full border rounded p-2">{{ $order->keluhan }}</textarea>

        </div>

        <!-- Catatan Servis -->
        <div class="mb-4">

            <label class="block mb-2">
                Catatan Servis
            </label>

            <textarea
                name="catatan_servis"
                rows="4"
                class="w-full border rounded p-2">{{ $order->catatan_servis }}</textarea>

        </div>

        <!-- Status -->
        <div class="mb-4">

            <label class="block mb-2">
                Status
            </label>

            <select
                name="status"
                class="w-full border rounded p-2">

                <option
                    value="proses"
                    {{ $order->status == 'proses' ? 'selected' : '' }}>

                    Proses

                </option>

                <option
                    value="selesai"
                    {{ $order->status == 'selesai' ? 'selected' : '' }}>

                    Selesai

                </option>

            </select>

        </div>

        <button
            class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded">

            Update Order

        </button>

    </form>

</div>

@endsection