<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item;

class CommentController extends Controller
{
    public function store(Request $request, Item $item)
    {
        if (!auth()->check()) {
        return back();
        }

        $validated = $request->validate(
            (new CommentRequest())->rules(),
            (new CommentRequest())->messages()
        );

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('item.show', ['item_id' => $item->id]);
    }

}
