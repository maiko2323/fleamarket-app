<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SellController extends Controller
{
        public function create()
    {
        $categories = Category::all();
        return view('sell.sell', compact('categories'));
    }

    public function store(Request $request)
    {
        return redirect()->route('listing.complete');
    }


}
