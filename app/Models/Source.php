<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;
    protected $fillable = ['label'];
    
    public function scopeIdDescending($query){
        return $query->orderBy('created_at','desc');
    }
}
