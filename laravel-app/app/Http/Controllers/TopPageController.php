<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;


class TopPageController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');

        if ($tab === 'mylist') {
            if (auth()->check()) {
                $items = auth()->user()->likedItems()->with('user')->get();
            } else {
                $items = collect();
            }
        } else {
            $items = Item::where('user_id', '!=', auth()->id())
                ->latest()
                ->take(12)
                ->get();
    }

    return view('top', compact('items', 'tab'));
    }


}
