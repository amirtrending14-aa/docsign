<?php

namespace App\Providers;

use App\Models\Document;
use App\Observers\DocumentObserver;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

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
    public function boot()
    { Carbon::setLocale('ru');
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $userId = \Illuminate\Support\Facades\Auth::id();

                // Переименовываем в headerNotifications, чтобы не мешать основной пагинации
                $headerNotifications = \App\Models\Notification::where('user_id', $userId)
                    ->latest()
                    ->take(10)
                    ->get();

                $unreadCount = \App\Models\Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->count();

                $view->with([
                    'headerNotifications' => $headerNotifications, // Новое имя
                    'unreadCount' => $unreadCount
                ]);
            }
        });

    }
}
