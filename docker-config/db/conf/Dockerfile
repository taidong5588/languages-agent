# ======================================================
# MariaDB 用 Dockerfile（バージョンを引数で受け取る）
# ======================================================
ARG MARIADB_VERSION=10.11

# 変数を使用（build.argsで必須）
FROM mariadb:${MARIADB_VERSION}       

# 以下は必要に応じて初期SQLなどを追加
# COPY ./init.sql /docker-entrypoint-initdb.d/
