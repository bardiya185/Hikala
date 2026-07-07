<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
    ];

    protected $hidden = [
        'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany(Role)
    }

}