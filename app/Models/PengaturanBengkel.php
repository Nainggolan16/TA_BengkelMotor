<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBengkel extends Model
{
    protected $table = 'pengaturan_bengkel';

    protected $fillable = [
        'key',
        'value',
    ];

    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
}
