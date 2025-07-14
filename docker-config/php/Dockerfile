# ==========================
# Stage 1: Composerバイナリの取得
# ==========================
FROM composer:latest AS composer

# ==========================
# Stage 2: PHP + Node.js アプリケーション環境の構築
# ==========================
FROM php:8.2-fpm

# 作業ディレクトリ
WORKDIR /var/www

# 必要なシステムパッケージとPHP拡張をインストール
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    ca-certificates \
    gnupg \
    # PHP拡張機能をインストール
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    zip \
    # Node.jsのセットアップ https://github.com/nodesource/distributions?files=1
    && curl -fsSL https://deb.nodesource.com/setup_current.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt-get install -y --no-install-recommends nodejs \
    # 不要パッケージとキャッシュを削除
    && apt-get purge -y curl gnupg \
    && apt-get autoremove -y \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* nodesource_setup.sh

# バージョン確認（オプション。必要ならコメント解除）
# RUN node -v && npm -v

# ComposerをStage 1からコピー
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Composerの環境設定
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/composer \
    PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Laravelインストーラをグローバルにインストールし、キャッシュを削除
RUN composer global require laravel/installer && composer clear-cache

# UID/GID 1000 の www ユーザーとグループを作成
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    chown -R www:www /var/www

# wwwユーザーで実行
USER www

# PHP-FPMポートを公開
EXPOSE 9000

# コンテナ起動時のコマンド
CMD ["php-fpm"]
