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
                alt="soldout"
                class="soldout-badge">
            @endif

        </div>

        <div class="item-info">
            <h2>{{ $item->name }}</h2>
            <p class="brand">ブランド：{{ $item->brand }}</p>
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
                <h3>商品説明</h3>
                <p>{{ $item->description }}</p>
            </div>

            <div class="item-info-section">
                <h3>商品の情報</h3>
                <ul>
                    <li>カテゴリー：
                        @foreach ($item->categories as $category)
                            <span>{{ $category->name }}</span>
                        @endforeach
                    </li>
                    <li>商品の状態：{{ $item->condition->label }}</li>
                </ul>
            </div>

            <div class="item-comments">
                <h3>コメント ({{ $item->comments->count() }})</h3>
                @foreach ($item->comments as $comment)
                    <div class="comment-block">
                        <img src="{{ $comment->user->profile->image_url }}" alt="ユーザーアイコン" class="comment-icon">
                        <div class="comment-content">
                            <strong>{{ $comment->user->name }}</strong><br>
                            <span>{{ $comment->content }}</span>
                        </div>
                    </div>
                @endforeach

                <h4 class="comment-form-title">商品へのコメント</h4>

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