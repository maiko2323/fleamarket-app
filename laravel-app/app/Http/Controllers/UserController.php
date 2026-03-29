<?php

namespace App\Http\Controllers;

use App\Models\SoldItem;
use App\Models\TransactionChat;
use App\Models\TransactionRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function showVerifyPage()
    {
        $user = Auth::user();

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
        $averageScore = $user->receivedRatings()->avg('score');
        $averageScore = $averageScore ? round($averageScore) : 0;

        $unreadMessageCount = TransactionChat::whereNull('read_at')
            ->where('user_id', '!=', $user->id)
            ->whereHas('soldItem', function ($query) use ($user) {
                $query->where(function ($q) use ($user) {
                    $q->where('buyer_id', $user->id)
                        ->orWhereHas('item', function ($itemQuery) use ($user) {
                            $itemQuery->where('user_id', $user->id);
                        });
                });
            })
            ->count();

        if ($page === 'buy') {
            $items = $user->soldItems()->with('item')->get();
        } elseif ($page === 'sell') {
            $items = $user->items()->get();
        } elseif ($page === 'transaction') {
            $items = SoldItem::with(['item', 'transactionChats'])
                ->where(function ($query) use ($user) {
                    $query->where('buyer_id', $user->id)
                        ->orWhereHas('item', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                })
                ->get()
                ->sortByDesc(function ($sold) {
                    return $sold->transactionChats->max('created_at');
                });

            $items = $items->filter(function ($sold) use ($user) {
                $hasRated = TransactionRating::where('sold_item_id', $sold->id)
                    ->where('rater_id', $user->id)
                    ->exists();

                return !$hasRated;
            });

            $items->each(function ($sold) use ($user) {
                $sold->unread_count = TransactionChat::where('sold_item_id', $sold->id)
                    ->whereNull('read_at')
                    ->where('user_id', '!=', $user->id)
                    ->count();
            });
        } else {
            $items = collect();
        }

        return view('user.mypage', compact(
            'user',
            'profile',
            'items',
            'page',
            'unreadMessageCount',
            'averageScore'
        ));
    }

    public function edit()
    {
        $user = Auth::user()->load('profile');
        $profile = $user->profile;

        return view('user.profile', compact('user', 'profile'));
    }
}


