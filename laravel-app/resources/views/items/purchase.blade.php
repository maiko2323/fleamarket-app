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
                <h1 class="item-name">{{ $item->name }}</h1>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <form id="purchase-form" action="{{ route('purchase.complete', ['item_id' => $item->id]) }}" method="POST">
            @csrf

            <div class="payment-section">
                <h2 class="section-title">支払い方法</h2>
                <select id="payment_method" name="payment_method">
                    <option value="">選択してください</option>
                    <option value="コンビニ払い">コンビニ払い</option>
                    <option value="カード払い">カード払い</option>
                </select>
            </div>

            <hr class="summary-divider">

            <div class="address-section">
                <div class="address-header">
                    <h2 class="section-title">配送先</h2>
                    <a href="{{ route('purchase.address', ['item_id' => $item->id]) }}" class="change-button">変更する</a>
                </div>

                <div class="address-display">
                    @if($profile)
                        <p>〒{{ $profile->post_code }}</p>
                        <p>{{ $profile->address }}</p>
                        <p>{{ $profile->building_name }}</p>
                    @else
                        <p>配送先が登録されていません。</p>
                    @endif
                </div>
            </div>
        </form>
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

        <button type="submit" form="purchase-form" class="purchase-button">購入する</button>

    </div>
</div>
@endsection

@section('scripts')
<script>
    const paymentSelect = document.getElementById('payment_method');
    const methodDisplay = document.getElementById('selected-method');

    paymentSelect.addEventListener('change', function () {
        const selectedText = this.options[this.selectedIndex].text;
        methodDisplay.textContent = selectedText || '未選択';
    });
</script>
@endsection
