# 🐳 PHP + Nginx + MariaDB + phpMyAdmin 開発環境（Docker Compose）

このリポジトリは、**Laravel 等の PHP アプリケーション開発環境**を Docker で構築するテンプレートです。PHP-FPM + Nginx + MariaDB + phpMyAdmin のスタックをシンプルにまとめています。

---

## 🧩 サービス構成

| サービス     | 内容                           | ポート          |
|--------------|--------------------------------|------------------|
| PHP (8.2)    | PHP-FPM によるアプリ実行       | -                |
| Nginx        | Webサーバ (静的 + PHP対応)     | `http://localhost:8080` |
| MariaDB      | データベース                    | `3306`           |
| phpMyAdmin   | DB GUI ツール                   | `http://localhost:8081` |

---

## 📂 ディレクトリ構成

project-root/
├── .env                       # 環境変数定義ファイル
├── docker-compose.yml        # Docker Compose によるサービス定義
├── docker-config/            # 各サービスの構成フォルダ
│   ├── db/                   # MariaDB 設定関連
│   │   ├── Dockerfile        # MariaDB カスタムビルド（必要な場合）
│   │   └── my.conf           # MariaDB 設定ファイル
│   ├── nginx/                # Nginx 設定関連
│   │   ├── Dockerfile        # Nginx カスタムビルド
│   │   └── default.conf      # 仮想ホスト、リバースプロキシ設定
│   ├── php/                  # PHP-FPM 設定関連
│   │   ├── Dockerfile        # PHP カスタムイメージ
│   │   └── php.ini           # PHP 設定ファイル
│   ├── errors/               # エラーログ出力用フォルダ
│   │   └── php_errors.log    # PHP エラーログファイル（空でOK）
│   └── .gitignore            # Gitに含めないファイルの指定
├── my-app/                   # PHP アプリケーション本体（Laravel等）
│   └── public/
│       └── index.php       　 # テスト用PHPファイル（初期）
└── README.md                 # プロジェクト説明書

---

## 🚀 セットアップ手順

```dotenv
### 1. `.env` ファイルを作成

必要な環境変数を記述します。以下を参考に `project-root/.env` を作成してください。

COMPOSE_PROJECT_NAME=myproject
NGINX_PORT=8080
PHPMYADMIN_PORT=8081
MARIADB_VERSION=10.9
MARIADB_ROOT_PASSWORD=rootのpassword
MARIADB_DATABASE=myapp_db
MARIADB_USER=myuser
MARIADB_PASSWORD=mypassword
TZ=Asia/Tokyo

2. Docker コンテナをビルド＆起動
bash
コードをコピーする
# 初回ビルド & 起動
docker-compose up --build -d

# サービスの状態確認
docker-compose ps
3. 動作確認
Webサーバー: http://localhost:8080
→ my-app/public/index.php が表示されれば成功です。

phpMyAdmin: http://localhost:8081
→ ユーザー名とパスワードは .env に記述した内容を使用します。

4. コンテナの停止と削除
Bash
docker compose down

5. コンテナの停止と削除

Bash
docker compose down
コンテナとネットワークが停止し、削除されます。データベースのデータ (db_data ボリューム) は保持されます。

コンテナ、ネットワーク、ボリュームすべてを削除するには:

Bash
docker compose down -v


6. コンテナ内でのコマンド実行

PHPコンテナ内でComposerコマンドなどを実行したい場合:

Bash
docker compose exec php bash # PHPコンテナ内でbashシェルに入る
その後、シェル内でComposerコマンド（例: composer install）を実行できます。

MariaDBコンテナに接続したい場合:

Bash
docker compose exec db bash # DBコンテナ内でbashシェルに入る
mysql -u root -p # ルートユーザーでMariaDBにログイン


💡 Laravel を導入したい場合
bash
コードをコピーする
# アプリケーションディレクトリへ移動
cd my-app

# Laravel プロジェクトを作成
docker exec -it myproject-php composer create-project laravel/laravel .

# Laravel のキーを生成
docker exec -it myproject-php php artisan key:generate

# 権限調整（Linux の場合）
sudo chown -R $USER:$USER my-app
🧪 使用技術
サービス	バージョン例
PHP	8.2 (FPM)
Nginx	最新（alpine）
MariaDB	.envで指定
phpMyAdmin	最新
Composer	最新
Laravel	任意で導入可能

🛠 よくある用途
Laravel / Slim / CakePHP などのローカル開発

チームで統一された開発環境を構築

phpMyAdmin によるデータベース管理

📌 注意点
本構成は 開発用途専用 です。本番環境にはセキュリティ強化が必要です。

phpMyAdmin は本番では 使用しないでください。

.env ファイルは Git管理しないように注意 してください。
