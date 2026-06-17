<?php

namespace App\Services;

use App\Models\StokMasuk;
use App\Models\SukuCadang;
use App\Models\FifoLog;
use Illuminate\Support\Facades\Log;
use Exception;

class FifoInventoryService
{
    /**
     * Proses pengurangan stok menggunakan FIFO (First In First Out)
     * 
     * @param int $idOrder - ID dari OrderServis
     * @param array $details - Array dari detail sparepart transaksi
     *                        [
     *                          ['id_suku_cadang' => 1, 'jumlah' => 5],
     *                          ['id_suku_cadang' => 2, 'jumlah' => 3]
     *                        ]
     * @return array - ['success' => true/false, 'message' => string, 'logs' => array]
     * @throws Exception - Jika stok tidak cukup
     */
    public function processFifo($idOrder, $details)
    {
        Log::info('=== FIFO Process Start ===', ['order_id' => $idOrder]);

        $logs = [];

        try {
            // Validasi stok terlebih dahulu
            $this->validateStockSufficiency($details);

            // Proses setiap detail sparepart
            foreach ($details as $detail) {
                $idSukuCadang = $detail['id_suku_cadang'];
                $jumlahDibutuhkan = $detail['jumlah'];

                $logs[] = $this->processSparepart(
                    $idOrder,
                    $idSukuCadang,
                    $jumlahDibutuhkan
                );
            }

            Log::info('=== FIFO Process Success ===', ['order_id' => $idOrder, 'logs_count' => count($logs)]);

            return [
                'success' => true,
                'message' => 'Stok berhasil dikurangi dengan metode FIFO',
                'logs' => $logs
            ];
        } catch (Exception $e) {
            Log::error('=== FIFO Process Failed ===', [
                'order_id' => $idOrder,
                'error' => $e->getMessage()
            ]);

            throw new Exception('Gagal memproses FIFO: ' . $e->getMessage());
        }
    }

    /**
     * Validasi apakah stok cukup untuk semua detail sparepart
     */
    private function validateStockSufficiency($details)
    {
        foreach ($details as $detail) {
            $sukuCadang = SukuCadang::findOrFail($detail['id_suku_cadang']);
            $jumlahDibutuhkan = $detail['jumlah'];

            if ($sukuCadang->stok < $jumlahDibutuhkan) {
                throw new Exception(
                    "Stok {$sukuCadang->nama} tidak cukup. " .
                    "Dibutuhkan: {$jumlahDibutuhkan} unit, " .
                    "Stok tersedia: {$sukuCadang->stok} unit"
                );
            }
        }
    }

    /**
     * Proses pengurangan stok untuk 1 jenis sparepart menggunakan FIFO
     */
    private function processSparepart($idOrder, $idSukuCadang, $jumlahDibutuhkan)
    {
        $sukuCadang = SukuCadang::findOrFail($idSukuCadang);

        // Ambil batch-batch stok yang masih tersedia (sisa_stok > 0), urut dari yang paling tua
        $batches = StokMasuk::where('id_suku_cadang', $idSukuCadang)
            ->where('sisa_stok', '>', 0)
            ->orderBy('tanggal', 'asc') // FIFO: ambil yang paling tua
            ->orderBy('id', 'asc')       // Jika tanggal sama, ambil ID terkecil
            ->get();

        if ($batches->isEmpty()) {
            throw new Exception("Tidak ada batch stok untuk {$sukuCadang->nama}");
        }

        $sisaDibutuhkan = $jumlahDibutuhkan;
        $logDetails = [];

        foreach ($batches as $batch) {
            if ($sisaDibutuhkan <= 0) {
                break;
            }

            $ambil = min($sisaDibutuhkan, $batch->sisa_stok);

            $sisaBatchBaru = $batch->sisa_stok - $ambil;

            $batch->update([
                'sisa_stok' => $sisaBatchBaru
            ]);

            $fifoLog = FifoLog::create([
                'id_order' => $idOrder,
                'id_suku_cadang' => $idSukuCadang,
                'id_batch_stok' => $batch->id,
                'qty_dipakai' => $ambil,
                'sisa_batch' => $sisaBatchBaru,
                'harga_beli' => $batch->harga_beli,
                'keterangan' => "Batch dari {$batch->tanggal}, Harga: Rp " . number_format($batch->harga_beli, 0)
            ]);

            $logDetails[] = [
                'batch_id' => $batch->id,
                'tanggal_masuk' => $batch->tanggal,
                'qty_dipakai' => $ambil,
                'sisa_batch' => $sisaBatchBaru,
                'log_id' => $fifoLog->id
            ];

            Log::info('FIFO Detail', [
                'suku_cadang' => $sukuCadang->nama,
                'batch_id' => $batch->id,
                'batch_tanggal' => $batch->tanggal,
                'qty_dipakai' => $ambil,
                'sisa_batch_setelah' => $sisaBatchBaru
            ]);

            $sisaDibutuhkan -= $ambil;
        }
        
        if ($sisaDibutuhkan > 0) {
            throw new Exception(
                "FIFO gagal. Masih kurang {$sisaDibutuhkan} unit untuk {$sukuCadang->nama}"
            );
        }
        // Update stok total di suku_cadang (hanya kurangi satu kali untuk sparepart ini)
        $sukuCadang->update([
            'stok' => $sukuCadang->stok - $jumlahDibutuhkan
        ]);

        return [
            'suku_cadang_id' => $idSukuCadang,
            'suku_cadang_nama' => $sukuCadang->nama,
            'suku_cadang_kode' => $sukuCadang->kode,
            'jumlah_dipakai' => $jumlahDibutuhkan,
            'stok_total_setelah' => $sukuCadang->stok,
            'batches_digunakan' => $logDetails
        ];
    }

