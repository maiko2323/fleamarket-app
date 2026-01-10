@extends('layouts.app')

@section('title', 'ログイン')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
    <div class="login-container">
        <h1>ログイン</h1>
        <form method="POST" action="/login">
            @csrf

            @if ($errors->has('login_error'))
                <div class="error-message">{{ $errors->first('login_error') }}</div>
            @endif

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" name="password" id="password">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">ログインする</button>

        </form>

        <div class="register-link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
@endsection
