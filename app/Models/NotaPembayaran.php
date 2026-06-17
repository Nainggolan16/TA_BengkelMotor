<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaPembayaran extends Model
{
    protected $table = 'nota_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_order',
        'id_pengguna',
        'total',
        'metode_bayar',
        'status_bayar',
        'dibayar_pada'
    ];

    public function orderServis()
    {
        return $this->belongsTo(
            OrderServis::class,
            'id_order'
        );
    }

    public function pengguna()
    {
        return $this->belongsTo(
            User::class,
            'id_pengguna'
        );
    }
}