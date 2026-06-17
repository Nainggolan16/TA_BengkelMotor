<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderServis;
use App\Models\DetailServis;
use App\Models\DetailSukuCadang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanServisController extends Controller
{
    /**
     * Display the laporan servis.
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Total order bulan ini
        $totalOrder = OrderServis::whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->count();

        // Order selesai bulan ini
        $selesai = OrderServis::whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->where('status', 'selesai')
            ->count();

        // Total pendapatan bulan ini
        $totalPendapatan = OrderServis::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->sum('total_harga');

        // Servis terlaris
        $servisTerlaris = DetailServis::with('jenisServis')
            ->selectRaw('detail_servis.id_jenis_servis, COUNT(*) as jumlah, SUM(detail_servis.harga_jasa) as pendapatan')
            ->join('order_servis', 'detail_servis.id_order', '=', 'order_servis.id')
            ->whereMonth('order_servis.tanggal_masuk', $bulan)
            ->whereYear('order_servis.tanggal_masuk', $tahun)
            ->groupBy('detail_servis.id_jenis_servis')
            ->orderByDesc('jumlah')
            ->get();

        // Spare part terbanyak dipakai
        $sparePartTerbanyak = DetailSukuCadang::with('sukuCadang')
            ->selectRaw('detail_suku_cadang.id_suku_cadang, SUM(detail_suku_cadang.jumlah) as total_qty, SUM(detail_suku_cadang.jumlah * detail_suku_cadang.harga_jual) as total_nilai')
            ->join('order_servis', 'detail_suku_cadang.id_order', '=', 'order_servis.id')
            ->whereMonth('order_servis.tanggal_masuk', $bulan)
            ->whereYear('order_servis.tanggal_masuk', $tahun)
            ->groupBy('detail_suku_cadang.id_suku_cadang')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Status distribusi
        $statusDistribusi = OrderServis::selectRaw('status, COUNT(*) as jumlah')
            ->whereMonth('tanggal_masuk', $bulan)
            ->whereYear('tanggal_masuk', $tahun)
            ->groupBy('status')
            ->get();

        return view('pemilik.laporan-servis', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'totalOrder' => $totalOrder,
            'selesai' => $selesai,
            'totalPendapatan' => $totalPendapatan,
            'servisTerlaris' => $servisTerlaris,
            'sparePartTerbanyak' => $sparePartTerbanyak,
            'statusDistribusi' => $statusDistribusi,
        ]);
    }
}
