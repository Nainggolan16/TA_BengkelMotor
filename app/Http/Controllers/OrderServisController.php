<?php

namespace App\Http\Controllers;

use App\Models\OrderServis;
use App\Models\Kendaraan;
use App\Models\JenisServis;
use Illuminate\Http\Request;

class OrderServisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $order = OrderServis::with([
            'kendaraan',
            'jenisServis'
        ])

        ->where(
            'kode_order',
            'like',
            '%' . $search . '%'
        )

        ->get();

        return view(
            'order-servis.index',
            compact('order')
        );
    }

    public function create()
    {
        $kendaraan = Kendaraan::with('pelanggan')->get();

        $jenisServis = JenisServis::all();

        return view('order-servis.create', compact(
            'kendaraan',
            'jenisServis'
        ));
    }

    public function store(Request $request)
    {   
        $request->validate([

        'id_kendaraan' => 'required',

        'id_jenis_servis' => 'required',

        'keluhan' => 'required|min:5',

        ]);

        $jenisServis = JenisServis::findOrFail(
            $request->id_jenis_servis
        );

        OrderServis::create([

            'kode_order' => 'ORD-' . time(),

            'id_kendaraan' => $request->id_kendaraan,

            'id_jenis_servis' => $request->id_jenis_servis,

            'id_pengguna' => 1,

            'keluhan' => $request->keluhan,

            'catatan_servis' => null,

            'status' => $request->status,

            'biaya_jasa' => $jenisServis->harga_jasa,

            'total_harga' => $jenisServis->harga_jasa,

            'tanggal_masuk' => now(),

            'tanggal_selesai' =>
                $request->status == 'selesai'
                ? now()
                : null
        ]);

             return redirect('/order-servis')
                ->with(
                    'success',
                    'Order servis berhasil ditambahkan'
                );
    }

    public function edit($id)
    {
        $order = OrderServis::findOrFail($id);

        $kendaraan = Kendaraan::with('pelanggan')->get();

        $jenisServis = JenisServis::all();

        return view('order-servis.edit', compact(
            'order',
            'kendaraan',
            'jenisServis'
        ));
    }

    public function update(Request $request, $id)
    {
        $jenisServis = JenisServis::findOrFail(
            $request->id_jenis_servis
        );

        $order = OrderServis::findOrFail($id);

        $order->update([

            'id_kendaraan' => $request->id_kendaraan,

            'id_jenis_servis' => $request->id_jenis_servis,

            'keluhan' => $request->keluhan,

            'status' => $request->status,

            'biaya_jasa' => $jenisServis->harga_jasa,

            'total_harga' => $jenisServis->harga_jasa,

            'tanggal_selesai' =>
                $request->status == 'selesai'
                ? now()
                : null
        ]);

        return redirect('/order-servis')
            ->with(
                'success',
                'Order servis berhasil diupdate'
            );
    }

    public function destroy($id)
    {
        $order = OrderServis::findOrFail($id);

        $order->delete();

        return redirect('/order-servis')
        ->with(
            'success',
            'Order servis berhasil dihapus'
        );
    }
}