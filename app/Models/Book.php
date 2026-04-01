<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ReadingHistory;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Book extends Model
{
    use Notifiable;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'publication_year',
        'summary',
        'subscription',
        'language',
        'is_recommended',
        'total_pages',
        'cover_file_name',
        'pdf_file_name'
    ];

    protected $casts = [
        'subscription' => 'boolean',
        'is_recommended' => 'boolean',
        'publication_year' => 'integer',
        'total_pages' => 'integer',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class, 'book_id');
    }

    public function readingHistoryFor(int $userId)
    {
        return $this->readingHistories()->where('user_id', $userId)->first();
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(User::class, 'user_favorite_books')->withTimestamps()->orderByPivot('created_at', 'desc');
    }

    public function ratedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_reting_books')
            ->withPivot('rating')
            ->withTimestamps();
    }

    public function averageRating()
    {
        $rate = $this->ratedByUsers()->wherePivotNotNull('rating');

        return $rate->count() == 0 ? null : round($rate->avg(), 2);
    }
}
