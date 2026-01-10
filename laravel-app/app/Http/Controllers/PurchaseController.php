<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\SoldItem;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = auth()->user()->profile;

        return view('items.purchase', compact('item', 'profile'));

    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = auth()->user()->profile;

        $title = $profile ? '住所の変更' : '住所の登録';
        $buttonLabel = $profile ? '更新する' : '登録する';

        return view('items.address', compact('item', 'profile', 'title', 'buttonLabel'));
    }

    public function complete(PurchaseRequest $request, $item_id)
    {
        if (!auth()->user()->profile || !auth()->user()->profile->post_code) {
        return redirect()
            ->route('purchase.address', ['item_id' => $item_id])
            ->with('error', '購入前に配送先を登録してください。');
        }

        $validated = $request->validated();

        $item = Item::findOrFail($item_id);

        SoldItem::create([
            'item_id'       => $item->id,
            'buyer_id'      => auth()->id(),
            'payment_method'=> $validated['payment_method'],
            'post_code'     => auth()->user()->profile->post_code,
            'address'       => auth()->user()->profile->address,
            'building_name' => auth()->user()->profile->building_name,
            'sold_at'       => now(),
        ]);

        $item->update(['status' => 'sold']);

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethodTypes = $validated['payment_method'] === 'カード払い'
            ? ['card']
            : ['konbini'];

        $session = CheckoutSession::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => (int) $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
            ]],
            'success_url' => route('top'),
            'cancel_url'  => route('top'),
        ]);

        return redirect($session->url);
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $validated = $request->validated();

        auth()->user()->profile()->updateOrCreate([], $validated);

        return redirect()
            ->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '配送先を保存しました。');
    }
}