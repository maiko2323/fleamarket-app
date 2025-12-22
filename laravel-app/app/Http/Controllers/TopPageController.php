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
                $query = auth()->user()->likedItems()->with('user');

            } else {
                $items = collect();
                return view('top', compact('items', 'tab'));
            }
        } else {
            $query = Item::where('user_id', '!=', auth()->id())->latest();
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $items = $query->paginate(12);

    return view('top', compact('items', 'tab'));
    }


}
