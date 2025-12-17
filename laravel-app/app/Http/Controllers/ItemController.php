<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class ItemController extends Controller
{

    public function show($item_id)
    {
        $item = Item::withCount('likes')
        ->with(['categories', 'condition', 'comments.user'])
        ->findOrFail($item_id);


        $user = auth()->user();

        $isLiked = $user ? $item->likes()->where('user_id', $user->id)->exists() : false;

        return view('items.show', compact('item', 'isLiked'));
    }

    public function like(Item $item)
    {
        $user = auth()->user();

        if ($item->likes()->where('user_id', $user->id)->exists()) {
        $item->likes()->detach($user->id);
        } else {
        $item->likes()->attach($user->id);
        }

        return back();
    }

    public function mylist()
    {
        $items = auth()->user()->likedItems()->with('brand')->get();
        return view('items.mylist', compact('items'));
    }

    public function storeComment(Request $request, Item $item)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back();
    }
}
