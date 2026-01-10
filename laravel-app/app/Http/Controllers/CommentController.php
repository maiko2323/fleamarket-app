<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        $validated = $request->validated();

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('item.show', ['item_id' => $item->id]);
    }

}
