# ==========================
# Stage 1: Get composer binary
# ==========================
FROM composer:latest AS composer

# ==========================
# Stage 2: PHP Application
# ==========================
FROM php:8.2-fpm

WORKDIR /var/www

# 必要なパッケージをインストール
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mysqli \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/*

# Composer を Stage1 からコピー
COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# Composer 設定（Laravel installer 用）
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/composer
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Laravel インストーラをグローバルにインストール
RUN composer global require laravel/installer && composer clear-cache

# UID/GID 1000 の www ユーザー作成
RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www

# 権限調整（任意）
RUN chown -R www:www /var/www

USER www

EXPOSE 9000
CMD ["php-fpm"]
