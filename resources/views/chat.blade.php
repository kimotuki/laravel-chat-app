<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Prism チャットサンプル</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Hiragino Sans", "Noto Sans JP", sans-serif;
            background: #f5f5f7;
            color: #1d1d1f;
        }
        .container {
            max-width: 720px;
            margin: 0 auto;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            padding: 16px 20px;
            background: #fff;
            border-bottom: 1px solid #e5e5ea;
            font-weight: 600;
            font-size: 18px;
        }
        header small { font-weight: 400; color: #8e8e93; margin-left: 8px; font-size: 13px; }
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .msg { max-width: 80%; padding: 10px 14px; border-radius: 16px; line-height: 1.5; white-space: pre-wrap; word-break: break-word; }
        .msg.user { align-self: flex-end; background: #007aff; color: #fff; border-bottom-right-radius: 4px; }
        .msg.assistant { align-self: flex-start; background: #fff; border: 1px solid #e5e5ea; border-bottom-left-radius: 4px; }
        .msg.error { align-self: center; background: #ffe5e5; color: #c00; border: 1px solid #ffb3b3; font-size: 14px; }
        .msg.typing { color: #8e8e93; font-style: italic; }
        form {
            display: flex;
            gap: 8px;
            padding: 12px 20px;
            background: #fff;
            border-top: 1px solid #e5e5ea;
        }
        #input {
            flex: 1;
            padding: 12px 14px;
            border: 1px solid #d1d1d6;
            border-radius: 20px;
            font-size: 15px;
            resize: none;
            outline: none;
            font-family: inherit;
        }
        #input:focus { border-color: #007aff; }
        button {
            padding: 0 20px;
            border: none;
            border-radius: 20px;
            background: #007aff;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        button:disabled { background: #b0c4de; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="container">
        <header>Prism チャットサンプル <small>Anthropic Claude × Laravel</small></header>
        <div id="messages">
            <div class="msg assistant">こんにちは！何でも聞いてください。</div>
        </div>
        <form id="chat-form">
            <textarea id="input" rows="1" placeholder="メッセージを入力..." autocomplete="off"></textarea>
            <button type="submit" id="send-btn">送信</button>
        </form>
    </div>

    <script>
        const messagesEl = document.getElementById('messages');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('input');
        const sendBtn = document.getElementById('send-btn');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function addMessage(text, type) {
            const div = document.createElement('div');
            div.className = 'msg ' + type;
            div.textContent = text;
            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
            return div;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            addMessage(message, 'user');
            input.value = '';
            sendBtn.disabled = true;
            const typing = addMessage('入力中...', 'assistant typing');

            try {
                const res = await fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message }),
                });
                const data = await res.json();
                typing.remove();

                if (res.ok) {
                    addMessage(data.reply, 'assistant');
                } else {
                    addMessage(data.error || 'エラーが発生しました', 'error');
                }
            } catch (err) {
                typing.remove();
                addMessage('通信エラー: ' + err.message, 'error');
            } finally {
                sendBtn.disabled = false;
                input.focus();
            }
        });

        // Enterで送信、Shift+Enterで改行
        // IME（日本語変換）の確定Enterは送信しない
        input.addEventListener('keydown', (e) => {
            // 変換確定中のEnterは無視（isComposing / keyCode 229 でIME入力を判定）
            if (e.isComposing || e.keyCode === 229) {
                return;
            }
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.requestSubmit();
            }
        });
    </script>
</body>
</html>
