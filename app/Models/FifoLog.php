<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FifoLog extends Model
{
    protected $table = 'fifo_logs';

    protected $fillable = [
        'id_order',
        'id_suku_cadang',
        'id_batch_stok',
        'qty_dipakai',
        'sisa_batch',
        'harga_beli',
        'keterangan'
    ];

    public function orderServis()
    {
        return $this->belongsTo(OrderServis::class, 'id_order');
    }

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'id_suku_cadang');
    }

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class, 'id_batch_stok');
    }
}
