<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTransaction extends Model
{
    protected $fillable = [
        'member_id',
        'package_id',
        'name',
        'number',
        'amount',
        'invoice_number',
        'payment_proof',
        'paid_date',
        'status',
    ];

    protected $casts = [
        'paid_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
