<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderServis;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanKeuanganController extends Controller
{
    /**
     * Display the laporan keuangan.
     */
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Total pendapatan bulan ini
        $totalPendapatan = OrderServis::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->sum('total_harga');

        // Total order yang dibayar bulan ini
        $totalOrder = OrderServis::whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->count();

        // Rata-rata harian
        $jumlahHari = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $rataHarian = $jumlahHari > 0 ? $totalPendapatan / $jumlahHari : 0;

        // Grafik pendapatan per hari
        $grafikHarian = OrderServis::selectRaw('DAY(tanggal_bayar) as hari, SUM(total_harga) as total')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        // Detail transaksi dengan pagination
        $detailTransaksi = OrderServis::with(['kendaraan.pelanggan'])
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->latest('tanggal_bayar')
            ->paginate(15);

        // Breakdown metode pembayaran
        $metodePembayaran = OrderServis::selectRaw('metode_pembayaran, COUNT(*) as jumlah, SUM(total_harga) as total')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->whereNotNull('tanggal_bayar')
            ->groupBy('metode_pembayaran')
            ->get();

        return view('pemilik.laporan-keuangan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'totalPendapatan' => $totalPendapatan,
            'totalOrder' => $totalOrder,
            'rataHarian' => $rataHarian,
            'grafikHarian' => $grafikHarian,
            'detailTransaksi' => $detailTransaksi,
            'metodePembayaran' => $metodePembayaran,
        ]);
    }
}
