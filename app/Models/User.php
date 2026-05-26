<?php

namespace App\Models;

// تم استيراد الـ Trait المسؤول عن الـ Tokens
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];

    /**
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relationship: One-to-Many with Articles (As an Author)
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id');
    }

    /**
     * Relationship: One-to-Many with Comments (As a Commenter)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}