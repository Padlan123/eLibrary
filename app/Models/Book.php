<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ReadingHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'summary',
        'subscription',
        'total_pages',
        'cover_file_name',
        'pdf_file_name'
    ];

    protected $casts = [
        'subscription' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function readingHistoryFor(int $userId)
    {
        return $this->readingHistories()->where('user_id', $userId)->first();
    }
}
