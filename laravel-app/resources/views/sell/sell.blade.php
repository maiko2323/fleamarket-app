@extends('layouts.app')

@section('title', '商品出品画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="sell-page">
    <h2>商品の出品</h2>
    <div class="image-upload-area">
        <div class="dashed-box">
            <button type="button" class="select-image-button">画像を選択する
            </button>
            <input type="file" name="item_img" id="item_img" accept="image/*" class="file-input">
            <img id="preview" class="preview-image">
        </div>
        <img id="preview" class="preview-image">
    </div>

    <h3>商品の詳細</h3>
    <div class="form-group">
        <label>カテゴリー</label>
        <div class="category-buttons">
            @foreach($categories as $category)
            <input type="checkbox" id="category{{ $category->id }}"
                name="categories[]"
                value="{{ $category->id }}">
            <label for="category{{ $category->id }}">{{ $category->name }}</label>
            @endforeach
        </div>
    </div>

    <div class="form-group">
        <label>商品の状態</label>
        <select name="condition">
            <option value="">選択してください</option>
            <option value="1">良好</option>
            <option value="2">目立った傷や汚れなし</option>
            <option value="3">やや傷や汚れあり</option>
            <option value="4">状態が悪い</option>
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
</form>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const button = document.querySelector('.select-image-button');
    const fileInput = document.getElementById('item_img');

    button.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);

        }
    });
});
</script>
@endsection
