<h1>Detail Suku Cadang</h1>

<a href="/detail-suku-cadang/create">
    Tambah Sparepart
</a>

<table border="1" cellpadding="10">

    <tr>
        <th>No</th>
        <th>Order</th>
        <th>Suku Cadang</th>
        <th>Jumlah</th>
        <th>Harga</th>
        <th>Subtotal</th>
        <th>Aksi</th>
    </tr>

    @foreach($detail as $item)

    <tr>

        <td>{{ $loop->iteration }}</td>

        <td>
            {{ $item->orderServis->kode_order }}
        </td>

        <td>
            {{ $item->sukuCadang->nama }}
        </td>

        <td>{{ $item->jumlah }}</td>

        <td>
            Rp {{ number_format($item->harga_jual) }}
        </td>

        <td>
            Rp {{ number_format(
                $item->harga_jual * $item->jumlah
            ) }}
        </td>

        <td>

            <form action="/detail-suku-cadang/{{ $item->id }}"
                  method="POST">

                @csrf
                @method('DELETE')

                <button type="submit">
                    Hapus
                </button>

            </form>

        </td>

    </tr>

    @endforeach

</table>
