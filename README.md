## Laravel + Vue3 　 MAMP 環境で学習の記録

## ダウンロード方法

git clone

git clone https://github.com/fuku-taro/laravel_uCRM.git

もしくは zip ファイルでダウンロードしてください

## インストール方法

-   docker compose up -d --build

    app コンテナに入り

-   composer install または composer update
-   npm install

.env.example をコピーして .env ファイルを作成

.env ファイルの中の下記をご利用の環境に合わせて変更してください。

-   DB_CONNECTION=mysql
-   DB_HOST=db
-   DB_PORT=3306
-   DB_DATABASE=laravel_ucrm
-   DB_USERNAME=laravel_ucrm
-   DB_PASSWORD=password123

php artisan migrate:fresh --seed

と実行してください。(データベーステーブルとダミーデータが追加)

最後に
php artisan key:generate
と入力してキーを生成後、

-   npm run dev
    で表示確認してください。
