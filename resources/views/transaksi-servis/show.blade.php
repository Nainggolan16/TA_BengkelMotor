@extends('layouts.app-admin')

@section('content')

@php
    $canModifyDetail = in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'], true);
    $selectedJenisServisIds = $transaction->detailServis->pluck('id_jenis_servis')->filter();
    $selectedSukuCadangIds = $transaction->detailSukuCadang->pluck('id_suku_cadang')->filter();
@endphp

<div class="max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold">Detail Transaksi Servis</h1>
            <p class="text-sm text-gray-600">Kelola status, pembayaran, dan cetak nota setelah pembayaran.</p>
        </div>
        <a href="/transaksi-servis" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Kembali</a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid gap-6">
        <div class="bg-white rounded shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h2 class="font-semibold text-gray-700">Kode Transaksi</h2>
                    <p class="text-lg">{{ $transaction->kode_order }}</p>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-700">Tanggal Masuk</h2>
                    <p>{{ $transaction->tanggal_masuk }}</p>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-700">Status</h2>
                    @php
                        $status = $transaction->status;
                        $badgeClass = 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold ';
                        if ($status === 'menunggu_pemeriksaan') $badgeClass .= 'bg-yellow-100 text-yellow-800';
                        elseif ($status === 'pembelian_barang') $badgeClass .= 'bg-purple-100 text-purple-800';
                        elseif ($status === 'proses_pengerjaan') $badgeClass .= 'bg-blue-100 text-blue-800';
                        elseif ($status === 'selesai') $badgeClass .= 'bg-green-100 text-green-800';
                        elseif ($status === 'sudah_dibayar') $badgeClass .= 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="{{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Data Pelanggan</h2>
                <div class="space-y-2 text-sm text-gray-700">
                    <div><span class="font-medium">Nama:</span> {{ optional(optional($transaction->kendaraan)->pelanggan)->nama }}</div>
                    <div><span class="font-medium">No HP:</span> {{ optional(optional($transaction->kendaraan)->pelanggan)->no_telepon }}</div>
                </div>
            </div>
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Data Kendaraan</h2>
                <div class="space-y-2 text-sm text-gray-700">
                    <div><span class="font-medium">Plat Nomor:</span> {{ optional($transaction->kendaraan)->plat_nomor }}</div>
                    <div><span class="font-medium">Nama Kendaraan:</span> {{ optional($transaction->kendaraan)->nama_kendaraan }}</div>
                </div>
            </div>
        </div>

        @if($canModifyDetail)
            <div class="bg-white rounded shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Tambah Detail Transaksi</h2>
                <div class="grid gap-4 lg:grid-cols-3">
                    <form action="{{ route('transaksi-servis.add-servis', $transaction->id) }}" method="POST" class="space-y-3 rounded border p-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Servis</label>
                            <div class="relative mt-1" data-searchable-select>
                                <input type="hidden" name="id_jenis_servis" data-searchable-value>
                                <input type="text" data-searchable-input class="w-full border rounded p-2 text-sm" placeholder="Cari atau pilih jenis servis..." autocomplete="off" required>
                                <div data-searchable-options class="absolute z-30 mt-1 hidden max-h-64 w-full overflow-y-auto rounded border bg-white shadow-lg">
                                    @foreach($jenisServis as $item)
                                        @unless($selectedJenisServisIds->contains($item->id))
                                            @php
                                                $jenisServisLabel = (optional($item->kategoriServis)->nama_kategori ? optional($item->kategoriServis)->nama_kategori . ' - ' : '') . $item->nama_servis . ' - Rp ' . number_format($item->harga_jasa, 0, ',', '.');
                                            @endphp
                                            <button type="button" data-searchable-option data-id="{{ $item->id }}" data-label="{{ $jenisServisLabel }}" class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-blue-50">
                                                <span class="block break-words">{{ $jenisServisLabel }}</span>
                                            </button>
                                        @endunless
                                    @endforeach
                                    <div data-searchable-empty class="hidden px-3 py-2 text-sm text-gray-500">Tidak ada pilihan yang cocok.</div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tambah Jenis Servis</button>
                    </form>

                    <form action="{{ route('transaksi-servis.add-sparepart', $transaction->id) }}" method="POST" class="space-y-3 rounded border p-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Suku Cadang</label>
                            <div class="relative mt-1" data-searchable-select>
                                <input type="hidden" name="id_suku_cadang" data-searchable-value>
                                <input type="text" data-searchable-input class="w-full border rounded p-2 text-sm" placeholder="Cari atau pilih suku cadang..." autocomplete="off" required>
                                <div data-searchable-options class="absolute z-30 mt-1 hidden max-h-64 w-full overflow-y-auto rounded border bg-white shadow-lg">
                                    @foreach($sukuCadang as $item)
                                        @unless($selectedSukuCadangIds->contains($item->id))
                                            @php
                                                $sukuCadangLabel = $item->kode . ' - ' . $item->nama . ' - Stok ' . $item->stok . ' - Rp ' . number_format($item->harga_jual, 0, ',', '.');
                                            @endphp
                                            <button type="button" data-searchable-option data-id="{{ $item->id }}" data-label="{{ $sukuCadangLabel }}" class="block w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-green-50">
                                                <span class="block break-words">{{ $sukuCadangLabel }}</span>
                                            </button>
                                        @endunless
                                    @endforeach
                                    <div data-searchable-empty class="hidden px-3 py-2 text-sm text-gray-500">Tidak ada pilihan yang cocok.</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qty</label>
                            <input type="number" name="jumlah" min="1" value="1" class="mt-1 w-full border rounded p-2 text-sm" required>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tambah Suku Cadang</button>
                    </form>

                    <form action="{{ route('transaksi-servis.add-jasa-tambahan', $transaction->id) }}" method="POST" class="space-y-3 rounded border p-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jasa Tambahan</label>
                            <input type="text" name="nama_jasa" class="mt-1 w-full border rounded p-2 text-sm" placeholder="Contoh: Las dudukan knalpot" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Biaya</label>
                            <input type="number" name="biaya" min="0" step="0.01" value="0" class="mt-1 w-full border rounded p-2 text-sm" required>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Tambah Jasa Tambahan</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Data Servis</h2>
            <div class="space-y-3 text-sm text-gray-700">
                <div><span class="font-medium">Keluhan:</span> {{ $transaction->keluhan }}</div>
                <div>
                    <span class="font-medium">Jenis Servis:</span>
                    <ul class="mt-2 list-disc pl-5 text-gray-700">
                        @forelse($transaction->detailServis as $detail)
                            <li>{{ optional($detail->jenisServis)->nama_servis }} — Rp {{ number_format($detail->harga_jasa, 0, ',', '.') }}</li>
                            @if($canModifyDetail)
                                <li class="list-none">
                                    <form action="{{ route('transaksi-servis.remove-servis', [$transaction->id, $detail->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('Hapus jenis servis ini?')">Hapus</button>
                                    </form>
                                </li>
                            @endif
                        @empty
                            <li class="text-gray-500">Belum ada jenis servis.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Data Sparepart</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3">Sparepart</th>
                            <th class="p-3">Qty</th>
                            <th class="p-3">Harga</th>
                            <th class="p-3">Subtotal</th>
                            @if($canModifyDetail)
                                <th class="p-3">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaction->detailSukuCadang as $detail)
                        <tr class="border-t">
                            <td class="p-3">{{ optional($detail->sukuCadang)->nama }}</td>
                            <td class="p-3">{{ $detail->jumlah }}</td>
                            <td class="p-3">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                            <td class="p-3">Rp {{ number_format($detail->harga_jual * $detail->jumlah, 0, ',', '.') }}</td>
                            @if($canModifyDetail)
                                <td class="p-3">
                                    <form action="{{ route('transaksi-servis.remove-sparepart', [$transaction->id, $detail->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('Hapus suku cadang ini?')">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $canModifyDetail ? 5 : 4 }}" class="p-3 text-gray-500">Belum ada sparepart.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Data Jasa Tambahan</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3">Nama Jasa</th>
                            <th class="p-3">Biaya</th>
                            @if($canModifyDetail)
                                <th class="p-3">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaction->jasaTambahan as $detail)
                            <tr class="border-t">
                                <td class="p-3">{{ $detail->nama_jasa }}</td>
                                <td class="p-3">Rp {{ number_format($detail->biaya, 0, ',', '.') }}</td>
                                @if($canModifyDetail)
                                    <td class="p-3">
                                        <form action="{{ route('transaksi-servis.remove-jasa-tambahan', [$transaction->id, $detail->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:underline" onclick="return confirm('Hapus jasa tambahan ini?')">Hapus</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $canModifyDetail ? 3 : 2 }}" class="p-3 text-gray-500">Belum ada jasa tambahan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6 grid gap-4 md:grid-cols-2">
                <div class="space-y-2 text-sm text-gray-700">
                <div><span class="font-medium">Total Jasa (Standar):</span> Rp {{ number_format($transaction->biaya_jasa, 0, ',', '.') }}</div>
                <div><span class="font-medium">Tambahan Biaya Jasa:</span> Rp {{ number_format($transaction->biaya_jasa_tambahan ?? 0, 0, ',', '.') }}</div>
                <div><span class="font-medium">Total Sparepart:</span> Rp {{ number_format($transaction->total_harga - ($transaction->biaya_jasa + ($transaction->biaya_jasa_tambahan ?? 0)), 0, ',', '.') }}</div>
                <div class="font-semibold"><span class="font-medium">Grand Total:</span> Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</div>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <div><span class="font-medium">Tanggal Selesai:</span> {{ $transaction->tanggal_selesai ?? '-' }}</div>
                <div><span class="font-medium">Tanggal Bayar:</span> {{ $transaction->tanggal_bayar ?? '-' }}</div>
                <div><span class="font-medium">Metode Pembayaran:</span> {{ $transaction->metode_pembayaran ?? '-' }}</div>
            </div>
        </div>

        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Tindakan Transaksi</h2>
            <form action="/transaksi-servis/{{ $transaction->id }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                @if($transaction->status === 'menunggu_pemeriksaan')
                    <button type="submit" name="action" value="mulai" class="px-4 py-2 bg-blue-600 text-white rounded">Mulai Pengerjaan</button>
                @elseif($transaction->status === 'proses_pengerjaan')
                    <button type="submit" name="action" value="selesai" class="px-4 py-2 bg-green-600 text-white rounded">Selesaikan Servis</button>
                @elseif($transaction->status === 'selesai' || $transaction->status === 'pembelian_barang')
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="w-full border rounded p-2" required>
                                <option value="">Pilih metode pembayaran</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>
                        <button type="submit" name="action" value="bayar" class="px-4 py-2 bg-indigo-600 text-white rounded">Proses Pembayaran</button>
                    </div>
                @elseif($transaction->status === 'sudah_dibayar')
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('transaksi-servis.nota', $transaction->id) }}" target="_blank" class="px-4 py-2 bg-gray-800 text-white rounded">Cetak Nota</a>
                        <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-500 text-white rounded">Cetak Halaman</button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

