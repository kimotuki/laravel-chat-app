# Laravel Chat App（ハンズオン用）

**Laravel** と [Prism](https://prismphp.com/) を使って、Anthropic Claude と対話できるシンプルなチャットアプリです。

> **Note:** 本リポジトリは **ハンズオン用のテストアプリ** です。学習・検証目的で作成しており、本番運用は想定していません。

## 機能

- ブラウザ上のチャット画面から Claude にメッセージを送信し、返信を表示
- Prism 経由で Anthropic API（`claude-haiku-4-5`）を呼び出し
- レート制限（1分あたり10リクエスト）による API キー保護
- エラー詳細はログにのみ記録し、画面には固定メッセージを表示

## 技術スタック

| 項目 | 内容 |
|------|------|
| フレームワーク | Laravel ^13.8 |
| 言語 | PHP ^8.3 |
| LLM 連携 | prism-php/prism ^0.100 + Anthropic Claude |
| データベース | SQLite |
| 静的解析 | PHPStan / Larastan（レベル10） |

## セットアップ

### 1. 依存パッケージのインストール

```bash
composer install
```

### 2. 環境変数ファイルの作成

このプロジェクトは、API キーなどの秘密情報をリポジトリ外へ置くため、環境変数ファイルを **ホームディレクトリ直下の `.env.laravel-chat-app`** から読み込みます（詳細は後述）。

```bash
cp .env.example ~/.env.laravel-chat-app
chmod 600 ~/.env.laravel-chat-app
```

`~/.env.laravel-chat-app` に最低限以下を設定してください:

```dotenv
ANTHROPIC_API_KEY=（Anthropic Console で発行した API キー）
PRISM_MODEL=claude-haiku-4-5-20251001
APP_KEY=（php artisan key:generate で生成）
```

### 3. データベースの準備

```bash
touch database/database.sqlite
php artisan migrate
```

### 4. 起動

```bash
php artisan serve
```

ブラウザで http://127.0.0.1:8000/chat を開くとチャット画面が表示されます。

## 環境変数ファイルの参照先について

環境変数ファイルの参照先は `bootstrap/app.php` でカスタマイズしており、以下の優先順で決まります:

1. **`APP_ENV_PATH` 環境変数で指定されたディレクトリ**
   ```bash
   APP_ENV_PATH=/path/to/secrets php artisan serve
   ```
2. **ホームディレクトリ（`$HOME`）** — 直下に `.env.laravel-chat-app` が存在する場合のみ
3. **どちらも該当しない場合** — Laravel 標準どおりプロジェクト直下の `.env`

ファイル名は 1・2 の場合 `.env.laravel-chat-app`、3 の場合は `.env` です。

> **Note:** Docker・CI など `HOME` が設定されていない環境でも起動できます。その場合は `APP_ENV_PATH` で参照先を明示するか、プロジェクト直下に `.env` を配置してください。

## テスト・静的解析

```bash
# テスト（個人の API キーは使われません — phpunit.xml でダミー値に上書き）
php artisan test

# 静的解析（PHPStan レベル10）
vendor/bin/phpstan analyse --memory-limit=1G
```

## ディレクトリ構成（主要ファイル）

```
app/Http/Controllers/ChatController.php  # チャット画面表示 + LLM 呼び出し
routes/web.php                           # ルート定義（/chat、レート制限付き）
resources/views/chat.blade.php           # チャット UI
config/services.php                      # Prism モデル設定（PRISM_MODEL）
bootstrap/app.php                        # 環境変数ファイル参照先のカスタマイズ
phpstan.neon                             # 静的解析設定
```

## ライセンス

MIT License
