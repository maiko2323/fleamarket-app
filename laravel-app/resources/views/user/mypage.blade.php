@extends('layouts.app')

@section('title', 'マイページ')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="user-profile">
        <img src="{{ $profile->image_url ?? asset('images/  default-icon.png') }}"
            alt="プロフィール画像" class="profile-icon">

        <h1>{{ $user->name }}</h1>

        <a href="{{ route('mypage.profile.edit') }}" class="edit-button">プロフィールを編集</a>
    </div>

    <div class="tab-buttons">
        <a href="{{ route('mypage.show', ['page' => 'sell']) }}"
            class="tab-button {{ request('page') === 'sell' ? 'active' : '' }}">
            出品した商品
        </a>

        <a href="{{ route('mypage.show', ['page' => 'buy']) }}"
            class="tab-button {{ request('page') === 'buy' ? 'active' : '' }}">
            購入した商品
        </a>
    </div>


    @if ($page === 'sell')
        <div class="tab-content listed active">
            <ul class="item-grid">
                @foreach ($items as $item)
                    <li class="item-card">
                        <a href="{{ route('item.show', ['item_id' => $item->id]) }}">
                            <div class="item-image">
                                <img src="{{ $item->item_img }}" alt="{{ $item->name }}">

                                @if($item->soldItem)
                                    <img src="{{ asset('images/soldout.png') }}"
                                    alt="soldout"
                                    class="soldout-badge">
                                @endif
                            </div>

                            <p>{{ $item->name }}</p>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($page === 'buy')
        <div class="tab-content purchased active">
            <ul class="item-grid">
                @foreach ($items as $sold)
                    @if ($sold->item)
                        <li class="item-card">
                            <a href="{{ route('item.show', ['item_id' => $sold->item->id]) }}">
                                <div class="item-image">
                                    <img src="{{ $sold->item->item_img }}" alt="{{ $sold->item->name }}">
                                    @if($sold->item->soldItem)
                                        <img src="{{ asset('images/soldout.png') }}"
                                        alt="soldout"
                                        class="soldout-badge">
                                    @endif
                                </div>

                                <p>{{ $sold->item->name }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif

</div>
@endsection

