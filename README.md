# Laravel-Social-Entry Demo
這是一個 [a2workspace/laravel-social-entry](https://github.com/A2Workspace/laravel-social-entry) 的範例專案。包含多使用者模型 (`App\Models\User`和`App\Models\Admin`)，你可以使用傳統登入並取得 JWT 或第三方平台授權登入。

注意: 當前專案不包含使用者端頁面，你可以使用 [a2workspace/nuxt-social-entry](https://github.com/A2Workspace/nuxt-social-entry) 中的範例作為測試。

Here is a demo project for [a2workspace/laravel-social-entry](https://github.com/A2Workspace/laravel-social-entry) and includes multiple user authentication for `App\Models\User` and `App\Models\Admin`. You can use JWT or third-party authorization to authenticate the users.

Notice: This project is not included client-side pages.

## Installation | 安裝

Clone demo project from github:
```bash
git clone https://github.com/A2Workspace/laravel-social-entry-demo.git
cd laravel-social-entry-demo
composer install
```

Make '.env' file from '.env.example':
```
cp .env.example .env
```

Generate app key:
```bash
php artisan key:generate
php artisan jwt:secret
```

Setup database and create testing users.
```
php artisan migrate --seed
```

Run server:
```
php artisan serve
```
