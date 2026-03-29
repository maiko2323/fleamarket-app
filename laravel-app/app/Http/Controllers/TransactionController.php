<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Mail\TransactionCompletedMail;
use App\Models\SoldItem;
use App\Models\TransactionChat;
use App\Models\TransactionRating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function show($soldItem)
    {
        $user = Auth::user();

        $soldItem = SoldItem::with(['item.user', 'buyer', 'transactionChats.user.profile'])
            ->findOrFail($soldItem);

        $item = $soldItem->item;
        $seller = $item->user;
        $buyer = $soldItem->buyer;

        if ($user->id !== $seller->id && $user->id !== $buyer->id) {
            abort(403);
        }

        $items = SoldItem::with('item')
            ->whereNull('completed_at')
            ->where(function ($query) use ($user) {
                $query->where('buyer_id', $user->id)
                    ->orWhereHas('item', function ($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
            })
            ->get();

        $soldItem->transactionChats()
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        $partner = $user->id === $seller->id ? $buyer : $seller;
        $partnerImage = $partner->profile->profile_img;
        $chats = $soldItem->transactionChats;
        $hasRated = TransactionRating::where('sold_item_id', $soldItem->id)
            ->where('rater_id', $user->id)
            ->exists();

        $shouldOpenRatingModal = $soldItem->completed_at && !$hasRated;

        return view('transactions.show', compact(
            'soldItem',
            'item',
            'seller',
            'buyer',
            'partner',
            'chats',
            'items',
            'partnerImage',
            'shouldOpenRatingModal',
        ));
    }

    public function storeMessage(ChatRequest $request, $soldItemId)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:400'],
            'chat_img' => ['nullable', 'image', 'mimes:jpeg,png'],
        ]);

        $soldItem = SoldItem::with(['item.user', 'buyer'])->findOrFail($soldItemId);
        $user = auth()->user();

        if ($user->id !== $soldItem->item->user_id && $user->id !== $soldItem->buyer_id) {
            abort(403);
        }

        $chatImgPath = null;

        if ($request->hasFile('chat_img')) {
            $path = $request->file('chat_img')->store('chat_images', 'public');
            $chatImgPath = '/storage/' . $path;
        }

        TransactionChat::create([
            'sold_item_id' => $soldItem->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'chat_img' => $chatImgPath,
            'read_at' => null,
        ]);

        return redirect()
            ->route('transactions.show', ['soldItem' => $soldItem->id])
            ->with('chat_sent', true);
    }

    public function update(Request $request, $chatId)
    {
        $chat = TransactionChat::findOrFail($chatId);

        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => ['required', 'string', 'max:400'],
        ]);

        $chat->update([
            'message' => $request->message,
        ]);

        return back();
    }

    public function destroy($chatId)
    {
        $chat = TransactionChat::findOrFail($chatId);

        if ($chat->user_id !== auth()->id()) {
            abort(403);
        }

        $chat->delete();

        return back();
    }

    public function complete($soldItemId)
    {
        $soldItem = SoldItem::with(['item.user', 'buyer'])->findOrFail($soldItemId);

        if (auth()->id() !== $soldItem->buyer_id) {
            abort(403);
        }

        $soldItem->update([
            'completed_at' => now(),
        ]);

        Mail::to($soldItem->item->user->email)
            ->send(new TransactionCompletedMail($soldItem));

        return redirect()
            ->route('transactions.show', ['soldItem' => $soldItem->id])
            ->with('open_rating_modal', true);
    }

    public function rate(Request $request, $soldItemId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $soldItem = SoldItem::with(['item.user', 'buyer'])->findOrFail($soldItemId);

        $user = auth()->user();

        $partner = $user->id === $soldItem->buyer_id
            ? $soldItem->item->user
            : $soldItem->buyer;

        $exists = TransactionRating::where('sold_item_id', $soldItem->id)
            ->where('rater_id', auth()->id())
            ->exists();

        if ($exists) {
            return back();
        }

        TransactionRating::create([
            'sold_item_id' => $soldItem->id,
            'rater_id' => $user->id,
            'rated_user_id' => $partner->id,
            'score' => $request->rating,
        ]);

        return redirect()->route('top');
    }
}
