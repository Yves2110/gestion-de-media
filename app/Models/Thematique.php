<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thematique extends Model
{
    use HasFactory;

    protected $fillable = ['label'];

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_thematique');
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'document_thematique');
    }

    public function scopeIdDescending($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
