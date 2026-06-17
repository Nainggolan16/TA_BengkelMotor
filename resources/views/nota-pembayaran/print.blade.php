<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Bengkel</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-gray-100 p-10">

<div class="max-w-3xl mx-auto bg-white p-10 shadow rounded">

    <div class="flex justify-between items-center mb-8">

        <div>

            <h1 class="text-3xl font-bold">
                Bengkel Motor
            </h1>

            <p class="text-gray-500">
                Invoice Pembayaran
            </p>

        </div>

        <div class="text-right">

            <p>
                <strong>Kode Order:</strong>
                {{ $nota->orderServis->kode_order }}
            </p>

            <p>
                <strong>Tanggal:</strong>
                {{ $nota->dibayar_pada }}
            </p>

        </div>

    </div>

    <hr class="mb-6">

    <div class="mb-6">

        <p>
            <strong>Pelanggan:</strong>
            {{ $nota->orderServis->kendaraan->pelanggan->nama }}
        </p>

        <p>
            <strong>Kendaraan:</strong>

            {{ $nota->orderServis->kendaraan->merk }}

            {{ $nota->orderServis->kendaraan->tipe }}

        </p>

        <p>
            <strong>Plat:</strong>

            {{ $nota->orderServis->kendaraan->plat_nomor }}

        </p>

    </div>

    <table class="w-full border">

        <thead class="bg-gray-200">

            <tr>

                <th class="border p-3 text-left">
                    Item
                </th>

                <th class="border p-3">
                    Qty
                </th>

                <th class="border p-3">
                    Harga
                </th>

                <th class="border p-3">
                    Total
                </th>

            </tr>

        </thead>

        <tbody>

            <!-- Jasa -->
            <tr>

                <td class="border p-3">

                    Jasa
                    {{ $nota->orderServis->jenisServis->nama }}

                </td>

                <td class="border p-3 text-center">
                    1
                </td>

                <td class="border p-3 text-right">

                    Rp
                    {{ number_format(
                        $nota->orderServis->biaya_jasa,
                        0,
                        ',',
                        '.'
                    ) }}

                </td>

                <td class="border p-3 text-right">

                    Rp
                    {{ number_format(
                        $nota->orderServis->biaya_jasa,
                        0,
                        ',',
                        '.'
                    ) }}

                </td>

            </tr>

            <!-- Sparepart -->
            @foreach(
                $nota->orderServis->detailSukuCadang
                as
                $item
            )

            <tr>

                <td class="border p-3">

                    {{ $item->sukuCadang->nama }}

                </td>

                <td class="border p-3 text-center">

                    {{ $item->jumlah }}

                </td>

                <td class="border p-3 text-right">

                    Rp
                    {{ number_format(
                        $item->harga_jual,
                        0,
                        ',',
                        '.'
                    ) }}

                </td>

                <td class="border p-3 text-right">

                    Rp

                    {{ number_format(
                        $item->harga_jual * $item->jumlah,
                        0,
                        ',',
                        '.'
                    ) }}

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    <div class="mt-8 text-right">

        <h2 class="text-3xl font-bold">

            Total:
            Rp

            {{ number_format(
                $nota->total,
                0,
                ',',
                '.'
            ) }}

        </h2>

    </div>

    <div class="mt-10 flex justify-end gap-4">

        <a href="/nota-pembayaran"
           class="bg-gray-500 text-white px-5 py-2 rounded">

            Kembali

        </a>

        <button
            onclick="window.print()"
            class="bg-blue-500 text-white px-5 py-2 rounded">

            Cetak

        </button>

    </div>

</div>

</body>
</html>