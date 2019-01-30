<?php

namespace App\Transformers;

use App\Models\Checklist;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class ChecklistTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'comments', 'recentComments'];

    public function transform(Checklist $post)
    {
        return $post->attributesToArray();
    }

    public function includeUser(Checklist $post)
    {
        if (! $post->user) {
            return $this->null();
        }

        return $this->item($post->user, new UserTransformer());
    }

    public function includeComments(Checklist $post, ParamBag $params = null)
    {
        $limit = 10;
        if ($params->get('limit')) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }

        $comments = $post->comments()->limit($limit)->get();

        return $this->collection($comments, new ItemTransformer())
            ->setMeta([
                'limit' => $limit,
                'count' => $comments->count(),
            ]);
    }

    public function includeRecentComments(Checklist $post)
    {
        $comments = $post->recentComments->sortByDesc('id');

        return $this->collection($comments, new ItemTransformer())
            ->setMeta([
                'count' => $comments->count(),
            ]);
    }
}
