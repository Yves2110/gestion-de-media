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
    protected $fillable = ['role_id','uuid','firstname','lastname','email','password','statut'];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'expires_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'firstame' => 'datetime',
    ];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role_id',2);
    }
    public function scopeIdDescending($query){
        return $query->orderBy('created_at','desc');
    }

}
