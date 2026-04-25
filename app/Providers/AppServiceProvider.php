<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        App::setLocale('ru');
        // На Render приложение работает за прокси, и Laravel считает,
        // что запросы приходят по http. Из-за этого @vite генерирует
        // ссылки на ассеты с http, и браузер их блокирует.
        // Принудительно включаем https, иначе фронтенд не работает.
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
