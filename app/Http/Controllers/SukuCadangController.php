<?php

namespace App\Http\Controllers;

use App\Models\SukuCadang;
use Illuminate\Http\Request;

class SukuCadangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $sukuCadang = SukuCadang::where('nama', 'like', '%' . $search . '%')
            ->orWhere('kode', 'like', '%' . $search . '%')
            ->get();

        return view('suku-cadang.index', compact('sukuCadang'));
    }

    public function create()
    {
        return view('suku-cadang.create');
    }

    public function store(Request $request)
    {   
        $request->validate([
            'kode' => 'required|unique:suku_cadang',
            'nama' => 'required|min:3',
            'stok_minimum' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);
        SukuCadang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'stok' => 0,
            'stok_minimum' => $request->stok_minimum,
            'harga_beli' => 0,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/suku-cadang')
            ->with('success', 'Suku cadang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);

        return view('suku-cadang.edit', compact('sukuCadang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:suku_cadang,kode,' . $id,
            'nama' => 'required|min:3',
            'stok_minimum' => 'required|integer|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $sukuCadang = SukuCadang::findOrFail($id);

        $sukuCadang->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'stok_minimum' => $request->stok_minimum,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/suku-cadang')
            ->with('success', 'Suku cadang berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $sukuCadang = SukuCadang::findOrFail($id);

        $sukuCadang->delete();

        return redirect('/suku-cadang')
            ->with('success', 'Suku cadang berhasil dihapus');
    }
}
