<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\SukuCadang;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $stokMasuk = StokMasuk::with('sukuCadang', 'pengguna')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('sukuCadang', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('kode', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('stok-masuk.index', compact('stokMasuk'));
    }

    public function create()
    {
        $sukuCadang = SukuCadang::orderBy('nama')->get();

        return view('stok-masuk.create', compact('sukuCadang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_suku_cadang' => 'required|exists:suku_cadang,id',
            'jumlah_masuk' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string|max:200',
        ]);

        $sukuCadang = SukuCadang::findOrFail($request->id_suku_cadang);

        // Buat record stok masuk dengan sisa_stok = jumlah masuk (batch baru)
        $stokMasuk = StokMasuk::create([
            'id_suku_cadang' => $request->id_suku_cadang,
            'id_pengguna' => auth()->id(),
            'jumlah' => $request->jumlah_masuk,
            'sisa_stok' => $request->jumlah_masuk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'tanggal' => $request->tanggal_masuk,
            'catatan' => $request->keterangan,
        ]);

        // Update stok SukuCadang
        $sukuCadang->update([
            'stok' => $sukuCadang->stok + $request->jumlah_masuk,
            // Model B (Harga baru dari stok baru kita buat jadi harga master seluruh stok)
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/stok-masuk')
            ->with('success', 'Stok masuk berhasil ditambahkan. Stok ' . $sukuCadang->nama . ' updated menjadi ' . $sukuCadang->stok);
    }

    public function show($id)
    {
        $stokMasuk = StokMasuk::with('sukuCadang', 'pengguna')->findOrFail($id);

        return view('stok-masuk.show', compact('stokMasuk'));
    }
}
