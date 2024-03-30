#!/bin/sh
# デプロイ時にサーバー上で実行されるスクリプト

# vendor以下をインストールして autoloadファイルを最適化
/usr/local/php/8.1/bin/php ../../composer.phar install --no-dev

# 環境設定をコピー
cp .env.lolipop .env


# /configの設定情報を1ファイルにまとめておく
/usr/local/php/8.1/bin/php artisan config:cache

# route情報をまとめておく ※CLOSUREがあると使用できないのでとりまコメントアウト……
/usr/local/php/8.1/bin/php artisan route:cache

# viewキャッシュをクリア
/usr/local/php/8.1/bin/php artisan view:clear

# ログをクリア
rm -f storage/logs/*.log
