<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;

        $pelanggan = Pelanggan::where(
            'nama',
            'like',
            '%' . $search . '%'
        )->get();

        return view(
            'pelanggan.index',
            compact('pelanggan')
        );
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|min:3',
            'no_telepon' => 'required|string|min:5',
            'alamat' => 'nullable|string',
        ]);

        $pelanggan->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
        ]);

        return redirect('/pelanggan');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->delete();

        return redirect('/pelanggan');
    }
}
