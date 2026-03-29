<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ReadingHistory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscribes()
    {
        return $this->hasMany(MemberSubscription::class, 'user_id');
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'member_subscriptions', 'user_id', 'package_id')
            ->withPivot('start_date', 'end_date', 'status')
            ->withTimestamps();
    }

    public function readingHistories()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'user_favorite_books')->withTimestamps();
    }
}
