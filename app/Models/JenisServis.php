<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisServis extends Model
{
    protected $table = 'jenis_servis';

    public $timestamps = false;

    protected $fillable = [
        'id_kategori_servis',
        'nama_servis',
        'harga_jasa',
        'keterangan'
    ];

    public function kategoriServis()
    {
        return $this->belongsTo(KategoriServis::class, 'id_kategori_servis');
    }

    public function orderServis()
    {
        return $this->hasMany(OrderServis::class, 'id_jenis_servis');
    }

    public function detailServis()
    {
        return $this->hasMany(DetailServis::class, 'id_jenis_servis');
    }
}

