<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artisanコマンド実行</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .output {
            margin-top: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            display: none;
        }

        .output.show {
            display: block;
        }

        .output pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #333;
            font-size: 12px;
            font-family: 'Courier New', monospace;
        }

        .alert {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Artisanコマンド実行</h1>

        <div id="alert"></div>

        <form id="commandForm">
            @csrf
            <div class="form-group">
                <label for="command">コマンド</label>
                <select id="command" name="command" required>
                    <option value="">-- コマンドを選択 --</option>
                    <option value="test:run">test:run</option>
                    <option value="greet:user">greet:user</option>
                </select>
            </div>

            <div class="form-group">
                <label for="name">名前</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="例: 太郎"
                    required
                >
            </div>

            <button type="submit" id="submitBtn">
                実行
            </button>
        </form>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p style="margin-top: 10px; color: #666;">実行中...</p>
        </div>

        <div class="output" id="output">
            <h3 style="margin-bottom: 10px; color: #333;">実行結果:</h3>
            <pre id="outputContent"></pre>
        </div>
    </div>

    <script>
        const form = document.getElementById('commandForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const output = document.getElementById('output');
        const outputContent = document.getElementById('outputContent');
        const alertDiv = document.getElementById('alert');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const command = formData.get('command');
            const name = formData.get('name');

            // UI状態を更新
            submitBtn.disabled = true;
            loading.classList.add('show');
            output.classList.remove('show');
            alertDiv.innerHTML = '';

            try {
                const response = await fetch('{{ route("command.run") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // 成功時
                    alertDiv.innerHTML = `<div class="alert alert-success">✅ ${data.message}</div>`;
                    outputContent.textContent = data.output;
                    output.classList.add('show');
                } else {
                    // エラー時
                    alertDiv.innerHTML = `<div class="alert alert-error">❌ ${data.message}</div>`;
                }
            } catch (error) {
                alertDiv.innerHTML = `<div class="alert alert-error">❌ エラーが発生しました: ${error.message}</div>`;
            } finally {
                submitBtn.disabled = false;
                loading.classList.remove('show');
            }
        });
    </script>
</body>
</html>
