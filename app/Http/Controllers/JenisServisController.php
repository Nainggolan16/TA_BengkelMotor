<?php

namespace App\Http\Controllers;

use App\Models\JenisServis;
use App\Models\KategoriServis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class JenisServisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $jenisServis = JenisServis::with('kategoriServis')
            ->when($search, function ($query, $search) {
                $query->where('nama_servis', 'like', '%' . $search . '%')
                    ->orWhereHas('kategoriServis', function ($sub) use ($search) {
                        $sub->where('nama_kategori', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('id_kategori_servis')
            ->orderBy('nama_servis')
            ->get();

        return view('jenis-servis.index', compact('jenisServis'));
    }

    public function create()
    {
        KategoriServis::firstOrCreate(
            ['nama_kategori' => 'Lainnya'],
            ['keterangan' => 'Kategori untuk layanan yang tidak cocok dengan kategori lain']
        );

        if (KategoriServis::count() === 0) {
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\KategoriServisSeeder']);
        }

        $kategoriServis = KategoriServis::orderBy('id')->get();
        return view('jenis-servis.create', compact('kategoriServis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori_servis' => 'required|exists:kategori_servis,id',
            'nama_servis' => 'required|min:3',
            'harga_jasa' => 'required|numeric|min:0',
            'keterangan' => 'nullable',
        ]);

        JenisServis::create([
            'id_kategori_servis' => $request->id_kategori_servis,
            'nama_servis' => $request->nama_servis,
            'harga_jasa' => $request->harga_jasa,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/jenis-servis')
            ->with('success', 'Jenis servis berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jenisServis = JenisServis::findOrFail($id);
        $kategoriServis = KategoriServis::orderBy('nama_kategori')->get();

        return view('jenis-servis.edit', compact('jenisServis', 'kategoriServis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori_servis' => 'required|exists:kategori_servis,id',
            'nama_servis' => 'required|min:3',
            'harga_jasa' => 'required|numeric|min:0',
            'keterangan' => 'nullable',
        ]);

        $jenisServis = JenisServis::findOrFail($id);

        $jenisServis->update([
            'id_kategori_servis' => $request->id_kategori_servis,
            'nama_servis' => $request->nama_servis,
            'harga_jasa' => $request->harga_jasa,
            'keterangan' => $request->keterangan,
        ]);

        return redirect('/jenis-servis')
            ->with('success', 'Jenis servis berhasil diupdate');
    }

    public function destroy($id)
    {
        $jenisServis = JenisServis::findOrFail($id);

        $jenisServis->delete();

        return redirect('/jenis-servis')
            ->with('success', 'Jenis servis berhasil dihapus');
    }
}
