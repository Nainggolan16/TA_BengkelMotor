<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StokMasuk extends Model
{
    protected $table = 'stok_masuk';

    public $timestamps = false;

    protected $fillable = [
        'id_suku_cadang',
        'id_pengguna',
        'jumlah',
        'sisa_stok',
        'harga_beli',
        'harga_jual',
        'tanggal',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'id_suku_cadang');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}