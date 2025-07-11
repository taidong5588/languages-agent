# docker-compose.ymlからビルド引数(ARG)としてバージョンを受け取る
ARG MARIADB_VERSION=latest
# 受け取ったバージョンを使ってベースイメージを指定
FROM mariadb:${MARIADB_VERSION}


# このDockerfileは、将来的なカスタマイズのために用意されています。
# 例えば、コンテナ初回起動時に実行したいSQLファイルを配置できます。
#
# --- カスタマイズ例 ---
# COPY ./init.sql /docker-entrypoint-initdb.d/
