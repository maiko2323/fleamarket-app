@extends('layouts.app')

@section('title', '送付先住所変更画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')
<div class="address-update-container">
        <h2 class="page-title">住所の変更</h2>

        <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST">
        @csrf

        <div class="form-group">
        <label for="post_code">郵便番号</label>
        <input type="text" id="post_code" name="post_code"
        value="{{ old('post_code', Auth::user()->profile->post_code ?? '') }}">
        @error('post_code')
                <div class="error-message">{{ $message }}</div>
        @enderror
</div>

        <div class="form-group">
        <label for="address">住所</label>
        <input type="text" id="address" name="address"
            value="{{ old('address', Auth::user()->profile->address ?? '') }}">
            @error('address')
                    <div class="error-message">{{ $message }}</div>
            @enderror
    </div>

    <div class="form-group">
        <label for="building_name">建物名</label>
        <input type="text" id="building_name" name="building_name"
            value="{{ old('building_name', Auth::user()->profile->building_name ?? '') }}">
            @error('building_name')
                    <div class="error-message">{{ $message }}</div>
            @enderror
    </div>

    <button type="submit" class="submit-button">更新する</button>
</form>



</div>
@endsection


