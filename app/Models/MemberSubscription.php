<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function scopeActive($query)
    {
        $today = now()->toDateString();
        return $query->where('status', 'active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '=>', $today);
    }
}
