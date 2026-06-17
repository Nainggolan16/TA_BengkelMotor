<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\JenisServis;
use App\Models\OrderServis;
use App\Models\Pelanggan;
use App\Models\SukuCadang;
use App\Models\DetailServis;
use App\Models\DetailSukuCadang;
use App\Models\DetailJasaTambahan;
use App\Services\FifoInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransaksiServisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $transactions = OrderServis::with(['kendaraan.pelanggan'])
            ->when($search, function ($query, $search) {
                $query->where('kode_order', 'like', "%{$search}%")
                    ->orWhereHas('kendaraan', function ($sub) use ($search) {
                        $sub->where('plat_nomor', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kendaraan.pelanggan', function ($sub) use ($search) {
                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhere('no_telepon', 'like', "%{$search}%");
                    });
            })
            ->orderByDesc('tanggal_masuk')
            ->get();
        
        return view('transaksi-servis.index', compact('transactions'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::select(['id', 'nama', 'no_telepon'])->get();
        $kendaraan = Kendaraan::with('pelanggan')->select(['id', 'id_pelanggan', 'plat_nomor', 'merk', 'tipe'])->get();
        $jenisServis = JenisServis::with('kategoriServis')
            ->select(['id', 'id_kategori_servis', 'nama_servis', 'harga_jasa'])
            ->orderBy('id_kategori_servis')
            ->orderBy('nama_servis')
            ->get();
        $sukuCadang = SukuCadang::select(['id', 'kode', 'nama', 'stok', 'harga_jual'])->get();

        // Group jenis servis by kategori
        $jenisServisGrouped = $jenisServis->groupBy(function ($item) {
            return $item->kategoriServis ? $item->kategoriServis->nama_kategori : 'Tanpa Kategori';
        });

        return view(
            'transaksi-servis.create',
            compact('pelanggan', 'kendaraan', 'jenisServis', 'jenisServisGrouped', 'sukuCadang')
        );
    }

    public function store(Request $request)
    {
        Log::info('STEP 1 - Masuk store');

        // Basic validation
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'pelanggan_nama' => 'nullable|string|min:3',
            'pelanggan_no_telepon' => 'nullable|string|min:5',
            'kendaraan_id' => 'nullable|exists:kendaraan,id',
            'plat_nomor' => 'nullable|string|max:15',
            'nama_kendaraan' => 'nullable|string|max:50',
            'suku_cadang' => 'nullable|array',
            'suku_cadang.*.id' => 'exists:suku_cadang,id',
            'suku_cadang.*.qty' => 'nullable|integer|min:1',
            'jasa_tambahan' => 'nullable|array',
            'jasa_tambahan.*.nama' => 'nullable|string|min:3',
            'jasa_tambahan.*.biaya' => 'nullable|numeric|min:0',
            'keluhan' => 'nullable|string',
            'status' => 'nullable|in:menunggu_pemeriksaan,proses_pengerjaan,pembelian_barang',
            'biaya_jasa_tambahan' => 'nullable|numeric|min:0',
        ]);

        if (!$request->filled('pelanggan_id')) {
            $request->validate([
                'pelanggan_nama' => 'required|string|min:3',
                'pelanggan_no_telepon' => 'required|string|min:5',
            ]);
        }

        if (!$request->filled('kendaraan_id')) {
            $request->validate([
                'plat_nomor' => 'required|string|max:15',
                'nama_kendaraan' => 'required|string|max:50',
            ]);
        }

        // Kita buat Jenis servis dan suku cadang bisa tidak diisi
        $status = $request->input('status', 'menunggu_pemeriksaan');
        $request->validate([
        'jenis_servis' => 'nullable|array',
        'jenis_servis.*' => 'exists:jenis_servis,id',
        ]);

        Log::info('STEP 2 - Setelah validasi');

        DB::beginTransaction();

        try {
            Log::info('TransaksiServis store: before creating pelanggan', [
                'pelanggan_id' => $request->pelanggan_id,
                'pelanggan_nama' => $request->pelanggan_nama,
                'pelanggan_no_telepon' => $request->pelanggan_no_telepon,
            ]);

            if (!$request->pelanggan_id) {
                $pelanggan = Pelanggan::create([
                    'nama' => $request->pelanggan_nama,
                    'no_telepon' => $request->pelanggan_no_telepon,
                    'alamat' => null,
                ]);
            } else {
                $pelanggan = Pelanggan::findOrFail($request->pelanggan_id);
            }

            Log::info('TransaksiServis store: after creating pelanggan', [
                'pelanggan_id' => $pelanggan->id,
                'pelanggan_nama' => $pelanggan->nama,
            ]);

            Log::info('STEP 3 - Setelah pelanggan');

            Log::info('TransaksiServis store: before creating kendaraan', [
                'kendaraan_id' => $request->kendaraan_id,
                'plat_nomor' => $request->plat_nomor,
                'nama_kendaraan' => $request->nama_kendaraan,
            ]);

            if (!$request->kendaraan_id) {
                $kendaraan = Kendaraan::create([
                    'id_pelanggan' => $pelanggan->id,
                    'plat_nomor' => $request->plat_nomor,
                    'nama_kendaraan' => $request->nama_kendaraan,
                    'tahun' => now()->year,
                    'warna' => null,
                ]);
            } else {
                $kendaraan = Kendaraan::findOrFail($request->kendaraan_id);
            }

            Log::info('TransaksiServis store: after creating kendaraan', [
                'kendaraan_id' => $kendaraan->id,
                'plat_nomor' => $kendaraan->plat_nomor,
                'nama_kendaraan' => $kendaraan->nama_kendaraan,
            ]);

            Log::info('STEP 4 - Setelah kendaraan');

            $selectedJenisServis = collect();
            if ($request->filled('jenis_servis') && is_array($request->jenis_servis)) {
                $selectedJenisServis = JenisServis::whereIn('id', $request->jenis_servis)->get();
            }

            $detailJasaTambahanInput = collect($request->input('jasa_tambahan', []))->filter(function ($item) {
                return isset($item['nama']) && trim($item['nama']) !== '' && isset($item['biaya']);
            })->map(function ($item) {
                return [
                    'nama_jasa' => trim($item['nama']),
                    'biaya' => (float) $item['biaya'],
                ];
            });

            $biayaJasaTambahan = $detailJasaTambahanInput->sum('biaya');
            if ($biayaJasaTambahan <= 0 && $request->filled('biaya_jasa_tambahan')) {
                $biayaJasaTambahan = (float) $request->input('biaya_jasa_tambahan', 0);
            }

            $serviceTotal = $selectedJenisServis->sum('harga_jasa') + $biayaJasaTambahan;
            $primaryJenisServis = $selectedJenisServis->first();

            Log::info('SERVICE TOTAL', [
                'selected_jenis_servis_ids' => $selectedJenisServis->pluck('id')->toArray(),
                'biaya_jasa' => $selectedJenisServis->sum('harga_jasa'),
                'biaya_jasa_tambahan' => $biayaJasaTambahan,
                'service_total' => $serviceTotal,
            ]);

            Log::info('STEP 5 - Sebelum create order');
            Log::info('TransaksiServis store: before creating order servis', [
                'id_kendaraan' => $kendaraan->id,
                'id_jenis_servis' => $primaryJenisServis?->id,
                'biaya_jasa' => $serviceTotal,
            ]);

            $lastId = (OrderServis::max('id') ?? 0) + 1;

            $kodeOrder = 'TRX-' . str_pad($lastId, 5, '0', STR_PAD_LEFT);

            $transaction = OrderServis::create([
                'kode_order' => $kodeOrder,
                'id_kendaraan' => $kendaraan->id,
                'id_jenis_servis' => $primaryJenisServis ? $primaryJenisServis->id : null,
                'id_pengguna' => auth()->id() ?? 1,
                'keluhan' => $request->keluhan,
                'catatan_servis' => null,
                'status' => $status,
                'biaya_jasa' => $selectedJenisServis->sum('harga_jasa'),
                'biaya_jasa_tambahan' => $biayaJasaTambahan,
                'total_harga' => $serviceTotal,
                'tanggal_masuk' => now(),
                'tanggal_selesai' => null,
            ]);

            Log::info('TransaksiServis store: after creating order servis', [
                'order_id' => $transaction->id,
                'kode_order' => $transaction->kode_order,
            ]);

            Log::info('STEP 6 - Setelah create order');
            Log::info('TransaksiServis store: before creating detail servis', [
                'order_id' => $transaction->id,
                'jenis_count' => $selectedJenisServis->count(),
            ]);

            if ($selectedJenisServis->isNotEmpty()) {
                foreach ($selectedJenisServis as $jenis) {
                    $transaction->detailServis()->create([
                        'id_order' => $transaction->id,
                        'id_jenis_servis' => $jenis->id,
                        'harga_jasa' => $jenis->harga_jasa,
                    ]);
                }
            }

            if ($detailJasaTambahanInput->isNotEmpty()) {
                foreach ($detailJasaTambahanInput as $detail) {
                    $transaction->jasaTambahan()->create([
                        'id_order' => $transaction->id,
                        'nama_jasa' => $detail['nama_jasa'],
                        'biaya' => $detail['biaya'],
                    ]);
                }
            }

            Log::info('TransaksiServis store: after creating detail servis', [
                'order_id' => $transaction->id,
                'detail_servis_count' => $transaction->detailServis()->count(),
            ]);

            Log::info('STEP 7 - Setelah detail servis');

            $spareTotal = 0;
            Log::info('TransaksiServis store: before creating detail suku cadang', [
                'order_id' => $transaction->id,
                'suku_cadang' => $request->suku_cadang,
            ]);

            if ($request->filled('suku_cadang') && is_array($request->suku_cadang)) {
                foreach ($request->suku_cadang as $entry) {
                    if (empty($entry['id']) || !isset($entry['qty'])) {
                        continue;
                    }

                    $spare = SukuCadang::find($entry['id']);
                    if (!$spare) {
                        continue;
                    }

                    $qty = isset($entry['qty']) ? (int)$entry['qty'] : 1;

                    if ($qty < 1) {
                        $qty = 1;
                    }

                    if ($qty > $spare->stok) {
                        DB::rollBack();

                        return back()
                            ->withInput()
                            ->withErrors([
                                'stok' => "Stok {$spare->nama} hanya tersedia {$spare->stok} pcs."
                            ]);
                    }

                    $transaction->detailSukuCadang()->create([
                        'id_order' => $transaction->id,
                        'id_suku_cadang' => $spare->id,
                        'jumlah' => $qty,
                        'harga_jual' => $spare->harga_jual,
                    ]);

                    $spareTotal += $spare->harga_jual * $qty;
                }
            }

            Log::info('TransaksiServis store: after creating detail suku cadang', [
                'order_id' => $transaction->id,
                'detail_suku_cadang_count' => $transaction->detailSukuCadang()->count(),
                'spare_total' => $spareTotal,
            ]);

            Log::info('SPARE TOTAL', ['spare_total' => $spareTotal]);
            Log::info('GRAND TOTAL', ['grand_total' => $serviceTotal + $spareTotal]);

            Log::info('STEP 8 - Setelah detail suku cadang');

            $transaction->update([
                'total_harga' => $serviceTotal + $spareTotal,
            ]);

            DB::commit();
            Log::info('STEP 9 - Commit berhasil');

           if ($transaction->status === 'pembelian_barang') {

            return redirect(
                route('transaksi-servis.show', $transaction->id)
            )->with(
                'success',
                'Transaksi pembelian barang berhasil dibuat.'
            );
        }

        return redirect('/transaksi-servis')
            ->with(
                'success',
                'Transaksi servis berhasil disimpan dan siap diproses.'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('TRANSAKSI GAGAL');
            Log::error($e->getMessage());
            Log::error($e->getFile());
            Log::error($e->getLine());
            throw $e;
        }
    }

    public function show($id)
    {
        $transaction = OrderServis::with([
            'kendaraan.pelanggan',
            'detailServis.jenisServis',
            'jasaTambahan',
            'detailSukuCadang.sukuCadang'
        ])->findOrFail($id);

        $jenisServis = JenisServis::with('kategoriServis')
            ->select(['id', 'id_kategori_servis', 'nama_servis', 'harga_jasa'])
            ->orderBy('id_kategori_servis')
            ->orderBy('nama_servis')
            ->get();

        $sukuCadang = SukuCadang::select(['id', 'kode', 'nama', 'stok', 'harga_jual'])
            ->where('stok', '>', 0)
            ->get();

        return view('transaksi-servis.show', compact('transaction', 'jenisServis', 'sukuCadang'));
    }

    public function edit($id)
    {
        $transaction = OrderServis::with(['kendaraan.pelanggan', 'detailServis', 'detailSukuCadang', 'jasaTambahan'])->findOrFail($id);
        $jenisServis = JenisServis::with('kategoriServis')
            ->select(['id', 'id_kategori_servis', 'nama_servis', 'harga_jasa'])
            ->orderBy('id_kategori_servis')
            ->orderBy('nama_servis')
            ->get();
        $sukuCadang = SukuCadang::select(['id', 'kode', 'nama', 'stok', 'harga_jual'])->get();

        // Group jenis servis by kategori
        $jenisServisGrouped = $jenisServis->groupBy(function ($item) {
            return $item->kategoriServis ? $item->kategoriServis->nama_kategori : 'Tanpa Kategori';
        });

        return view('transaksi-servis.edit', compact('transaction', 'jenisServis', 'jenisServisGrouped', 'sukuCadang'));
    }

    public function update(Request $request, $id)
    {
        $transaction = OrderServis::with('detailSukuCadang.sukuCadang')->findOrFail($id);
        $action = $request->input('action');

        // Handle full edit/save_changes
        if ($request->has('save_changes')) {
            $request->validate([
                'status' => 'required|in:menunggu_pemeriksaan,proses_pengerjaan,selesai,sudah_dibayar',
                'jenis_servis' => 'nullable|array',
                'jenis_servis.*' => 'exists:jenis_servis,id',
                'jasa_tambahan' => 'nullable|array',
                'jasa_tambahan.*.nama' => 'required_with:jasa_tambahan|string|min:3',
                'jasa_tambahan.*.biaya' => 'required_with:jasa_tambahan|numeric|min:0',
                'suku_cadang' => 'nullable|array',
                'suku_cadang.*.id' => 'exists:suku_cadang,id',
                'suku_cadang.*.qty' => 'nullable|integer|min:1',
                'biaya_jasa_tambahan' => 'nullable|numeric|min:0',
                'catatan_servis' => 'nullable|string',
            ]);

            DB::beginTransaction();
            try {
                // update catatan and biaya tambahan and status
                $transaction->update([
                    'catatan_servis' => $request->input('catatan_servis'),
                    'biaya_jasa_tambahan' => $request->input('biaya_jasa_tambahan', 0),
                    'status' => $request->input('status'),
                ]);

                // Sync jenis servis
                $transaction->detailServis()->delete();
                $serviceTotal = 0;
                if ($request->filled('jenis_servis')) {
                    $selected = JenisServis::whereIn('id', $request->jenis_servis)->get();
                    foreach ($selected as $jenis) {
                        $transaction->detailServis()->create([
                            'id_order' => $transaction->id,
                            'id_jenis_servis' => $jenis->id,
                            'harga_jasa' => $jenis->harga_jasa,
                        ]);
                        $serviceTotal += $jenis->harga_jasa;
                    }
                    $transaction->update(['biaya_jasa' => $serviceTotal]);
                }

                // Sync suku cadang
                $transaction->detailSukuCadang()->delete();
                $spareTotal = 0;
                if ($request->filled('suku_cadang') && is_array($request->suku_cadang)) {
                    foreach ($request->suku_cadang as $entry) {
                        if (empty($entry['id']) || !isset($entry['qty'])) continue;
                        $spare = SukuCadang::find($entry['id']);
                        if (!$spare) continue;
                        $qty = isset($entry['qty']) ? (int)$entry['qty'] : 1;
                        $transaction->detailSukuCadang()->create([
                            'id_order' => $transaction->id,
                            'id_suku_cadang' => $spare->id,
                            'jumlah' => $qty,
                            'harga_jual' => $spare->harga_jual,
                        ]);
                        $spareTotal += $spare->harga_jual * $qty;
                    }
                }

                // Recalculate totals
                $biayaTambahan = (float) $request->input('biaya_jasa_tambahan', 0);
                if ($request->filled('jasa_tambahan')) {
                    $detailJasaTambahanInput = collect($request->input('jasa_tambahan', []))->filter(function ($item) {
                        return isset($item['nama']) && trim($item['nama']) !== '' && isset($item['biaya']);
                    })->map(function ($item) {
                        return [
                            'nama_jasa' => trim($item['nama']),
                            'biaya' => (float) $item['biaya'],
                        ];
                    });

                    $biayaTambahan = $detailJasaTambahanInput->sum('biaya');
                }

                $transaction->jasaTambahan()->delete();
                if (isset($detailJasaTambahanInput) && $detailJasaTambahanInput->isNotEmpty()) {
                    foreach ($detailJasaTambahanInput as $detail) {
                        $transaction->jasaTambahan()->create([
                            'id_order' => $transaction->id,
                            'nama_jasa' => $detail['nama_jasa'],
                            'biaya' => $detail['biaya'],
                        ]);
                    }
                }
                $transaction->update([
                    'biaya_jasa_tambahan' => $biayaTambahan,
                    'total_harga' => ($transaction->biaya_jasa + $biayaTambahan) + $spareTotal,
                ]);

                // If status changed to sudah_dibayar, record payment date and decrement stock using FIFO
                if ($request->input('status') === 'sudah_dibayar') {
                    $transaction->update(['tanggal_bayar' => now()]);
                    
                    // Prepare details for FIFO processing
                    $details = $transaction->detailSukuCadang->map(function ($detail) {
                        return [
                            'id_suku_cadang' => $detail->id_suku_cadang,
                            'jumlah' => $detail->jumlah
                        ];
                    })->toArray();

                    if (!empty($details)) {
                        try {
                            $fifoService = new FifoInventoryService();
                            $fifoResult = $fifoService->processFifo($transaction->id, $details);
                            Log::info('FIFO Processing Success', $fifoResult);
                        } catch (\Exception $e) {
                            throw new \Exception('Gagal memproses stok FIFO: ' . $e->getMessage());
                        }
                    }
                }

                DB::commit();
                return redirect()->route('transaksi-servis.show', $transaction->id)->with('success', 'Perubahan transaksi disimpan.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Gagal menyimpan perubahan transaksi: ' . $e->getMessage());
                return redirect()->back()->withErrors('Gagal menyimpan perubahan: ' . $e->getMessage());
            }
        }

        if ($action === 'mulai' && $transaction->status === 'menunggu_pemeriksaan') {
            $transaction->update(['status' => 'proses_pengerjaan']);
        }

        if ($action === 'selesai' && $transaction->status === 'proses_pengerjaan') {
            $transaction->update([
                'status' => 'selesai',
                'tanggal_selesai' => now(),
            ]);
        }

        if (in_array( $transaction->status,['selesai', 'pembelian_barang'])) 
            {
            $request->validate([
                'metode_pembayaran' => 'required|in:Cash,Transfer,QRIS',
            ]);

            DB::beginTransaction();
            try {
                $transaction->update([
                    'status' => 'sudah_dibayar',
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'tanggal_bayar' => now(),
                ]);

                // Process stok using FIFO
                $details = $transaction->detailSukuCadang->map(function ($detail) {
                    return [
                        'id_suku_cadang' => $detail->id_suku_cadang,
                        'jumlah' => $detail->jumlah
                    ];
                })->toArray();

                if (!empty($details)) {
                    $fifoService = new FifoInventoryService();
                    $fifoResult = $fifoService->processFifo($transaction->id, $details);
                    Log::info('FIFO Processing Success on Payment', $fifoResult);
                }

                DB::commit();
                return redirect()->back()->with('success', 'Pembayaran berhasil dicatat dan stok FIFO diproses.');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Gagal memproses pembayaran: ' . $e->getMessage());
                return redirect()->back()->withErrors('Gagal memproses pembayaran: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function nota($id)
    {
        $transaction = OrderServis::with([
            'kendaraan.pelanggan',
            'detailServis.jenisServis',
            'detailSukuCadang.sukuCadang'
        ])->findOrFail($id);

        return view('transaksi-servis.print', compact('transaction'));
    }

    /**
     * Tambah Jenis Servis ke transaksi
     */
    public function addServis(Request $request, $id)
    {
        $transaction = OrderServis::findOrFail($id);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menambah servis pada status ini.');
        }

        $request->validate([
            'id_jenis_servis' => 'required|exists:jenis_servis,id',
        ]);

        $jenisServis = JenisServis::find($request->id_jenis_servis);

        $transaction->detailServis()->create([
            'id_order' => $transaction->id,
            'id_jenis_servis' => $jenisServis->id,
            'harga_jasa' => $jenisServis->harga_jasa,
        ]);

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Servis berhasil ditambahkan.');
    }

    /**
     * Hapus Jenis Servis dari transaksi
     */
    public function removeServis($orderId, $detailServisId)
    {
        $transaction = OrderServis::findOrFail($orderId);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menghapus servis pada status ini.');
        }

        $detailServis = DetailServis::where('id', $detailServisId)
            ->where('id_order', $orderId)
            ->firstOrFail();

        $detailServis->delete();

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Servis berhasil dihapus.');
    }

    /**
     * Tambah Sparepart ke transaksi
     */
    public function addSparepart(Request $request, $id)
    {
        $transaction = OrderServis::findOrFail($id);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menambah sparepart pada status ini.');
        }

        $request->validate([
            'id_suku_cadang' => 'required|exists:suku_cadang,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cek jika sparepart sudah ada
        $existing = $transaction->detailSukuCadang()
            ->where('id_suku_cadang', $request->id_suku_cadang)
            ->first();

        if ($existing) {
            return redirect()->back()->withErrors('Sparepart ini sudah ada dalam transaksi.');
        }

        $sukuCadang = SukuCadang::find($request->id_suku_cadang);

        $transaction->detailSukuCadang()->create([
            'id_order' => $transaction->id,
            'id_suku_cadang' => $sukuCadang->id,
            'jumlah' => $request->jumlah,
            'harga_jual' => $sukuCadang->harga_jual,
        ]);

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Sparepart berhasil ditambahkan.');
    }

    /**
     * Hapus Sparepart dari transaksi
     */
    public function removeSparepart($orderId, $detailSukuCadangId)
    {
        $transaction = OrderServis::findOrFail($orderId);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menghapus sparepart pada status ini.');
        }

        $detailSukuCadang = DetailSukuCadang::where('id', $detailSukuCadangId)
            ->where('id_order', $orderId)
            ->firstOrFail();

        $detailSukuCadang->delete();

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Sparepart berhasil dihapus.');
    }

    /**
     * Tambah Jasa Tambahan ke transaksi
     */
    public function addJasaTambahan(Request $request, $id)
    {
        $transaction = OrderServis::findOrFail($id);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menambah jasa tambahan pada status ini.');
        }

        $request->validate([
            'nama_jasa' => 'required|string|min:3',
            'biaya' => 'required|numeric|min:0',
        ]);

        $transaction->jasaTambahan()->create([
            'id_order' => $transaction->id,
            'nama_jasa' => $request->nama_jasa,
            'biaya' => $request->biaya,
        ]);

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Jasa tambahan berhasil ditambahkan.');
    }

    /**
     * Hapus Jasa Tambahan dari transaksi
     */
    public function removeJasaTambahan($orderId, $detailId)
    {
        $transaction = OrderServis::findOrFail($orderId);

        if (!in_array($transaction->status, ['menunggu_pemeriksaan', 'proses_pengerjaan'])) {
            return redirect()->back()->withErrors('Tidak dapat menghapus jasa tambahan pada status ini.');
        }

        $jasaTambahan = DetailJasaTambahan::where('id', $detailId)
            ->where('id_order', $orderId)
            ->firstOrFail();

        $jasaTambahan->delete();

        // Recalculate total_harga
        $this->recalculateTotalHarga($transaction);

        return redirect()->back()->with('success', 'Jasa tambahan berhasil dihapus.');
    }

    /**
     * Helper: Recalculate total harga transaksi
     */
    private function recalculateTotalHarga(OrderServis $transaction)
    {
        // Ambil total dari semua servis
        $serviceTotal = $transaction->detailServis()->sum('harga_jasa');

        // Ambil total dari semua jasa tambahan
        $jasaTambahanTotal = $transaction->jasaTambahan()->sum('biaya');

        // Ambil total dari semua sparepart
        $spareTotal = $transaction->detailSukuCadang()
            ->select(\DB::raw('SUM(harga_jual * jumlah) as total'))
            ->value('total') ?? 0;

        // Update transaction
        $totalHarga = $serviceTotal + $jasaTambahanTotal + $spareTotal;

        $transaction->update([
            'biaya_jasa' => $serviceTotal,
            'biaya_jasa_tambahan' => $jasaTambahanTotal,
            'total_harga' => $totalHarga,
        ]);

        \Log::info('Recalculate Total Harga', [
            'order_id' => $transaction->id,
            'service_total' => $serviceTotal,
            'jasa_tambahan_total' => $jasaTambahanTotal,
            'spare_total' => $spareTotal,
            'grand_total' => $totalHarga,
        ]);
    }
}
