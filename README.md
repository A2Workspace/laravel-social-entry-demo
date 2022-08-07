# Laravel-Social-Entry Demo

<p align="center"><img src="/.github/animation.gif" alt="Laravel-Database-Patcher demo animation"></p>

這是一個 [a2workspace/laravel-social-entry](https://github.com/A2Workspace/laravel-social-entry) 的範例專案。實作了 `client` 與 `admin` 兩個使用者的登入功能，並整合了第三方登入與 JWT API 登入。

Here is a demo project for [a2workspace/laravel-social-entry](https://github.com/A2Workspace/laravel-social-entry) and includes multiple user authentication / authorization for `App\Models\User` and `App\Models\Admin`.

## Installation | 安裝

Clone demo project from github:

```bash
git clone https://github.com/A2Workspace/laravel-social-entry-demo.git

cd laravel-social-entry-demo

composer install
```

執行自我安裝命令。該命令會自動設置運行時所需的資料庫環境，請先確定 `.env` 內的 `DATABASE=` 設定值是否正確。

```bash
php artisan setup
```

最後，運行測試伺服器，並透過瀏覽器打開 [http://localhost:8000](http://localhost:8000)。

```
php artisan serve
```

## About | 關於

### 登入認證處理

你可以參考 [routes/auth.php](routes/auth.php) 中如何註冊 [LaravelJwt](https://github.com/A2Workspace/laravel-jwt) 與 [SocialEntry](https://github.com/A2Workspace/laravel-social-entry)
的路由。

其中，透過 [assign.guard](app/Http/Middleware/AssignGuard.php) 中介層的處理，我們可以切換不同的 Auth Guard 來處理與認證使用者帳號，關於 `guards` 的設定可參考 [config/auth.php](config/auth.php#L38)

### 快速連結

- [Client Login Page](resources/js/pages/client/login.vue)
- [Admin Login Page](resources/js/pages/admin/login.vue)
- [SocialEntry Module](resources/js/mixins/SocialEntry.js)
