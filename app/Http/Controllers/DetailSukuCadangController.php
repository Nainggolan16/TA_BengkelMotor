<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OrderServis;
use App\Models\SukuCadang;
use App\Models\DetailSukuCadang;

class DetailSukuCadangController extends Controller
{
    public function create($id)
    {
        $order = OrderServis::findOrFail($id);

        $sukuCadang = SukuCadang::all();

        return view(
            'detail-suku-cadang.create',
            compact(
                'order',
                'sukuCadang'
            )
        );
    }

    public function store(Request $request)
    {   
        $request->validate([

            'id_suku_cadang' => 'required',

            'jumlah' => 'required|numeric|min:1',

        ]);

        $sparepart = SukuCadang::findOrFail(
            $request->id_suku_cadang
        );

       if ($request->jumlah > $sparepart->stok)
        {
            return back()->with(
                'error',
                'Stok sparepart tidak cukup'
            );
        }

        DetailSukuCadang::create([

            'id_order' => $request->id_order,

            'id_suku_cadang' => $request->id_suku_cadang,

            'jumlah' => $request->jumlah,

            'harga_jual' => $sparepart->harga_jual,

        ]);

        // Update total order without changing stock here.
        $order = OrderServis::findOrFail(
            $request->id_order
        );

        $subtotal =
            $sparepart->harga_jual
            *
            $request->jumlah;

        $order->total_harga += $subtotal;
        $order->save();

        return redirect('/transaksi-servis');
    }
}