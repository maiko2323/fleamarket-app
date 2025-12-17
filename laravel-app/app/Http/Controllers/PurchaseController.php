<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('items.purchase', compact('item'));
    }

    public function editAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('items.address', compact('item'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        $validated = $request->validate([
            'postal_code' => 'required|string|max:10',
            'address'     => 'required|string|max:255',
            'building'    => 'nullable|string|max:255',
        ]);

        $purchase = Purchase::where('item_id', $item_id)->firstOrFail();
        $purchase->update($validated);

        return redirect()->route('purchase.address', ['item_id' => $item_id])
                    ->with('success', '住所を更新しました！');
    }
}