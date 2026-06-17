<?php

namespace App\Services;

use App\Models\StokMasuk;
use App\Models\SukuCadang;
use Illuminate\Support\Facades\Log;

class ActiveBatchService
{
    /**
     * Get batch aktif untuk suku cadang tertentu
     * Batch aktif = batch FIFO tertua dengan sisa_stok > 0
     */
    public function getActiveBatch($idSukuCadang)
    {
        $batch = StokMasuk::where('id_suku_cadang', $idSukuCadang)
            ->where('sisa_stok', '>', 0)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->first();

        return $batch;
    }

    /**
     * Update status batch menjadi HABIS jika sisa_stok = 0
     * Dan update harga_beli_aktif ke batch berikutnya
     */
    public function updateBatchStatus($idSukuCadang)
    {
        Log::info('=== Active Batch Status Check Start ===', ['suku_cadang_id' => $idSukuCadang]);

        // Update batch yang sudah habis
        StokMasuk::where('id_suku_cadang', $idSukuCadang)
            ->where('sisa_stok', '<=', 0)
            ->where('status_batch', '!=', 'HABIS')
            ->update(['status_batch' => 'HABIS']);

        // Cari batch aktif yang baru
        $activeBatch = $this->getActiveBatch($idSukuCadang);
        $sukuCadang = SukuCadang::findOrFail($idSukuCadang);

        if ($activeBatch) {
            // Update harga_beli_aktif ke harga dari batch aktif
            $oldPrice = $sukuCadang->harga_beli_aktif;
            $sukuCadang->update([
                'harga_beli_aktif' => $activeBatch->harga_beli,
                'status_batch' => 'AKTIF'
            ]);

            Log::info('Active Batch Updated', [
                'suku_cadang_id' => $idSukuCadang,
                'batch_id' => $activeBatch->id,
                'old_price' => $oldPrice,
                'new_price' => $activeBatch->harga_beli,
                'batch_tanggal' => $activeBatch->tanggal
            ]);
        } else {
            // Semua batch habis, harga_beli_aktif tetap (fallback ke harga terakhir)
            Log::info('All Batches Depleted', [
                'suku_cadang_id' => $idSukuCadang,
                'last_active_price' => $sukuCadang->harga_beli_aktif
            ]);
        }

        Log::info('=== Active Batch Status Check End ===');
    }

    /**
     * Initialize harga_beli_aktif saat stok masuk pertama kali
     */
    public function initializeActiveBatch($idSukuCadang)
    {
        $activeBatch = $this->getActiveBatch($idSukuCadang);
        $sukuCadang = SukuCadang::findOrFail($idSukuCadang);

        if ($activeBatch && !$sukuCadang->harga_beli_aktif) {
            $sukuCadang->update([
                'harga_beli_aktif' => $activeBatch->harga_beli
            ]);

            Log::info('Active Batch Initialized', [
                'suku_cadang_id' => $idSukuCadang,
                'batch_id' => $activeBatch->id,
                'harga_beli_aktif' => $activeBatch->harga_beli
            ]);
        }
    }

    /**
     * Get ringkasan batch untuk suku cadang
     */
    public function getBatchSummary($idSukuCadang)
    {
        $batches = StokMasuk::where('id_suku_cadang', $idSukuCadang)
            ->orderBy('tanggal', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $summary = [
            'total_batches' => count($batches),
            'active_batches' => 0,
            'depleted_batches' => 0,
            'batches' => []
        ];

        foreach ($batches as $batch) {
            $status = $batch->sisa_stok > 0 ? 'AKTIF' : 'HABIS';
            $isActive = $status === 'AKTIF' && $this->getActiveBatch($idSukuCadang)->id === $batch->id;

            if ($status === 'AKTIF') {
                $summary['active_batches']++;
            } else {
                $summary['depleted_batches']++;
            }

            $summary['batches'][] = [
                'id' => $batch->id,
                'tanggal_masuk' => $batch->tanggal,
                'jumlah_masuk' => $batch->jumlah,
                'sisa_stok' => $batch->sisa_stok,
                'harga_beli' => $batch->harga_beli,
                'status' => $status,
                'is_current_active' => $isActive
            ];
        }

        return $summary;
    }
}
