<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SukuCadang extends Model
{
    protected $table = 'suku_cadang';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama',
        'stok',
        'stok_minimum',
        'harga_beli',
        'harga_jual'
    ];

    public function stokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'id_suku_cadang');
    }

    public function detailSukuCadang()
    {
        return $this->hasMany(DetailSukuCadang::class, 'id_suku_cadang');
    }

    public function fifoLogs()
    {
        return $this->hasMany(FifoLog::class, 'id_suku_cadang');
    }
}