@if($canModifyDetail)
    <script>
        document.querySelectorAll('[data-searchable-select]').forEach((select) => {
            const input = select.querySelector('[data-searchable-input]');
            const hiddenInput = select.querySelector('[data-searchable-value]');
            const panel = select.querySelector('[data-searchable-options]');
            const options = Array.from(select.querySelectorAll('[data-searchable-option]'));
            const emptyState = select.querySelector('[data-searchable-empty]');

            const normalize = (value) => value.toLowerCase().trim();

            const openPanel = () => {
                panel.classList.remove('hidden');
            };

            const closePanel = () => {
                panel.classList.add('hidden');
            };

            const syncExactValue = () => {
                const exactOption = options.find((option) => normalize(option.dataset.label) === normalize(input.value));
                hiddenInput.value = exactOption ? exactOption.dataset.id : '';
                input.setCustomValidity(input.value === '' || hiddenInput.value ? '' : 'Pilih data dari daftar.');
            };

            const filterOptions = () => {
                const keyword = normalize(input.value);
                let visibleCount = 0;

                options.forEach((option) => {
                    const isVisible = normalize(option.dataset.label).includes(keyword);
                    option.classList.toggle('hidden', !isVisible);
                    if (isVisible) visibleCount++;
                });

                emptyState.classList.toggle('hidden', visibleCount > 0);
                syncExactValue();
            };

            input.addEventListener('focus', () => {
                filterOptions();
                openPanel();
            });

            input.addEventListener('input', () => {
                filterOptions();
                openPanel();
            });

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.label;
                    hiddenInput.value = option.dataset.id;
                    input.setCustomValidity('');
                    filterOptions();
                    closePanel();
                });
            });

            input.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closePanel();
                }
            });

            input.closest('form').addEventListener('submit', (event) => {
                syncExactValue();

                if (!hiddenInput.value) {
                    event.preventDefault();
                    input.setCustomValidity('Pilih data dari daftar.');
                    input.reportValidity();
                    openPanel();
                }
            });

            document.addEventListener('click', (event) => {
                if (!select.contains(event.target)) {
                    closePanel();
                }
            });
        });
    </script>
@endif

@endsection
