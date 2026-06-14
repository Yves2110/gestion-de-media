<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['role_id', 'uuid', 'firstname', 'lastname', 'email', 'password', 'statut'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role_id === 1;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role_id, [1, 2], true);
    }

    public function isClient(): bool
    {
        return $this->role_id === 3;
    }

    public function scopeAdmin($query)
    {
        return $query->whereIn('role_id', [1, 2]);
    }

    public function scopeIdDescending($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
