<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends BaseModel implements AuthenticatableContract, JWTSubject
{
    use SoftDeletes, Authenticatable;

    protected $hidden = ['password', 'deleted_at'];

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
