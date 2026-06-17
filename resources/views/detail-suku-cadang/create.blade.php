@extends('layouts.app-admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Tambah Sparepart
</h1>

<div class="bg-white p-6 rounded shadow">

    <form action="/detail-suku-cadang/store"
          method="POST">

        @csrf

        <input
            type="hidden"
            name="id_order"
            value="{{ $order->id }}">

        <div class="mb-4">

            <label class="block mb-2">
                Sparepart
            </label>

            <select
                name="id_suku_cadang"
                class="w-full border rounded p-2">

                @foreach($sukuCadang as $item)

                <option value="{{ $item->id }}">

                    {{ $item->nama }}
                    -
                    Stok: {{ $item->stok }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="mb-4">

            <label class="block mb-2">
                Jumlah
            </label>

            <input
                type="number"
                name="jumlah"
                class="w-full border rounded p-2">

        </div>

        <button
            class="bg-blue-500 text-white px-5 py-2 rounded">

            Tambah Sparepart

        </button>

    </form>

</div>

@endsection