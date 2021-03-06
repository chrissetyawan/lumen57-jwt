<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Http\Request;
use League\Fractal\Pagination\Cursor;
use App\Transformers\ItemTransformer;

class ItemController extends BaseController
{
    public function index($checklistId, Request $request)
    {
        $checklist = Checklist::findOrFail($checklistId);

        $items = $checklist->items();

        $currentCursor = $request->get('cursor');

        if ($currentCursor !== null) {
            $currentCursor = (int) $request->get('cursor', null);
            // how to use previous ??
            // $prevCursor = $request->get('previous', null);
            $limit = $request->get('limit', 10);

            $items = $items->where([['id', '>', $currentCursor]])->limit($limit)->get();

            if ($items->count()) {
                $nextCursor = $items->last()->id;
                $prevCursor = $currentCursor;

                $cursorPatination = new Cursor($currentCursor, $prevCursor, $nextCursor, $items->count());

                return $this->response->collection($items, new ItemTransformer(), [], function ($resource) use ($cursorPatination) {
                    $resource->setCursor($cursorPatination);
                });
            }

            return $this->response->collection($items, new ItemTransformer());
        } else {
            $items = $items->orderBy('created_at', 'desc')->paginate();

            return $this->response->paginator($items, new ItemTransformer());
        }
    }

    public function show($checklistId, $id)
    {
        $item = Item::where('checklist_id', $checklistId)
          ->where('id', $id)
          ->paginate();

        return $this->response->paginator($item, new ItemTransformer());
    }

    public function store($checklistId, Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $checklist = Checklist::findOrFail($checklistId);

        $user = $this->user();

        $item = new Item;
        $item->content = $request->get('content');
        $item->user_id = $user->id;
        $item->post_id = $checklist->id;
        $item->save();

        return $this->response->item($item, new ItemTransformer())
            ->setStatusCode(201);
    }

    public function destroy($checklistId, $id)
    {
        $item = Item::where('checklist_id', $checklistId)
            ->where('id', $id)
            ->firstOrFail();

        if ($comment->user_id != $this->user()->id) {
            abort(403);
        }

        $comment->delete();

        return $this->response->noContent();
    }
}
