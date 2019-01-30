<?php

namespace App\Transformers;

use App\Models\Item;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Item $comment)
    {
        return $comment->attributesToArray();
    }

    public function includeUser(Item $comment)
    {
        if (! ($comment->user)) {
            return $this->null();
        }

        return $this->item($comment->user, new UserTransformer());
    }
}
