<?php

namespace App\Models;

class Item extends BaseModel
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public static function boot()
    {
        static::created(function ($item) {
            $item->checklist->increment('comment_count');
        });

        static::deleted(function ($item) {
            $item->checklist->decrement('comment_count');
        });

        parent::boot();
    }
}
