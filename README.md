# Flea Market

フリマアプリ（出品 / 購入 / お気に入り / コメント / 検索 / メール認証）を実装しました。

---

## 環境構築

### 起動

```bash
docker compose up -d
```

### Laravelセットアップ

```bash
docker compose exec php bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

---

## 使用技術

- Laravel
- MySQL
- Laravel Fortify（認証）
- MailHog（メール確認）

---

## 機能一覧

- 会員登録 / ログイン / ログアウト
- メール認証（認証メール送信、再送、認証完了後の遷移）
- 商品一覧（おすすめ / マイリスト切替、検索）
- 商品詳細（カテゴリ、コメント、いいね数）
- 出品（画像、カテゴリ複数選択、状態、価格）
- 購入（支払い方法選択、購入後は sold 表示）
- お気に入り登録 / 解除
- コメント投稿 / 削除
- プロフィール表示（購入 / 出品タブ切替）
- プロフィール編集

---

## 画面・URL

- トップ（商品一覧）: `/`
- マイリスト（トップ切替）: `/?tab=mylist`
- 会員登録: `/register`
- ログイン: `/login`
- 商品詳細: `/items/{item}`
- 出品: `/items/create`
- 購入: `/items/{item}/purchase`
- プロフィール: `/profile?tab=buy` / `/profile?tab=sell`
- プロフィール編集: `/profile/edit`

---

## テスト

```bash
php artisan test
```

全テストが通ることを確認済みです。（56 tests passed）

---

## ER図

※ER図は `docs/erd.png`に配置しています。

---

## メール認証の確認

MailHogで認証メールを確認できます。

- MailHog: http://localhost:8025

---

## 💻 作者

小牧智沙都