# ==========================
# Stage 1: Composerバイナリの取得
# ==========================
# Composerの公式イメージをベースとして使用
FROM composer:latest AS composer

# ==========================
# Stage 2: PHPアプリケーション環境の構築
# ==========================
# PHP-FPMの公式イメージ (PHP 8.2) をベースとして使用
FROM php:8.2-fpm

# コンテナ内の作業ディレクトリを設定
WORKDIR /var/www

# 必要なシステムパッケージをインストール
# --no-install-recommends: 推奨パッケージのインストールをスキップし、イメージサイズを削減
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    # 必要なPHP拡張機能をインストール
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    zip \
    # APTキャッシュをクリーンアップしてイメージサイズを削減
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# ComposerをStage1からコピー
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Composerの設定
# COMPOSER_ALLOW_SUPERUSER: rootユーザーでのComposer実行を許可
# COMPOSER_HOME: Composerのホームディレクトリ (キャッシュなど)
# PATH: ComposerのグローバルインストールパスをPATHに追加
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Laravelインストーラをグローバルにインストールし、Composerキャッシュをクリア
RUN composer global require laravel/installer && composer clear-cache

# UID/GID 1000 の www ユーザーとグループを作成
# アプリケーションの実行ユーザーとして使用し、ホストのユーザーIDと合わせることで権限問題を軽減
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# アプリケーションディレクトリの所有者を www ユーザーに変更 (任意だが推奨)
RUN chown -R www:www /var/www

# 以降のコマンドは www ユーザーとして実行
USER www

# PHP-FPMがリッスンするポートを公開
EXPOSE 9000
# コンテナ起動時に実行されるコマンド
CMD ["php-fpm"]