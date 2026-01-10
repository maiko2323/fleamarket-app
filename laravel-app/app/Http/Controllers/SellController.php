<?php

namespace App\Http\Controllers;

use App\Models\Category;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('sell.sell', compact('categories'));
    }

}
