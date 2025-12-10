@extends('layouts.app')

@section('title', 'トップページ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection


@section('content')
    <div class="tabs">
        <a href="{{ route('top') }}" class="tab {{ $tab !== 'mylist' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('top', ['tab' => 'mylist']) }}" class="tab {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    @if($items->isNotEmpty())
        <div class="item-grid">
            @foreach ($items as $item)
                <a href="{{ route('item.show', ['item_id' => $item['id']]) }}" class="item-card">
                    <div class="item-image">
                        <img src="{{ $item->item_img }}" alt="{{ $item->name }}">
                    </div>
                    <div class="item-name">{{ $item['name'] }}</div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
