<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'anggota_id',
        'paket_id',
        'tanggal_bayar',
        'status'
    ];

    protected $casts = [
        'status' => 'array',
    ];
}
