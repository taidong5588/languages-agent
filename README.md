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
.
├── docker-compose.yml                # Docker Compose 本体。全サービス定義（Nginx, PHP, DB, phpMyAdmin など）
├── docker-config                     # 各種サービスの個別設定フォルダ
│   ├── db                            # MariaDB 関連の設定
│   │   ├── conf
│   │   │   └── my.conf              # MariaDB のカスタム設定ファイル（文字コード・ログ・タイムゾーンなど）
│   │   └── sql
│   │       └── agent.sql            # MariaDB 初期データ投入用SQLスクリプト（DB初回起動時に実行される）
│   ├── errors                        # 各サービスのログ出力先
│   │   ├── error.log                # Nginxなど共通のエラーログファイル
│   │   └── php_errors.log          # PHP用のエラーログファイル（php-fpm経由のログが出力される）
│   ├── nginx                         # Nginx（Webサーバ）の設定
│   │   ├── default.conf            # Nginx のバーチャルホスト設定（Laravel へのルーティング等）
│   │   └── Dockerfile              # Nginx コンテナのビルド用（必要に応じてカスタマイズ）
│   └── php                           # PHP（Laravel 実行エンジン）設定
│       ├── Dockerfile              # PHP-FPM の Dockerfile（composer含む Laravel対応環境を構築）
│       └── php.ini                 # PHP 設定ファイル（タイムゾーンや拡張設定など）
├── my-app                            # Laravel アプリ本体のコード格納ディレクトリ（複数アプリ対応可）
│   ├── agent                       # Laravel アプリ1（例：不動産エージェント向け機能など）
│   ├── languages                   # Laravel アプリ2（例：多言語サポート機能など）
│   └── public
│       └── index.php              # Laravel のエントリーポイント（Nginx経由で呼び出される）
└── README.md                        # プロジェクトの説明や構築手順などのメモ


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

🔍 サービス名が不明なときの確認方法

以下を実行すると、サービス一覧が表示されます：

docker compose ps
出力例：

NAME                COMMAND                  SERVICE             STATUS
my-php-app          "docker-php-entrypoi…"   app                 running
my-db               "docker-entrypoint.s…"   db                  running
この場合、app がサービス名なので docker compose exec app php -v を使います。

🧼 よくある組み合わせ例（完全クリーン再構築）

docker-compose down --volumes --remove-orphans
system prune -a --volumes -f
docker builder prune -a -f
docker-compose up --build -d

############################
Laravel 11のインストール
############################
# アプリコンテナに入るコマンド
docker compose exec php bash

www@d3b77baaf809:/var/www$ ls
agent  languages  public

# Laravelインストールコマンド
laravel new agent

   _                               _
  | |                             | |
  | |     __ _ _ __ __ ___   _____| |
  | |    / _` |  __/ _` \ \ / / _ \ |
  | |___| (_| | | | (_| |\ V /  __/ |
  |______\__,_|_|  \__,_| \_/ \___|_|


 ┌ Which starter kit would you like to install? ────────────────┐
 │ Livewire                                                     │
 └──────────────────────────────────────────────────────────────┘

 ┌ Which authentication provider do you prefer? ────────────────┐
 │ Laravel's built-in authentication                            │
 └──────────────────────────────────────────────────────────────┘

 ┌ Would you like to use Laravel Volt? ─────────────────────────┐
 │ ● Yes / ○ No                                                 │
 └──────────────────────────────────────────────────────────────┘

➜ cd agent
➜ composer run dev

php artisan migrate
