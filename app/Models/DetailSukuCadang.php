<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSukuCadang extends Model
{
    protected $table = 'detail_suku_cadang';

    public $timestamps = false;

    protected $fillable = [
        'id_order',
        'id_suku_cadang',
        'jumlah',
        'harga_jual'
    ];

    public function orderServis()
    {
        return $this->belongsTo(OrderServis::class, 'id_order');
    }

    public function sukuCadang()
    {
        return $this->belongsTo(SukuCadang::class, 'id_suku_cadang');
    }
}
