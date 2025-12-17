<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellController extends Controller
{
    public function create()
{
    return view('sell.create'); // resources/views/sell/create.blade.php
}

public function store(Request $request)
{

    return redirect()->route('listing.complete'); // 
}

}
