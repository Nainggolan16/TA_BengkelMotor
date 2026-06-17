<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\DetailJasaTambahan;

class OrderServis extends Model
{
    protected $table = 'order_servis';

    protected $fillable = [
        'kode_order',
        'id_kendaraan',
        'id_jenis_servis',
        'id_pengguna',
        'keluhan',
        'catatan_servis',
        'status',
        'metode_pembayaran',
        'tanggal_bayar',
        'biaya_jasa',
        'biaya_jasa_tambahan',
        'total_harga',
        'tanggal_masuk',
        'tanggal_selesai'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_bayar' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }

    public function jenisServis()
    {
        return $this->belongsTo(JenisServis::class, 'id_jenis_servis');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function detailSukuCadang()
    {
        return $this->hasMany(DetailSukuCadang::class, 'id_order');
    }

    public function detailServis()
    {
        return $this->hasMany(DetailServis::class, 'id_order');
    }

    public function jasaTambahan()
    {
        return $this->hasMany(DetailJasaTambahan::class, 'id_order');
    }

    public function fifoLogs()
    {
        return $this->hasMany(FifoLog::class, 'id_order');
    }

    public function notaPembayaran()
    {
        return $this->hasOne(NotaPembayaran::class, 'id_order');
    }
}
