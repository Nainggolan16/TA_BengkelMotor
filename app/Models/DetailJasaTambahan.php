<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailJasaTambahan extends Model
{
    protected $table = 'detail_jasa_tambahan';

    protected $fillable = [
        'id_order',
        'nama_jasa',
        'biaya',
    ];

    public function orderServis()
    {
        return $this->belongsTo(OrderServis::class, 'id_order');
    }
}
