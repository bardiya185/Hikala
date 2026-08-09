<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected string $guard_name = 'sanctum';

    public function getDefaultGuardName(): string
    {
        return 'sanctum';
    }

    protected $fillable = [
        'name',
        'mobile',
    ];

    protected $hidden = [
        'remember_token',
    ];
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
