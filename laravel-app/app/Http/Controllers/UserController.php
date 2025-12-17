<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;


class UserController extends Controller
{

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/profile_images');
            $user->image_path = $path;
        }

        $user->save();

        return redirect()->route('mypage.profile')->with('success', 'プロフィールを更新しました');

    }

    public function showMypage(Request $request)
    {
    $user = Auth::user();
    $profile = $user->profile ?? null;

    $page = $request->query('page');

    if ($page === 'buy') {
        $items = $user->soldItems()->with('item')->get();
    } elseif ($page === 'sell') {
        $items = $user->items()->get();
    } else {
        $items = collect();
    }

    return view('user.mypage', compact('user', 'profile', 'items', 'page'));
    }

    public function edit()
    {
    $user = Auth::user();
    return view('user.profile', compact('user'));
    }

}


