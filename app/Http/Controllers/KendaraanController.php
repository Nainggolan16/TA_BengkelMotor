<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $kendaraan = Kendaraan::with('pelanggan')
            ->when($search, function ($query, $search) {
                return $query->where('plat_nomor', 'like', '%' . $search . '%');
            })
            ->get();

        return view('kendaraan.index', compact('kendaraan'));
    }

    public function edit($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $pelanggan = Pelanggan::all();

        return view('kendaraan.edit', compact('kendaraan', 'pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $kendaraan = Kendaraan::findOrFail($id);

        $validated = $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id',
            'plat_nomor' => 'required|string|max:15|unique:kendaraan,plat_nomor,' . $kendaraan->id,
            'nama_kendaraan' => 'required|string|max:50',
        ]);

        $kendaraan->update($validated);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data kendaraan berhasil dihapus');
    }
}
