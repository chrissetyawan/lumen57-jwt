<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Checklist;
use Illuminate\Http\Request;
use App\Transformers\ChecklistTransformer;

class ChecklistController extends BaseController
{

    public function index()
    {
        $items = Checklist::orderBy('created_at', 'desc')->paginate();

        return $this->response->paginator($items, new ChecklistTransformer());
    }

    public function userIndex()
    {
        $items = Checklist::where(['user_id' => $this->user()->id])
            ->paginate();

        return $this->response->paginator($items, new ChecklistTransformer());
    }

    public function show($id)
    {
        $item = Checklist::findOrFail($id);

        return $this->response->item($item, new ChecklistTransformer());
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->input(), [
            'title' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $attributes = $request->only('title', 'content');
        $attributes['user_id'] = $this->user()->id;
        $item = Checklist::create($attributes);

        $location = dingo_route('v1', 'posts.show', $item->id);
        return $this->response
            ->item($item, new ChecklistTransformer())
            //->withHeader('Location', $location)
            ->setStatusCode(201);
    }

    public function update($id, Request $request)
    {
        $item = Checklist::findOrFail($id);

        // forbidden
        if ($item->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $validator = \Validator::make($request->input(), [
            'title' => 'required|string|max:50',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $item->update($request->only('title', 'content'));

        return $this->response->noContent();
    }

    public function destroy($id)
    {
        $item = Checklist::findOrFail($id);

        // forbidden
        if ($item->user_id != $this->user()->id) {
            return $this->response->errorForbidden();
        }

        $item->delete();

        return $this->response->noContent();
    }
}
