# COACHTECH フリマアプリ

Laravel × Docker で構築したフリマアプリです。ユーザー登録、ログイン、商品出品、検索、コメント、いいね機能などを備えています。

## 環境構築

### Docker ビルド

```bash
git clone <https://github.com/maiko2323/fleamarket-app.git>
cd fleamarket-app
docker-compose up -d --build
```


### Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env
環境変数を変更

※下記の「"環境変数の変更"について」を参照
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

"環境変数の変更"について.env

DB_HOST = mysql

DB_DATABASE = fleamarket

DB_USERNAME = root

DB_PASSWORD = rootpassword

MAIL_FROM_ADDRESS = no-reply@example.com
に変更


### 開発環境

[商品一覧(トップページ)](http://localhost:8083/)

[商品一覧(トップページ_マイリスト)](http://localhost:8083/?tab=mylist)

[会員登録](http://localhost:8083/register)

[ログイン](http://localhost:8083/login)

[商品詳細(例: ID=1 の商品)](http://localhost:8083/item/1)

[商品購入(例: ID=1 の商品)](http://localhost:8083/purchase/1)

[送付先住所変更画面(例: ID=1 の商品)](http://localhost:8083/purchase/address/1)

[商品出品](http://localhost:8083/sell)

[プロフィール](http://localhost:8083/mypage)

[プロフィール編集](http://localhost:8083/mypage/profile)

[プロフィール(購入した商品)](http://localhost:8083/mypage?page=buy)

[プロフィール(出品した商品)](http://localhost:8083/mypage?page=sell)

[phpMyAdmin](http://localhost:8084/)




## 仕様技術（実行環境）

PHP 8.1.33

Laravel 8.83.29

MySQL 8.0.44

nginx 1.29.3

Docker 28.3.2 / docker-compose 2.39.1

jQuery 3.7.1



## ER図

![ER図](docs/er.png)


