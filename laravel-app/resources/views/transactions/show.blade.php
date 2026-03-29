@extends('layouts.app')

@section('title', '取引チャット画面')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('content')
<div class="transaction-container">

    <div class="sidebar">
        <h2>その他の取引</h2>
        @foreach ($items as $sold)
            @if ($sold->id !== $soldItem->id)
                <a href="{{ route('transactions.show', ['soldItem' => $sold->id]) }}">
                    {{ $sold->item->name }}
                </a>
            @endif
        @endforeach
    </div>

    <div class="chat-area">
        <div class="chat-header">
            <div class="user-info">
                <img src="{{ asset($partnerImage) }}" alt="ユーザーアイコン" class="user-icon">
                <h1>「{{ $partner->name }}さん」との取引画面</h1>
            </div>

            @if (auth()->id() === $buyer->id && !$soldItem->completed_at)
                <form action="{{ route('transactions.complete', ['soldItem' => $soldItem->id]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="complete-btn">取引を完了する</button>
                </form>
            @endif
        </div>

        <div class="item-info">
            <img src="{{ asset($item->item_img) }}" class="item-img">
            <div>
                <h2 class="item-name">{{ $item->name }}</h2>
                <p class="item-price">¥{{ number_format($item->price) }}（税込）</p>
            </div>
        </div>       
        <div class="chat-list">
            @foreach ($chats as $chat)
                <div class="chat-row {{ $chat->user_id === auth()->id() ? 'my-row' : 'other-row' }}">
                    <div class="chat-block">
                        <div class="chat-user">
                            <img src="{{ asset($chat->user->profile->profile_img) }}" class="chat-icon">
                            <span>{{ $chat->user->name }}</span>
                        </div>

                        <div class="chat-bubble">
                            <p>{{ $chat->message }}</p>

                            @if ($chat->chat_img)
                                <img src="{{ asset($chat->chat_img) }}" alt="チャット画像" class="chat-img">
                            @endif
                        </div>

                        @if ($chat->user_id === auth()->id())
                            <div class="chat-buttons">
                                <button type="button" class="edit-toggle-btn" data-target="edit-form-{{ $chat->id }}">編集</button>

                                <form action="{{ route('transactions.chats.destroy', ['chat' => $chat]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">削除</button>
                                </form>
                            </div>

                            <form id="edit-form-{{ $chat->id }}" class="edit-form hidden" action="{{ route('transactions.chats.update', ['chat' => $chat]) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <textarea name="message" class="edit-form-textarea">{{ $chat->message }}</textarea>
                                <button type="submit" class="edit-form-submit">更新</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            <form class="chat-send-form" action="{{ route('transactions.messages.store', ['soldItem' => $soldItem->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="chat-send-left">
                    @error('message')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <textarea id="chat-message" name="message" placeholder="取引メッセージを記入してください">{{ old('message') }}</textarea>
                </div>

                <div class="chat-send-right">
                    @error('chat_img')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <label for="chat_img" class="file-label">画像を追加</label>
                    <input id="chat_img" type="file" name="chat_img" accept=".jpeg,.png">

                    <button type="submit" class="send-btn">
                        <img src="{{ asset('images/send_button.png') }}" alt="送信">
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if ($soldItem->completed_at)
        <dialog id="rating-modal" class="rating-modal">
            <form action="{{ route('transactions.rate', ['soldItem' => $soldItem->id]) }}" method="POST">
                @csrf

                <div class="rating-title">取引が完了しました。</div>
                <p class="rating-subtitle">今回の取引相手はどうでしたか？</p>

                <div class="rating-stars">
                    @for ($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                        <label for="star{{ $i }}">★</label>
                    @endfor
                </div>

                <div class="rating-actions">
                    <button type="submit" class="rating-submit-btn">送信する</button>
                </div>
            </form>
        </dialog>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const textarea = document.getElementById('chat-message');

    // --- チャット入力の保存・復元 ---
    if (textarea) {
        textarea.addEventListener('input', () => {
            localStorage.setItem('chat_message', textarea.value);
        });

        const chatSent = @json(session('chat_sent'));

        if (chatSent) {
            localStorage.removeItem('chat_message');
            textarea.value = '';
        } else {
            const saved = localStorage.getItem('chat_message');
            if (saved) {
                textarea.value = saved;
            }
        }
    }

    // --- 編集フォームの開閉 ---
    document.querySelectorAll('.edit-toggle-btn').forEach(button => {
        button.addEventListener('click', () => {
            const currentBlock = button.closest('.chat-block');
            const targetForm = currentBlock ? currentBlock.querySelector('.edit-form') : null;

            document.querySelectorAll('.edit-form').forEach(form => {
                if (form !== targetForm) {
                    form.classList.add('hidden');
                }
            });

            if (targetForm) {
                targetForm.classList.toggle('hidden');
            }
        });
    });

    // --- モーダル表示 ---
    const modal = document.getElementById('rating-modal');
    const shouldOpen =
        @json(session('open_rating_modal')) ||
        @json($shouldOpenRatingModal ?? false);

    if (modal && shouldOpen) {
        modal.showModal();
    }
});
</script>

@endsection

