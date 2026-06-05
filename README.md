<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 環境変数ファイルの参照先について

このプロジェクトは、APIキーなどの秘密情報をリポジトリ外に置くため、環境変数ファイル（`.env`）を**プロジェクト直下ではなく外部ディレクトリ**から読み込むようカスタマイズしています（`bootstrap/app.php` 参照）。

参照先は以下の優先順で決まります:

1. **`APP_ENV_PATH` 環境変数で指定されたディレクトリ**
   ```bash
   # 例: 任意のディレクトリに置いた env ファイルを使う
   APP_ENV_PATH=/path/to/secrets php artisan serve
   ```
2. **ホームディレクトリ（`$HOME`）** — 直下に `.env.laravel-chat-app` が存在する場合のみ
   ```bash
   # 例: 初回セットアップ
   cp .env.example ~/.env.laravel-chat-app
   # ~/.env.laravel-chat-app に ANTHROPIC_API_KEY 等を設定し、権限を絞る
   chmod 600 ~/.env.laravel-chat-app
   ```
3. **どちらも該当しない場合** — Laravel 標準どおりプロジェクト直下の `.env` を読み込みます

ファイル名はいずれの場合も `.env.laravel-chat-app` です（3の標準フォールバック時のみ `.env`）。

> **Note:** Docker・CI など `HOME` が設定されていない環境でも起動可能です。その場合は `APP_ENV_PATH` で参照先を明示するか、プロジェクト直下に `.env` を配置してください。なお、テスト実行時（phpunit）は `phpunit.xml` で `APP_KEY` と `ANTHROPIC_API_KEY` をダミー値に上書きするため、個人の実APIキーが使われることはありません。

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
