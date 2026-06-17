<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\OrderServis;

class Kendaraan extends Model
{
    protected $table = 'kendaraan';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'plat_nomor',
        'merk',
        'tipe',
        'tahun',
        'warna',
        'nama_kendaraan',
    ];

    protected $appends = [
        'nama_kendaraan',
    ];

    public function getNamaKendaraanAttribute()
    {
        $name = trim(($this->merk ?? '') . ' ' . ($this->tipe ?? ''));
        return $name ?: null;
    }

    public function setNamaKendaraanAttribute($value)
    {
        $this->attributes['merk'] = $value;
        $this->attributes['tipe'] = '';
    }

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function orderServis(){
        return $this->hasMany(OrderServis::class, 'id_kendaraan');
    }
}