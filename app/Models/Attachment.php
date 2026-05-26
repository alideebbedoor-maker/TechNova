<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    protected $fillable = ['file_path', 'file_type', 'attachable_id', 'attachable_type'];

    /**
     * Relationship: Polymorphic MorphTo (Can be a Profile, Article, etc.)
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}