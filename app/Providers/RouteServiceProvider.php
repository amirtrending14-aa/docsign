<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Путь к маршруту "home" для вашего приложения.
     * Обычно это '/dashboard' или '/home'.
     */
    public const HOME = '/dashboard';

    /**
     * Определение моделей маршрутов, шаблоны параметров, глобальные ограничения и т.д.
     */
    public function boot(): void
    {
        // 🔒 Регистрируем все Rate Limiter'ы
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * 🔒 Настройка ограничителей скорости (Rate Limiters)
     */
    protected function configureRateLimiting(): void
    {
        // 🔒 Защита от брутфорса для логина СУПЕР-АДМИНА
        RateLimiter::for('super-admin-login', function (Request $request) {
            // Максимум 3 попытки в минуту по email + IP
            return Limit::perMinute(3)->by($request->email . $request->ip());
        });

        // 🔒 Защита от брутфорса для обычного логина
        RateLimiter::for('login', function (Request $request) {
            // Максимум 5 попыток в минуту по email + IP
            return Limit::perMinute(5)->by($request->email . $request->ip());
        });

        // 🔒 Защита API от спама
        RateLimiter::for('api', function (Request $request) {
            // Максимум 60 запросов в минуту для авторизованных
            // Максимум 10 запросов в минуту для неавторизованных
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 🔒 Защита от спама форм (отправка документов, комментариев)
        RateLimiter::for('forms', function (Request $request) {
            // Максимум 10 запросов в минуту для авторизованных пользователей
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        // 🔒 Защита от спама AI-генерации (дорогая операция!)
        RateLimiter::for('ai-generation', function (Request $request) {
            // Максимум 3 генерации в минуту
            return Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        // 🔒 Защита от спама подписания документов
        RateLimiter::for('signing', function (Request $request) {
            // Максимум 5 подписаний в минуту
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}