<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberSubcription extends Model
{
    protected $fillable = [
        'member_id',
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
        return $this->belongsTo(User::class, 'member_id');
    }
}
