@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="profile-container">
    <h2>プロフィール設定</h2>
    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
        @csrf
    <div class="form-group image-row">
        <div class="image-preview">
        <img src="{{ asset('images/sample-profile.png') }}" alt="プロフィール画像">
        </div>
        <div class="form-group image-upload">
        <input type="file" name="image" id="image" class="hidden-file">
        <label for="image" class="custom-file-label">画像を選択する</label>
        </div>
    </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code"  value="{{ old('postal_code', $user->profile->post_code ?? '') }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $user->profile->address ?? '') }}">

        </div>
        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building', $user->profile->building_name ?? '') }}">
        </div>
        <button type="submit">更新する</button>
    </form>
</div>
@endsection