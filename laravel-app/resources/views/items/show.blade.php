@extends('layouts.app')

@section('title', '商品詳細画面')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')
<div class="item-detail">
    <div class="item-header">
        <div class="item-image">
            <img src="{{ asset($item->item_img) }}" alt="{{ $item->name }}">

            @if($item->soldItem)
                <img src="{{ asset('images/soldout.png') }}"
                alt="売り切れ"
                class="soldout-badge">
            @endif

        </div>

        <div class="item-info">
            <h1>{{ $item->name }}</h1>
            <p class="brand">
                <span class="brand-label">ブランド名</span>
                <span class="brand-value">{{ $item->brand }}</span>
            </p>
            <p class="price">¥{{ number_format($item->price) }}（税込）</p>

            <div class="item-stats">
                <div class="stat-block">
                    <form method="POST" action="{{ route('items.like', $item->id) }}">
                        @csrf
                        <button type="submit" class="like-button">
                            @if($isLiked)
                                <img src="{{ asset('images/liked.svg') }}" alt="いいね済み" class="icon-heart">
                            @else
                                <img src="{{ asset('images/unliked.svg') }}" alt="未いいね" class="icon-heart">
                            @endif
                        </button>
                    </form>
                    <span class="stat-count like-count">{{ $item->likes_count }}</span>
                </div>

                <div class="stat-block">
                    <img src="{{ asset('images/comment.svg') }}" alt="コメント" class="icon-comment">
                    <span class="stat-count">{{ $item->comments->count() }}</span>
                </div>
            </div>

            <a href="{{ route('purchase.show', ['item_id' => $item->id]) }}" class="purchase-button">購入手続きへ</a>

            <div class="item-description">
                <h2>商品説明</h2>
                <p>{{ $item->description }}</p>
            </div>

            <div class="item-info-section">
                <h2>商品の情報</h2>
                <ul class="item-info-list">
                    <li class="item-info-row">
                        <span class="item-info-label">カテゴリー</span>
                        <span class="item-info-value item-info-chips">
                            @foreach ($item->categories as $category)
                                <span class="chip">{{ $category->name }}</span>
                            @endforeach
                        </span>
                    </li>

                    <li class="item-info-row">
                        <span class="item-info-label">商品の状態</span>
                        <span class="item-info-value">{{ $item->condition->label }}</span>
                    </li>
                </ul>
            </div>

            <div class="item-comments">
                <h2>コメント ({{ $item->comments->count() }})</h2>
                @foreach ($item->comments as $comment)
                    <div class="comment-block">
                        @php
                            $profileImage = optional($comment->user->profile)->image_url ?? asset('images/default-icon.png');
                        @endphp

                        <img src="{{ $profileImage }}" alt="ユーザーアイコン" class="comment-icon">

                        <div class="comment-content">
                            <strong>{{ $comment->user->name }}</strong><br>
                            <span>{{ $comment->content }}</span>
                        </div>
                    </div>
                @endforeach

                <h3 class="comment-form-title">商品へのコメント</h3>

                <form method="POST" action="{{ route('comment.store', ['item' => $item->id]) }}">
                    @csrf
                    <textarea name="content"></textarea>

                    @error('content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror

                    <button type="submit">コメントを送信する</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection