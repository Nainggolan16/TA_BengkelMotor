<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriServis extends Model
{
    protected $table = 'kategori_servis';

    protected $fillable = [
        'nama_kategori',
        'keterangan'
    ];

    public function jenisServis()
    {
        return $this->hasMany(JenisServis::class, 'id_kategori_servis');
    }
}
