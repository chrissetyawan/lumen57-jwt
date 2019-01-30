<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Checklist extends BaseModel
{
    use SoftDeletes;

    protected $casts = ['extra' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function recentItems($limit = 5)
    {
        return $this->items()->limit($limit);
    }
}
