@extends('layouts.app-admin')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-6">

    {{-- ── Header ──────────────────────────────────────────── --}}
    <div class="mb-6">
        <div class="bg-gradient-to-r from-gray-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">
                        Buat Transaksi Servis
                    </h1>

                    <p class="text-blue-100 mt-1">
                        Kelola transaksi servis kendaraan pelanggan
                    </p>
                </div>

                <a href="{{ route('transaksi-servis.index') }}"
                class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- ── Validasi Error ───────────────────────────────────── --}}
    @if ($errors->any())
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Form Utama ───────────────────────────────────────── --}}
    <form
        id="transaksiForm"
        action="{{ route('transaksi-servis.store') }}"
        method="POST"
        class="grid grid-cols-1 lg:grid-cols-3 gap-6"
    >
        @csrf

        {{-- ════════════════════════════════════════════════════
             KOLOM KIRI (col-span-2): semua input
        ════════════════════════════════════════════════════ --}}
        <div class="col-span-2 space-y-6">

            {{-- ── 1. Pelanggan & Kendaraan ─────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="border-b border-slate-200 pb-3 mb-5">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Pelanggan & Kendaraan
                    </h2>
                </div>
                {{-- Cari Pelanggan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Cari Pelanggan
                    </label>
                    <input
                        type="text"
                        id="pelangganSearch"
                        placeholder="Ketik nama atau nomor telepon..."
                        class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                        autocomplete="off"
                    >
                    <div
                        id="pelangganResults"
                        class="mt-1 border rounded bg-white max-h-48 overflow-auto hidden shadow"
                    ></div>
                    <div class="mt-2 flex items-center gap-3">
                        <button type="button" id="btnTambahPelanggan" class="text-sm text-blue-600 hover:underline">
                            + Tambah Pelanggan Baru
                        </button>
                        <span id="selectedPelangganLabel" class="text-sm text-gray-700 font-medium"></span>
                    </div>
                    <input type="hidden" name="pelanggan_id" id="pelanggan_id">
                </div>

                {{-- Pilih Kendaraan --}}
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan</label>
                    <select
                        name="kendaraan_id"
                        id="kendaraanSelect"
                        class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                    >
                        <option value="">-- Pilih kendaraan --</option>
                    </select>
                    <button type="button" id="btnTambahKendaraan" class="mt-2 text-sm text-blue-600 hover:underline">
                        + Tambah Kendaraan Baru
                    </button>
                </div>

                {{-- Form Tambah Pelanggan (toggle) --}}
                <div id="formTambahPelanggan" class="mt-4 border-t pt-4 hidden">
                    <h3 class="font-medium mb-2">Data Pelanggan Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1">Nama</label>
                            <input
                                type="text"
                                id="pelanggan_nama"
                                class="w-full border rounded p-2"
                                disabled
                            >
                        </div>
                        <div>
                            <label class="block text-sm mb-1">No Telepon</label>
                            <input
                                type="text"
                                id="pelanggan_no_telepon"
                                class="w-full border rounded p-2"
                                disabled
                            >
                        </div>
                    </div>
                </div>

                {{-- Form Tambah Kendaraan (toggle) --}}
                <div id="formTambahKendaraan" class="mt-4 border-t pt-4 hidden">
                    <h3 class="font-medium mb-2">Data Kendaraan Baru</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm mb-1">Plat Nomor</label>
                            <input
                                type="text"
                                id="plat_nomor"
                                class="w-full border rounded p-2"
                                disabled
                            >
                        </div>
                        <div>
                            <label class="block text-sm mb-1">Nama Kendaraan</label>
                            <input
                                type="text"
                                id="nama_kendaraan"
                                class="w-full border rounded p-2"
                                disabled
                            >
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── 2. Jenis Servis ──────────────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="border-b border-slate-200 pb-3 mb-5">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Jenis Servis
                    </h2>
                </div>

                <div class="flex gap-2 mb-3">
                    <div class="flex-1">
                        <select id="jenisServisSelect">
                            <option value=""></option>
                            @foreach ($jenisServis as $item)
                                <option
                                    value="{{ $item->id }}"
                                    data-nama="{{ $item->nama_servis }}"
                                    data-harga="{{ $item->harga_jasa }}"
                                >
                                    {{ $item->nama_servis }}
                                    (Rp {{ number_format($item->harga_jasa, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button
                        type="button"
                        id="btnTambahServis"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Tambah
                    </button>
                </div>

                <div id="selectedServisContainer" class="space-y-2"></div>
            </section>

            {{-- ── 3. Jasa Tambahan ─────────────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="border-b border-slate-200 pb-1">                
                        <h2 class="text-lg font-semibold text-slate-800">
                            Jasa Tambahan
                        </h2>
                    </div>
                    <button
                        type="button"
                        id="btnTambahJasaTambahan"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700"
                    >
                        + Tambah Jasa Tambahan
                    </button>
                </div>
                <div id="jasaTambahanRows" class="space-y-3"></div>
            </section>

            {{-- ── 4. Suku Cadang ───────────────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="border-b border-slate-200 pb-3">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Suku Cadang
                    </h2>
                </div>
                <div class="flex gap-3 mt-4 items-start">
                    <div class="flex-1 mr-3">
                        <select id="sukuCadangSelect">
                            <option value=""></option>

                            @foreach ($sukuCadang as $item)
                                @if($item->stok > 0)
                                    <option
                                        value="{{ $item->id }}"
                                        data-nama="{{ $item->nama }}"
                                        data-harga="{{ $item->harga_jual }}"
                                        data-stok="{{ $item->stok }}"
                                    >
                                        {{ $item->kode }} - {{ $item->nama }}
                                        (Stok: {{ $item->stok }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button
                        type="button"
                        id="btnTambahSparepart"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                    >
                        Tambah
                    </button>
                </div>

                <div id="selectedSparepartContainer" class="space-y-2"></div>
            </section>

            {{-- ── 5. Keluhan ───────────────────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">  
                <div class="border-b border-slate-200 pb-3 mb-5">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Keluhan Pelanggan
                    </h2>
                </div>              
                <textarea
                    name="keluhan"
                    rows="3"
                    placeholder="Deskripsikan keluhan pelanggan..."
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                ></textarea>
            </section>

            {{-- ── 6. Status ────────────────────────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="border-b border-slate-200 pb-3 mb-5">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Status Transaksi
                    </h2>
                </div>
                <select
                    name="status"
                    class="w-full border rounded p-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                    <option value="menunggu_pemeriksaan" selected>Menunggu Pemeriksaan</option>
                    <option value="proses_pengerjaan">Proses Pengerjaan</option>
                    <option value="pembelian_barang">Pembelian Barang </option>
                </select>
            </section>

        </div>{{-- /col-span-2 --}}

        {{-- ════════════════════════════════════════════════════
             KOLOM KANAN: Ringkasan Transaksi (sticky)
        ════════════════════════════════════════════════════ --}}
        <aside class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 sticky top-4 overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">
                    Ringkasan Transaksi
                </h3>
                <p class="text-sm text-slate-500 mt-1">
                    Total biaya servis dan suku cadang.
                </p>
            </div>

            {{-- Body --}}
            <div class="p-6 space-y-4">

                <div class="flex justify-between items-center">
                    <span class="text-slate-600">
                        Total Jasa
                    </span>
                    <span id="totalJasa" class="font-medium text-slate-800">
                        Rp 0
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-600">
                        Jasa Tambahan
                    </span>
                    <span id="totalJasaTambahan" class="font-medium text-slate-800">
                        Rp 0
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-600">
                        Total Sparepart
                    </span>
                    <span id="totalSpare" class="font-medium text-slate-800">
                        Rp 0
                    </span>
                </div>

                {{-- Grand Total --}}
                <div class="border-t border-slate-200 pt-3">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-slate-700">
                            Grand Total
                        </span>
                        <span id="grandTotal" class="font-semibold text-slate-800">
                            Rp 0
                        </span>
                    </div>
                </div>

                <input
                    type="hidden"
                    name="biaya_jasa_tambahan"
                    id="biayaJasaTambahan"
                    value="0"
                >

                {{-- Action Buttons --}}
                <div class="pt-2 space-y-3">

                    <button
                        type="submit"
                        class="w-full px-4 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700 transition"
                    >
                        Simpan Transaksi
                    </button>
                    <a
                        href="/transaksi-servis"
                        class="block w-full px-4 py-3 rounded-xl border border-slate-300 text-center font-medium text-slate-700 hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>
        </aside>

    </form>
</div>

{{-- ── Template: Baris Jasa Tambahan ───────────────────────── --}}
<template id="templateJasaTambahanRow">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end border rounded p-3 jasa-tambahan-row">
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jasa</label>
            <input
                type="text"
                name="jasa_tambahan[][nama]"
                class="w-full border rounded p-2 jasa-tambahan-nama"
                placeholder="Contoh: Las Dudukan Knalpot"
            >
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Biaya (Rp)</label>
            <input
                type="number"
                name="jasa_tambahan[][biaya]"
                step="0.01"
                min="0"
                value="0"
                class="w-full border rounded p-2 jasa-tambahan-biaya"
            >
        </div>
        <div class="md:col-span-3">
            <button type="button" class="text-red-600 text-sm hover:underline remove-jasa-tambahan">
                Hapus baris ini
            </button>
        </div>
    </div>
</template>

{{-- ════════════════════════════════════════════════════════════
     JAVASCRIPT
════════════════════════════════════════════════════════════ --}}
<script>
// ─────────────────────────────────────────────────────────────
// DATA dari Blade (PHP → JS)
// ─────────────────────────────────────────────────────────────
const pelangganData  = @json($pelanggan);
const kendaraanData  = @json($kendaraan);
const jenisServisData = @json($jenisServis->keyBy('id'));
const sukuCadangData  = @json($sukuCadang->keyBy('id'));

// ─────────────────────────────────────────────────────────────
// ELEMENT REFERENCES
// ─────────────────────────────────────────────────────────────
const elPelangganSearch        = document.getElementById('pelangganSearch');
const elPelangganResults       = document.getElementById('pelangganResults');
const elPelangganId            = document.getElementById('pelanggan_id');
const elSelectedPelangganLabel = document.getElementById('selectedPelangganLabel');
const elBtnTambahPelanggan     = document.getElementById('btnTambahPelanggan');
const elFormTambahPelanggan    = document.getElementById('formTambahPelanggan');
const elPelangganNama          = document.getElementById('pelanggan_nama');
const elPelangganNoTelepon     = document.getElementById('pelanggan_no_telepon');

const elKendaraanSelect        = document.getElementById('kendaraanSelect');
const elBtnTambahKendaraan     = document.getElementById('btnTambahKendaraan');
const elFormTambahKendaraan    = document.getElementById('formTambahKendaraan');
const elPlatNomor              = document.getElementById('plat_nomor');
const elNamaKendaraan          = document.getElementById('nama_kendaraan');

const elJenisServisSelect      = document.getElementById('jenisServisSelect');
const elBtnTambahServis        = document.getElementById('btnTambahServis');
const elSelectedServisContainer = document.getElementById('selectedServisContainer');

const elBtnTambahJasaTambahan  = document.getElementById('btnTambahJasaTambahan');
const elJasaTambahanRows       = document.getElementById('jasaTambahanRows');
const elTemplateJasaTambahan   = document.getElementById('templateJasaTambahanRow');

const elSukuCadangSelect       = document.getElementById('sukuCadangSelect');
const elBtnTambahSparepart     = document.getElementById('btnTambahSparepart');
const elSelectedSparepartContainer = document.getElementById('selectedSparepartContainer');

const elTotalJasa              = document.getElementById('totalJasa');
const elTotalJasaTambahan      = document.getElementById('totalJasaTambahan');
const elTotalSpare             = document.getElementById('totalSpare');
const elGrandTotal             = document.getElementById('grandTotal');
const elBiayaJasaTambahan      = document.getElementById('biayaJasaTambahan');

// ─────────────────────────────────────────────────────────────
// STATE
// ─────────────────────────────────────────────────────────────
let selectedServices   = [];
let selectedSpareparts = [];

// ─────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────
function formatRp(amount) {
    return 'Rp ' + Number(amount).toLocaleString('id-ID');
}

// ─────────────────────────────────────────────────────────────
// PELANGGAN — mode input (baru vs pilih existing)
// ─────────────────────────────────────────────────────────────
function setPelangganMode(useNew) {
    if (useNew) {
        elPelangganNama.name       = 'pelanggan_nama';
        elPelangganNoTelepon.name  = 'pelanggan_no_telepon';
        elPelangganNama.disabled   = false;
        elPelangganNoTelepon.disabled = false;
        elPelangganId.value        = '';
        elSelectedPelangganLabel.textContent = '';
    } else {
        elPelangganNama.removeAttribute('name');
        elPelangganNoTelepon.removeAttribute('name');
        elPelangganNama.disabled   = true;
        elPelangganNoTelepon.disabled = true;
        elPelangganNama.value      = '';
        elPelangganNoTelepon.value = '';
    }
}

// ─────────────────────────────────────────────────────────────
// KENDARAAN — mode input (baru vs pilih existing)
// ─────────────────────────────────────────────────────────────
function setKendaraanMode(useNew) {
    if (useNew) {
        elKendaraanSelect.removeAttribute('name');
        elKendaraanSelect.value    = '';
        elPlatNomor.name           = 'plat_nomor';
        elNamaKendaraan.name       = 'nama_kendaraan';
        elPlatNomor.disabled       = false;
        elNamaKendaraan.disabled   = false;
    } else {
        elKendaraanSelect.name     = 'kendaraan_id';
        elPlatNomor.removeAttribute('name');
        elNamaKendaraan.removeAttribute('name');
        elPlatNomor.disabled       = true;
        elNamaKendaraan.disabled   = true;
        elPlatNomor.value          = '';
        elNamaKendaraan.value      = '';
    }
}

// ─────────────────────────────────────────────────────────────
// PELANGGAN — search & pilih
// ─────────────────────────────────────────────────────────────
elPelangganSearch.addEventListener('input', function () {
    const query = this.value.trim().toLowerCase();
    if (!query) {
        elPelangganResults.classList.add('hidden');
        return;
    }

    const matches = pelangganData.filter(p =>
        (p.nama        || '').toLowerCase().includes(query) ||
        (p.no_telepon  || '').toLowerCase().includes(query)
    );

    elPelangganResults.innerHTML = matches.length
        ? matches.map(p => `
            <div class="px-3 py-2 hover:bg-gray-100 cursor-pointer" data-id="${p.id}">
                <div class="font-medium">${p.nama}</div>
                <div class="text-xs text-gray-500">${p.no_telepon}</div>
            </div>`).join('')
        : '<div class="p-3 text-sm text-gray-500">Pelanggan tidak ditemukan</div>';

    elPelangganResults.classList.remove('hidden');
});

elPelangganResults.addEventListener('click', function (e) {
    const el = e.target.closest('[data-id]');
    if (!el) return;

    const pelanggan = pelangganData.find(p => p.id == el.dataset.id);
    elPelangganId.value               = pelanggan.id;
    elSelectedPelangganLabel.textContent = `✔ ${pelanggan.nama} — ${pelanggan.no_telepon}`;
    elPelangganResults.classList.add('hidden');
    elPelangganSearch.value           = '';
    elFormTambahPelanggan.classList.add('hidden');
    setPelangganMode(false);
    populateKendaraan(pelanggan.id);
});

elBtnTambahPelanggan.addEventListener('click', function () {
    const show = elFormTambahPelanggan.classList.toggle('hidden') === false;
    setPelangganMode(show);
});

elBtnTambahKendaraan.addEventListener('click', function () {
    const show = elFormTambahKendaraan.classList.toggle('hidden') === false;
    setKendaraanMode(show);
});

// ─────────────────────────────────────────────────────────────
// KENDARAAN — populate dropdown berdasarkan pelanggan
// ─────────────────────────────────────────────────────────────
function populateKendaraan(pelangganId) {
    const list = kendaraanData.filter(k => k.id_pelanggan == pelangganId);

    elKendaraanSelect.innerHTML = '<option value="">-- Pilih kendaraan --</option>';

    if (!list.length) {
        elKendaraanSelect.innerHTML = '<option value="">(Belum ada kendaraan terdaftar)</option>';
        elFormTambahKendaraan.classList.remove('hidden');
        setKendaraanMode(true);
        return;
    }

    list.forEach(k => {
        const opt = document.createElement('option');
        opt.value       = k.id;
        opt.textContent = `${k.plat_nomor} — ${k.nama_kendaraan || '-'}`;
        elKendaraanSelect.appendChild(opt);
    });

    elFormTambahKendaraan.classList.add('hidden');
    setKendaraanMode(false);
}

// ─────────────────────────────────────────────────────────────
// JENIS SERVIS — tambah & render
// ─────────────────────────────────────────────────────────────
elBtnTambahServis.addEventListener('click', function () {
    const id = tsJenisServis.getValue();
    if (!id) return;
    if (selectedServices.find(s => s.id == id)) {
        alert('Servis ini sudah ditambahkan.');
        return;
    }
    const item = jenisServisData[id];
    if (!item) return;
    selectedServices.push({
        id   : id,
        nama : item.nama_servis,
        harga: parseFloat(item.harga_jasa),
    });
    tsJenisServis.clear();
    renderServices();
    calcTotals();
});

function renderServices() {
    elSelectedServisContainer.innerHTML = selectedServices.map((s, i) => `
        <div class="flex justify-between items-center border rounded p-3 bg-gray-50">
            <div>
                <div class="font-medium">${s.nama}</div>
                <div class="text-sm text-gray-500">${formatRp(s.harga)}</div>
                <input type="hidden" name="jenis_servis[]" value="${s.id}">
            </div>
            <button type="button" onclick="removeService(${i})" class="text-red-500 hover:text-red-700 text-sm">
                Hapus
            </button>
        </div>
    `).join('');
}

window.removeService = function (index) {
    selectedServices.splice(index, 1);
    renderServices();
    calcTotals();
};

// ─────────────────────────────────────────────────────────────
// JASA TAMBAHAN — tambah baris & remove
// ─────────────────────────────────────────────────────────────
elBtnTambahJasaTambahan.addEventListener('click', function () {
    addJasaTambahanRow();
});

function addJasaTambahanRow(nama = '', biaya = 0) {
    const fragment = document.importNode(elTemplateJasaTambahan.content, true);
    const row      = fragment.querySelector('.jasa-tambahan-row');

    row.querySelector('.jasa-tambahan-nama').value  = nama;
    row.querySelector('.jasa-tambahan-biaya').value = biaya;

    row.querySelector('.jasa-tambahan-biaya').addEventListener('input', calcTotals);
    row.querySelector('.remove-jasa-tambahan').addEventListener('click', function () {
        row.remove();
        calcTotals();
    });

    elJasaTambahanRows.appendChild(row);
    calcTotals();
}

// ─────────────────────────────────────────────────────────────
// SUKU CADANG — tambah, render, update qty, hapus
// ─────────────────────────────────────────────────────────────
elBtnTambahSparepart.addEventListener('click', function () {
    const opt = elSukuCadangSelect.options[elSukuCadangSelect.selectedIndex];
    const stok = parseInt(opt.dataset.stok);

    if (stok <= 0) {
        alert('Stok barang habis');
        return;
    }
    
    if (!opt.value) return;

    if (selectedSpareparts.find(s => s.id == opt.value)) {
        alert('Suku cadang ini sudah ditambahkan.');
        return;
    }

    selectedSpareparts.push({
        id   : opt.value,
        nama : opt.dataset.nama,
        harga: parseFloat(opt.dataset.harga),
        qty  : 1,
        stok : parseInt(opt.dataset.stok),
    });

    renderSpareparts();
    calcTotals();
});

function renderSpareparts() {
    elSelectedSparepartContainer.innerHTML = selectedSpareparts.map((item, i) => `
        <div class="border rounded p-3 bg-gray-50">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-medium">${item.nama}</div>
                    <div class="text-sm text-gray-500">${formatRp(item.harga)} / pcs</div>
                </div>
                <button type="button" onclick="removeSparepart(${i})" class="text-red-500 hover:text-red-700 text-sm">
                    Hapus
                </button>
            </div>
            <div class="mt-2 flex items-center gap-2">
                <label class="text-sm text-gray-600">Qty:</label>
                <input
                    type="number"
                    min="1"
                    max="${item.stok}"
                    value="${item.qty}"
                    onchange="updateQty(${i}, this.value)"
                    class="border rounded p-1 w-20 text-center"
                >
                <span class="text-sm text-gray-500">Subtotal: ${formatRp(item.harga * item.qty)}</span>
            </div>
            <input type="hidden" name="suku_cadang[${i}][id]"  value="${item.id}">
            <input type="hidden" name="suku_cadang[${i}][qty]" value="${item.qty}">
        </div>
    `).join('');
}

window.updateQty = function (index, value) {
    let qty = parseInt(value) || 1;

    const stok = selectedSpareparts[index].stok;

    if (qty > stok) {
        alert(`Stok hanya tersedia ${stok} pcs`);
        qty = stok;
    }

    if (qty < 1) {
        qty = 1;
    }

    selectedSpareparts[index].qty = qty;

    renderSpareparts();
    calcTotals();
};

window.removeSparepart = function (index) {
    selectedSpareparts.splice(index, 1);
    renderSpareparts();
    calcTotals();
};

// ─────────────────────────────────────────────────────────────
// KALKULASI TOTAL
// ─────────────────────────────────────────────────────────────
function calcTotals() {
    const totalJasa = selectedServices.reduce((sum, s) => sum + Number(s.harga), 0);

    const totalTambahan = Array.from(
        document.querySelectorAll('.jasa-tambahan-biaya')
    ).reduce((sum, el) => sum + Number(el.value || 0), 0);

    const totalSpare = selectedSpareparts.reduce(
        (sum, item) => sum + item.harga * item.qty, 0
    );

    const grandTotal = totalJasa + totalTambahan + totalSpare;

    elTotalJasa.textContent          = formatRp(totalJasa + totalTambahan);
    elTotalJasaTambahan.textContent  = formatRp(totalTambahan);
    elTotalSpare.textContent         = formatRp(totalSpare);
    elGrandTotal.textContent         = formatRp(grandTotal);
    elBiayaJasaTambahan.value        = totalTambahan;
}

// ─────────────────────────────────────────────────────────────
// INIT
// ─────────────────────────────────────────────────────────────
// ─────────────────────────────────────────────────────────────
// INISIALISASI TOM SELECT — manual agar instance bisa diakses
// ────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    tsJenisServis = new TomSelect('#jenisServisSelect', {
        create          : false,
        placeholder     : 'Cari jenis servis...',
        searchField     : ['text'],
    });

    tsSukuCadang = new TomSelect('#sukuCadangSelect', {
        create          : false,
        placeholder     : 'Cari suku cadang...',
        searchField     : ['text'],
    });
});
calcTotals();
</script>

@endsection
