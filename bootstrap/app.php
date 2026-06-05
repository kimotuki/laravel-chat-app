<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

// 環境変数ファイルの参照先（優先順）:
// 1. APP_ENV_PATH で指定されたディレクトリ
// 2. $HOME（直下に .env.laravel-chat-app が存在する場合のみ）
// 3. どちらも該当しなければ Laravel 標準（プロジェクト直下の .env）
$envPath = getenv('APP_ENV_PATH') ?: getenv('HOME');
if (is_string($envPath) && $envPath !== '' && is_file($envPath.'/.env.laravel-chat-app')) {
    $app->useEnvironmentPath($envPath);
    $app->loadEnvironmentFrom('.env.laravel-chat-app');
}

return $app;
