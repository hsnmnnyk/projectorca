# PHP 8.2 + Apache イメージを使用
FROM php:8.2-apache

# 必要なパッケージとPHP拡張をインストール
RUN apt-get update && apt-get install -y     zip unzip git libzip-dev libpng-dev &&     docker-php-ext-install pdo_mysql zip gd

# Apacheの設定：.htaccessを有効化
RUN a2enmod rewrite

# ソースコードをコンテナにコピー
COPY . /var/www/html/

# 作業ディレクトリを設定
WORKDIR /var/www/html/

# アクセス権限の設定（必要に応じて）
RUN chmod -R 755 /var/www/html

# Apacheをフォアグラウンドで起動
CMD ["apache2-foreground"]
