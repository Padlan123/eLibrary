<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'judul',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'kategori',
        'sinopsis',
        'premium',
    ];

    protected $casts = [
        'kategori' => 'array',
        'premium' => 'boolean',
    ];
}
