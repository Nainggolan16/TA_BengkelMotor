<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kendaraan;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'no_telepon',
        'alamat',
        'dibuat_pada'
    ];

    public function kendaraan(){
        return $this->hasMany(Kendaraan::class, 'id_pelanggan');
    }
}
