<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Item

class CommentController extends Controller
{
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return redirect()->route('item.show', ['item' => $item->id])
                    ->with('success', 'コメントを投稿しました！');
    }

}
