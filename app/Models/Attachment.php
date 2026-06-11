<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = ['file_path', 'file_type', 'attachable_id', 'attachable_type'];

    /**
     * Relationship: Polymorphic MorphTo (Can be a Profile, Article, etc.)
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}