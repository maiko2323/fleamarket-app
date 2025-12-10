@extends('layouts.app')

@section('title', '商品購入画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-layout">
    <div class="main-form">
        <div class="item-section">
            <img src="{{ asset($item->item_img) }}" alt="{{ $item->name }}">

            <div class="item-info">
                <h2 class="item-name">{{ $item->name }}</h2>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <div class="payment-section">
            <label for="payment">支払い方法</label>
            <select id="payment" name="payment">
                <option value="">選択してください</option>
                <option value="convenience">コンビニ払い</option>
                <option value="credit">クレジットカード</option>
            </select>
        </div>

        <hr class="summary-divider">

        <div class="address-section">
            <div class="address-header">
                <label for="address">配送先</label>
                <a href="{{ route('purchase.address', ['item_id' => $item['id']]) }}" class="change-button">変更する</a>
            </div>

            <input type="text" id="address" name="address" placeholder="〒XXX-YYYY ここには住所と建物が入ります">
        </div>
    </div>

    <div class="summary-wrapper">
        <div class="summary-panel">
            <div class="summary-row">
                <span class="summary-label">商品代金</span>
                <span class="summary-value">¥{{ number_format($item['price']) }}</span>
            </div>

            <hr class="full-divider">

            <div class="summary-row">
                <span class="summary-label">支払い方法</span>
                <span class="summary-value" id="selected-method">{{ $selectedPayment ?? '未選択' }}</span>
            </div>
        </div>
        <button class="purchase-button">購入する</button>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const paymentSelect = document.getElementById('payment');
    const methodDisplay = document.getElementById('selected-method');

    paymentSelect.addEventListener('change', function () {
        const selectedText = this.options[this.selectedIndex].text;
        methodDisplay.textContent = selectedText || '未選択';
    });
</script>
@endsection
