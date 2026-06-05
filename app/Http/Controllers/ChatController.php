<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Throwable;

class ChatController extends Controller
{
    /**
     * チャット画面を表示する。
     */
    public function index(): View
    {
        return view('chat');
    }

    /**
     * ユーザーのメッセージを受け取り、LLM の返信を返す。
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ]);

        $message = $request->string('message')->toString();

        $model = config('services.prism.model');
        if (! is_string($model) || $model === '') {
            $model = 'claude-3-5-haiku-latest';
        }

        try {
            $response = Prism::text()
                ->using(Provider::Anthropic, $model)
                ->withSystemPrompt('あなたは親切で丁寧な日本語のアシスタントです。簡潔に回答してください。')
                ->withPrompt($message)
                ->asText();

            return response()->json([
                'reply' => $response->text,
            ]);
        } catch (Throwable $e) {
            // エラー詳細はログ（storage/logs/laravel.log）にのみ記録する
            report($e);

            return response()->json([
                'error' => 'LLM への問い合わせに失敗しました。管理者にご確認ください。',
            ], 500);
        }
    }
}
