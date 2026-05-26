<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;

class Tag extends Model
{
    protected $fillable = ['name'];

    /**
     * Relationship: Polymorphic Inverse of Many-to-Many with Articles
     */
    public function articles(): MorphToMany
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }

   
}