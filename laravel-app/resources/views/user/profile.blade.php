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
        <img id="preview"
            src="{{ $profile->image_url ?? asset('images/default-icon.png') }}"
            alt="プロフィール画像"
            class="profile-icon">

        </div>
        <div class="form-group image-upload">
        <input type="file" name="image" id="image" class="hidden-file">
        <label for="image" class="custom-file-label">画像を選択する</label>
        @error('image')
            <div class="error-message">{{ $message }}</div>
        @enderror

        </div>
    </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
            @error('name')
                    <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="post_code">郵便番号</label>
            <input type="text" name="post_code" id="post_code"  value="{{ old('post_code', $profile->post_code ?? '') }}">
            @error('post_code')
                    <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
                    <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="building_name">建物名</label>
            <input type="text" name="building_name" id="building_name" value="{{ old('building_name', $profile->building_name ?? '') }}">
            @error('building_name')
                    <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">更新する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection