<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'anggota_id',
        'paket_id',
        'nama_pengirim',
        'nomor_pengirim',
        'bukti_pembayaran',
        'tanggal_bayar',
        'status'
    ];

    protected $casts = [
        'status' => 'array',
        'tanggal_bayar' => 'date',
    ];
}
