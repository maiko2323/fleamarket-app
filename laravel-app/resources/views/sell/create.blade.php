@extends('layouts.app')

@section('title', '商品出品画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-page">
    <h2>商品の出品</h2>
    <div class="image-upload-area">
        <div class="dashed-box">
            <button type="button" class="select-image-button">画像を選択する</button>
        </div>
    </div>


    <h3>商品の詳細</h3>
    <div class="form-group">
        <label>カテゴリー</label>
        <div class="category-buttons">
            <button type="button">ファッション</button>
            <button type="button">家電</button>
            <button type="button">インテリア</button>
            <button type="button">レディース</button>
            <button type="button">メンズ</button>
            <button type="button">コスメ</button>
            <button type="button">本</button>
            <button type="button">ゲーム</button>
            <button type="button">スポーツ</button>
            <button type="button">キッチン</button>
            <button type="button">ハンドメイド</button>
            <button type="button">アクセサリー</button>
            <button type="button">おもちゃ</button>
            <button type="button">ベビー・キッズ</button>
        </div>
    </div>

    <div class="form-group">
        <label>商品の状態</label>
        <select name="condition">
            <option value="">選択してください</option>
            <option value="良好">良好</option>
            <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり">やや傷や汚れあり</option>
            <option value="状態が悪い">状態が悪い</option>
        </select>
    </div>

    <h3>商品名と説明</h3>
    <div class="form-main">
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name">

            <label>ブランド名</label>
            <input type="text" name="brand">

            <label>商品の説明</label>
            <textarea name="description"></textarea>

            <label>販売価格</label>
            <input type="number" name="price" placeholder="¥">
    </div>

    <button type="submit" class="submit-button">出品する</button>
</div>

@endsection