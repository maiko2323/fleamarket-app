@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="user-profile">
        <img src="{{ asset($profile->profile_img) }}" alt="プロフィール画像">
        <h2>{{ $user->name }}</h2>
        <a href="{{ route('mypage.profile') }}" class="edit-button">プロフィールを編集</a>
    </div>

    <div class="tab-buttons">
        <a href="{{ route('mypage', ['page' => 'sell']) }}"
            class="tab-button {{ request('page') === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>
        <a href="{{ route('mypage', ['page' => 'buy']) }}"
            class="tab-button {{ request('page') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>


    @if ($page === 'sell')
    <div class="tab-content listed active">
        <div class="item-grid">
            @foreach ($items as $item)
                <a href="{{ route('item.show', ['item_id' => $item->id]) }}" class="item-card">
                    <img src="{{ $item->item_img }}" alt="{{ $item->name }}">
                    <p>{{ $item->name }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    @if ($page === 'buy')
    <div class="tab-content purchased active">
        <div class="item-grid">
            @foreach ($items as $item)
                @if ($item->item)
                    <a href="{{ route('item.show', ['item_id' => $item->item->id]) }}" class="item-card">
                        <img src="{{ $item->item->item_img }}" alt="{{ $item->item->name }}">
                        <p>{{ $item->item->name }}</p>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

