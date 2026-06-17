<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailServis extends Model
{
    protected $table = 'detail_servis';

    public $timestamps = false;

    protected $fillable = [
        'id_order',
        'id_jenis_servis',
        'harga_jasa'
    ];

    public function orderServis()
    {
        return $this->belongsTo(OrderServis::class, 'id_order');
    }

    public function jenisServis()
    {
        return $this->belongsTo(JenisServis::class, 'id_jenis_servis');
    }
}
