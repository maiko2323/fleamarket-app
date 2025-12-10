@extends('layouts.app')

@section('title', 'メール認証誘導画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
<div class="verify-container">
    <p>登録していただいたメールアドレスに認証メールを送付しました。<br>
    メール認証を完了してください。</p>

    <a href="#" class="verify-button">認証はこちらから</a>
    <a href="#" class="resend-link">認証メールを再送する</a>
</div>
@endsection