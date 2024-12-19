## Laravel + Vue3 　 MAMP環境で学習の記録

##インストール
composer install
npm install && npm run dev
.env.exampleを .envにコピー
.envのDB関連、sanctum, sessionなどの情報を編集
php artisan key:generate

##開発中の簡易サーバー
サーバー側
php artisan serve

フロント側 (vite)
npm run dev
２つのコマンドを実行してください

トップページからユーザー登録を行いログイン後、商品管理タグより商品の登録、編集、削除ができます
