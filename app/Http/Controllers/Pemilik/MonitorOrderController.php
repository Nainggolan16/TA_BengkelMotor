<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\OrderServis;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonitorOrderController extends Controller
{
    /**
     * Display monitor order aktif.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'semua');
        $tanggal = $request->get('tanggal', 'hari_ini');

        // Query order dengan filter
        $query = OrderServis::with(['kendaraan.pelanggan', 'detailServis.jenisServis']);

        // Filter status
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        // Filter tanggal
        if ($tanggal === 'hari_ini') {
            $query->whereDate('tanggal_masuk', today());
        } elseif ($tanggal === 'minggu_ini') {
            $query->whereBetween('tanggal_masuk', [
                today()->startOfWeek(),
                today()->endOfWeek(),
            ]);
        } elseif ($tanggal === 'bulan_ini') {
            $query->whereMonth('tanggal_masuk', now()->month)
                ->whereYear('tanggal_masuk', now()->year);
        }

        $orders = $query->latest()->paginate(10);

        // Ringkasan untuk hari ini
        $ringkasanHariIni = [
            'menunggu' => OrderServis::whereDate('tanggal_masuk', today())
                ->where('status', 'menunggu_pemeriksaan')
                ->count(),
            'proses' => OrderServis::whereDate('tanggal_masuk', today())
                ->where('status', 'proses_pengerjaan')
                ->count(),
            'selesai' => OrderServis::whereDate('tanggal_masuk', today())
                ->where('status', 'selesai')
                ->count(),
            'dibayar' => OrderServis::whereDate('tanggal_masuk', today())
                ->where('status', 'sudah_dibayar')
                ->count(),
        ];

        $ringkasanHariIni['total'] = array_sum($ringkasanHariIni);

        return view('pemilik.monitor-order', [
            'orders' => $orders,
            'status' => $status,
            'tanggal' => $tanggal,
            'ringkasan' => $ringkasanHariIni,
        ]);
    }

    /**
     * Display order detail (read-only).
     */
    public function show($id)
    {
        $order = OrderServis::with([
            'kendaraan.pelanggan',
            'detailServis.jenisServis',
            'detailSukuCadang.sukuCadang',
            'detailJasaTambahan',
        ])->findOrFail($id);

        return view('pemilik.order-detail', [
            'order' => $order,
        ]);
    }
}
