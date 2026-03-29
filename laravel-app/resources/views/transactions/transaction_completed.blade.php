<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>取引完了のお知らせ</title>
</head>
<body>
    <p>{{ $soldItem->item->user->name }} 様</p>

    <p>出品されていた商品のお取引が完了しました。</p>

    <p>【商品名】{{ $soldItem->item->name }}</p>
    <p>【購入者】{{ $soldItem->buyer->name }} 様</p>

    <p>マイページより取引内容をご確認ください。</p>

    <p>よろしくお願いいたします。</p>
</body>
</html>