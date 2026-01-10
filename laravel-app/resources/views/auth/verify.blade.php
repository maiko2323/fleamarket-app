@extends('layouts.app')

@section('title', 'メール認証誘導画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール認証を完了してください。</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit" class="verify-button">
        認証はこちらから
        </button>

    </form>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">認証メールを再送する</button>
    </form>
</div>
@endsection