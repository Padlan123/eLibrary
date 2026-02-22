<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Paket extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'pakets';

    protected $fillable = [
        'nama',
        'durasi',
        'harga',
        'deskripsi'
    ];
}
