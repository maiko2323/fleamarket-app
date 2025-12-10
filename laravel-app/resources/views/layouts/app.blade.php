<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>
<body>
    <div class="container">
    <header class="header-bar">
        <a href="{{ route('top') }}">
        <img src="{{ asset('images/logo.svg') }}" alt="COACHTECHロゴ" class="logo">
        </a>

        @if (!Request::is('login') && !Request::is('register') && !Request::is('verify'))
        <input type="text" placeholder="なにをお探しですか？" class="search-input">

            <div class="header-links">
                @if(Auth::check())
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-button">ログアウト</button>
                    </form>
                @else
                    <a href="/login">ログイン</a>
                @endif

                <a href="/mypage">マイページ</a>

                <a href="/sell" class="post-button">出品</a>
            </div>
        @endif
    </header>

    @yield('content')
    </div>
    @yield('scripts')
</body>
</html>

