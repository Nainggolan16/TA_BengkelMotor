<?php

namespace App\Http\Controllers;

use App\Models\OrderServis;
use App\Models\NotaPembayaran;
use Illuminate\Http\Request;

class NotaPembayaranController extends Controller
{
    public function index()
    {
        $nota = NotaPembayaran::with([
            'orderServis'
        ])->get();

        return view(
            'nota-pembayaran.index',
            compact('nota')
        );
    }

    public function create()
    {
        $orderServis = OrderServis::whereDoesntHave(
            'notaPembayaran'
        )->get();

        return view(
            'nota-pembayaran.create',
            compact('orderServis')
        );
    }

    public function store(Request $request)
    {
        $order = OrderServis::findOrFail(
            $request->id_order
        );

        NotaPembayaran::create([

            'id_order' => $request->id_order,

            'id_pengguna' => auth()->id(),

            'total' => $order->total_harga,

            'metode_bayar' => $request->metode_bayar,

            'status_bayar' => $request->status_bayar,

            'dibayar_pada' =>
                $request->status_bayar == 'lunas'
                ? now()
                : null
        ]);

        return redirect('/nota-pembayaran');
    }

    public function show($id)
    {
        $nota = NotaPembayaran::with([
            'orderServis.kendaraan.pelanggan',
            'orderServis.jenisServis'
        ])->findOrFail($id);

        return view(
            'nota-pembayaran.show',
            compact('nota')
        );
    }

    public function destroy($id)
    {
        $nota = NotaPembayaran::findOrFail($id);

        $nota->delete();

        return redirect('/nota-pembayaran');
    }

    public function print($id)
    {
        $nota = NotaPembayaran::with([

            'orderServis.kendaraan.pelanggan',

            'orderServis.jenisServis',

            'orderServis.detailSukuCadang.sukuCadang'

        ])->findOrFail($id);

        return view(
            'nota-pembayaran.print',
            compact('nota')
        );
    }
}