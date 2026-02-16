<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DTransaction extends Model
{
    protected $fillable = [
        'anggota_id',
        'admin_id',
        'transaction_id',
        'subtotal',
    ];
}
