<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderServis;
use App\Models\SukuCadang;
use App\Models\DetailServis;
use Illuminate\Support\Facades\DB;

class DashboardPemilikController extends Controller
{
    /**
     * Display the pemilik dashboard.
     */
    public function index()
    {
        // 1. Pendapatan hari ini
        $pendapatanHariIni = OrderServis::whereDate('tanggal_bayar', today())
            ->whereNotNull('tanggal_bayar')
            ->sum('total_harga');

        // 2. Pendapatan bulan ini
        $pendapatanBulanIni = OrderServis::whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->whereNotNull('tanggal_bayar')
            ->sum('total_harga');

        // 3. Order hari ini
        $orderHariIni = OrderServis::whereDate('tanggal_masuk', today())->count();

        // 4. Order sedang dikerjakan
        $orderDikerjakan = OrderServis::where('status', 'proses_pengerjaan')->count();

        // 5. Order selesai hari ini
        $orderSelesaiHariIni = OrderServis::whereDate('tanggal_masuk', today())
            ->where('status', 'selesai')->count();

        // 6. Stok kritis
        $stokKritis = SukuCadang::whereColumn('stok', '<=', 'stok_minimum')->count();

        // 7. Grafik pendapatan 7 hari
        $grafikPendapatan = OrderServis::selectRaw('DATE(tanggal_bayar) as tanggal, SUM(total_harga) as total')
            ->whereDate('tanggal_bayar', '>=', now()->subDays(6))
            ->whereNotNull('tanggal_bayar')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 8. Order aktif (menunggu / proses)
        $orderAktif = OrderServis::with(['kendaraan.pelanggan'])
            ->whereIn('status', ['menunggu_pemeriksaan', 'proses_pengerjaan'])
            ->latest()
            ->take(5)
            ->get();

        // 9. Servis terlaris bulan ini
        $servisTerlaris = DetailServis::with('jenisServis')
            ->join('order_servis', 'detail_servis.id_order', '=', 'order_servis.id')
            ->selectRaw('detail_servis.id_jenis_servis, COUNT(*) as total')
            ->whereMonth('order_servis.tanggal_masuk', now()->month)
            ->whereYear('order_servis.tanggal_masuk', now()->year)
            ->groupBy('detail_servis.id_jenis_servis')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 10. Stok hampir habis
        $stokHampirHabis = SukuCadang::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->take(5)
            ->get();

        // 11. Pelanggan teraktif bulan ini
        $pelangganTeraktif = OrderServis::with('kendaraan.pelanggan')
            ->selectRaw('id_kendaraan, COUNT(*) as total_order')
            ->whereMonth('tanggal_masuk', now()->month)
            ->groupBy('id_kendaraan')
            ->orderByDesc('total_order')
            ->take(4)
            ->get();

        return view('pemilik.dashboard', [
            'pendapatanHariIni' => $pendapatanHariIni,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'orderHariIni' => $orderHariIni,
            'orderDikerjakan' => $orderDikerjakan,
            'orderSelesaiHariIni' => $orderSelesaiHariIni,
            'stokKritis' => $stokKritis,
            'grafikPendapatan' => $grafikPendapatan,
            'orderAktif' => $orderAktif,
            'servisTerlaris' => $servisTerlaris,
            'stokHampirHabis' => $stokHampirHabis,
            'pelangganTeraktif' => $pelangganTeraktif,
        ]);
    }
}
