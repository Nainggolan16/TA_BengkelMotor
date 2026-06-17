<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\OrderServis;
use App\Models\SukuCadang;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalPelanggan = Pelanggan::count();

        $totalKendaraan = Kendaraan::count();

        $totalOrder = OrderServis::count();

        $totalSparepart = SukuCadang::count();

        return view(
            'dashboard-admin',
            compact(
                'totalPelanggan',
                'totalKendaraan',
                'totalOrder',
                'totalSparepart'
            )
        );
    }


}