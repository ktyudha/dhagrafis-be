<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope a query to searching articles from given key.
     */
    public function scopeSearch(Builder $query, string $key): void
    {
        $query->where('name', 'like', "%$key%")
            ->orWhere('email', 'like', "%$key%")
            ->orWhere('phone', 'like', "%$key%");
    }

    /**
     * Scope a query to only include verified users.
     */
    public function scopeVerified(Builder $query): void
    {
        $query->whereNotNull('email_verified_at');
    }

    /**
     * Get the articles for this user.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Get the published articles for this user.
     */
    public function publishedArticles(): HasMany
    {
        return $this->hasMany(Article::class, 'published_by');
    }

    /**
     * Get the artworks for this user.
     */
    public function artworks(): HasMany
    {
        return $this->hasMany(Artwork::class, 'author_id');
    }

    /**
     * Get the published artworks for this user.
     */
    public function publishedArtworks(): HasMany
    {
        return $this->hasMany(Artwork::class, 'published_by');
    }

    /**
     * Get the events for this user.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'author_id');
    }

    /**
     * Get the published artworks for this user.
     */
    public function publishedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'published_by');
    }
}
