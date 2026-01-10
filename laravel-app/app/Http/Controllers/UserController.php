<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\Profile;
use App\Http\Requests\ProfileRequest;



class UserController extends Controller
{
    public function showVerifyPage()
    {
        $user = auth()->user();

        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        return view('auth.verify-action', compact('verifyUrl'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building_name' => $request->building_name,
            ]
        );

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profile_images', 'public');
            $profile->profile_img = '/storage/' . $path;
            $profile->save();
            }

        return redirect()->route('mypage.show');

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
        $user = Auth::user()->load('profile');
        $profile = $user->profile;

        return view('user.profile', compact('user', 'profile'));
    }

}


