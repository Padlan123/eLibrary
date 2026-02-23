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
        'tanggal_bayar' => 'date',
    ];

    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class, 'paket_id');
    }
}
