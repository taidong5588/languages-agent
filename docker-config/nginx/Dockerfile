# 公式のNginx安定版イメージをベースとして使用
FROM nginx:latest

# タイムゾーン設定（オプション）
ENV TZ=Asia/Tokyo

# コンテナ起動時の作業ディレクトリ
WORKDIR /etc/nginx

# このDockerfileは、将来的なカスタマイズのために用意されています。
# 例えば、特定のツール（certbotなど）をインストールしたり、
# 静的コンテンツをイメージ内に直接コピーしたりする場合に、ここへ追記します。
#
# --- カスタマイズ例 ---
# RUN apt-get update && apt-get install -y some-package
# COPY ./my-static-assets /usr/share/nginx/html/static
#
# 現状の構成では、設定ファイルはdocker-compose.yml経由でマウントされるため、
# このDockerfile内での特別な設定は不要です。
