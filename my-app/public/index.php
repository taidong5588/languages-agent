<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docker PHP Environment</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #3a3a3a;
            background-color: #f8f9fa;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        h1,
        h2 {
            color: #2c3e50;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        h1 {
            font-size: 2.5rem;
        }

        .status {
            padding: 1rem;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 1rem;
            border: 1px solid transparent;
        }

        .status.success {
            background-color: #e6ffed;
            color: #2f6f44;
            border-color: #b8e9c5;
        }

        .status.error {
            background-color: #fff0f1;
            color: #a82a38;
            border-color: #f5c6cb;
        }

        pre {
            background-color: #e9ecef;
            padding: 1rem;
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: #495057;
        }

        code {
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚀 Docker for PHP Developers</h1>
        <p>このページが表示されていれば、NginxとPHP-FPMの連携は成功です。</p>

        <h2>データベース接続確認</h2>
        <?php
        $db_host = getenv('DB_HOST');
        $db_name = getenv('DB_DATABASE');
        $db_user = getenv('DB_USERNAME');
        $db_pass = getenv('DB_PASSWORD');

        try {
            // mysqliでデータベースに接続
            // オブジェクト指向スタイルでのエラーハンドリング
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

            echo '<div class="status success">✅ データベースへの接続に成功しました！<br>MariaDB Version: ' . $conn->server_info . '</div>';
            $conn->close();

        } catch (mysqli_sql_exception $e) {
            echo '<div class="status error">❌ データベースへの接続に失敗しました。</div>';
            echo '<p><code>docker-config/.env</code> の設定内容が正しいか、またDBコンテナが正常に起動しているか確認してください。</p>';
            echo '<pre><code>' . htmlspecialchars($e->getMessage()) . '</code></pre>';
        }
        ?>

        <h2>PHP環境情報</h2>
        <?php
        echo "<p>PHP Version: <code>" . phpversion() . "</code></p>";
        ?>
    </div>
</body>

</html>