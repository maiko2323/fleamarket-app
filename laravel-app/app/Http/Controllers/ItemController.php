<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ItemStoreRequest;


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

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'item_id', 'user_id');
    }

    public function mylist()
    {
        $items = auth()->user()->likedItems()->with('brand')->get();
        return view('items.mylist', compact('items'));
    }

    public function storeComment(CommentRequest $request, Item $item)
    {
        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return back();
    }

    public function store(ItemStoreRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('item_img')->store('public/item_images');

        $item = new Item();
        $item->name = $request->name;
        $item->brand = $request->brand;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->condition_id = $request->condition;
        $item->item_img = Storage::url($path);
        $item->user_id = auth()->id();
        $item->save();

        $item->categories()->sync($validated['categories']);

        return redirect()->route('mypage.show', ['page' => 'sell']);
    }

}
