<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\SoldItem;
use App\Http\Requests\AddressRequest;
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

        return view('items.address', compact('item', 'profile'));
    }

        public function complete(Request $request, $item_id)
    {
        $validated = $request->validate([
        'payment_method' => 'required|in:コンビニ払い,カード払い',
    ]);

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

    $profile = auth()->user()->profile;
    $profile->update($validated);

    return back()->with('success', '住所を更新しました');
    }
}