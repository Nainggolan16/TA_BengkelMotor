@extends('layouts.app-admin')

@section('content')

<h1 class="text-3xl font-bold mb-6">
    Detail Nota
</h1>

<div class="bg-white p-6 rounded shadow">

    <div class="mb-3">
        <strong>Pelanggan:</strong>
        {{ $nota->orderServis->kendaraan->pelanggan->nama }}
    </div>

    <div class="mb-3">
        <strong>Kendaraan:</strong>
        {{ $nota->orderServis->kendaraan->merk }}
        {{ $nota->orderServis->kendaraan->tipe }}
    </div>

    <div class="mb-3">
        <strong>Jenis Servis:</strong>
        {{ $nota->orderServis->jenisServis->nama }}
    </div>

    <div class="mb-3">
        <strong>Total:</strong>
        Rp {{ number_format($nota->total) }}
    </div>

    <div class="mb-3">
        <strong>Metode:</strong>
        {{ $nota->metode_bayar }}
    </div>

    <div class="mb-3">
        <strong>Status:</strong>
        {{ $nota->status_bayar }}
    </div>

</div>

@endsection