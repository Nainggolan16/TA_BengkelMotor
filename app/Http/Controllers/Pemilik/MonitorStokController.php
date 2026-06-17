<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\SukuCadang;
use App\Models\StokMasuk;
use Illuminate\Http\Request;

class MonitorStokController extends Controller
{
    /**
     * Display monitor stok suku cadang.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');

        // Query berdasarkan filter
        $sukuCadang = SukuCadang::query();

        if ($filter === 'kritis') {
            $sukuCadang->whereColumn('stok', '<=', 'stok_minimum');
        } elseif ($filter === 'aman') {
            $sukuCadang->whereColumn('stok', '>', 'stok_minimum');
        }

        $sukuCadang = $sukuCadang->orderBy('nama')->get();

        // Ringkasan
        $semua = SukuCadang::all();
        $stokKritis = SukuCadang::whereColumn('stok', '<=', 'stok_minimum')->count();
        $perluRestock = SukuCadang::where('stok', 0)->count();
        $totalNilai = SukuCadang::selectRaw('SUM(stok * harga_jual) as total')->value('total') ?? 0;

        $ringkasan = [
            'total_item' => $semua->count(),
            'stok_kritis' => $stokKritis,
            'total_nilai' => $totalNilai,
            'perlu_restock' => $perluRestock,
        ];

        // Riwayat stok masuk
        $riwayatStokMasuk = StokMasuk::with('sukuCadang')
            ->latest('tanggal')
            ->take(10)
            ->get();

        return view('pemilik.monitor-stok', [
            'filter' => $filter,
            'sukuCadang' => $sukuCadang,
            'ringkasan' => $ringkasan,
            'riwayatStokMasuk' => $riwayatStokMasuk,
        ]);
    }
}