    /**
     * Rollback FIFO logs (jika perlu undo transaksi)
     * Mengembalikan sisa_stok ke state sebelumnya
     */
    public function rollbackFifo($idOrder)
    {
        Log::info('=== FIFO Rollback Start ===', ['order_id' => $idOrder]);

        try {
            $fifoLogs = FifoLog::where('id_order', $idOrder)->get();

            foreach ($fifoLogs as $log) {
                // Kembalikan sisa_stok di batch
                $batch = StokMasuk::findOrFail($log->id_batch_stok);
                $batch->update([
                    'sisa_stok' => $batch->sisa_stok + $log->qty_dipakai
                ]);

                // Kembalikan stok di suku_cadang (akan di-handle di controller jika perlu)

                Log::info('FIFO Rollback Detail', [
                    'batch_id' => $batch->id,
                    'qty_restored' => $log->qty_dipakai
                ]);
            }

            // Delete FIFO logs
            FifoLog::where('id_order', $idOrder)->delete();

            Log::info('=== FIFO Rollback Success ===', ['order_id' => $idOrder]);

            return [
                'success' => true,
                'message' => 'FIFO logs berhasil di-rollback',
                'logs_deleted' => count($fifoLogs)
            ];
        } catch (Exception $e) {
            Log::error('=== FIFO Rollback Failed ===', [
                'order_id' => $idOrder,
                'error' => $e->getMessage()
            ]);

            throw new Exception('Gagal rollback FIFO: ' . $e->getMessage());
        }
    }

    /**
     * Get FIFO logs untuk satu order
     */
    public function getOrderLogs($idOrder)
    {
        return FifoLog::where('id_order', $idOrder)
            ->with(['sukuCadang', 'stokMasuk'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get ringkasan FIFO untuk display
     */
    public function getSummary($idOrder)
    {
        $logs = $this->getOrderLogs($idOrder);

        $summary = [];
        foreach ($logs->groupBy('id_suku_cadang') as $sukuCadangId => $logsBySparepart) {
            $firstLog = $logsBySparepart->first();
            $totalQty = $logsBySparepart->sum('qty_dipakai');

            $summary[] = [
                'suku_cadang' => $firstLog->sukuCadang->nama,
                'kode' => $firstLog->sukuCadang->kode,
                'total_qty_dipakai' => $totalQty,
                'batch_count' => count($logsBySparepart),
                'batches' => $logsBySparepart->map(function ($log) {
                    return [
                        'id' => $log->id_batch_stok,
                        'tanggal_masuk' => $log->stokMasuk->tanggal,
                        'qty' => $log->qty_dipakai,
                        'harga_beli' => $log->harga_beli
                    ];
                })
            ];
        }

        return $summary;
    }
}
