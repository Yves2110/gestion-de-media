<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['label'];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function scopeIdDescending($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
