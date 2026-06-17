<?php

namespace App\Http\Controllers;

use App\Models\KategoriServis;
use Illuminate\Http\Request;

class KategoriServisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kategoriServis = KategoriServis::where('nama_kategori', 'like', '%' . $search . '%')
            ->withCount('jenisServis')
            ->orderBy('nama_kategori')
            ->get();

        return view('kategori-servis.index', compact('kategoriServis'));
    }

    public function create()
    {
        return view('kategori-servis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|min:3|max:100',
            'keterangan' => 'nullable|string',
        ]);

        KategoriServis::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/kategori-servis')
            ->with('success', 'Kategori servis berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriServis = KategoriServis::findOrFail($id);

        return view('kategori-servis.edit', compact('kategoriServis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|min:3|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $kategoriServis = KategoriServis::findOrFail($id);

        $kategoriServis->update([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/kategori-servis')
            ->with('success', 'Kategori servis berhasil diupdate');
    }

    public function destroy($id)
    {
        $kategoriServis = KategoriServis::findOrFail($id);

        // Cegah delete jika ada jenis servis terkait
        if ($kategoriServis->jenisServis()->count() > 0) {
            return redirect('/kategori-servis')
                ->withErrors('Tidak bisa menghapus kategori yang masih memiliki jenis servis. Hapus jenis servis terlebih dahulu.');
        }

        $kategoriServis->delete();

        return redirect('/kategori-servis')
            ->with('success', 'Kategori servis berhasil dihapus');
    }
}
