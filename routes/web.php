<?php
        use App\Http\Controllers\Admin\AvatarController;
        use App\Http\Controllers\StrelController;
        use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
        use App\Http\Controllers\CompanyController;
        use App\Http\Controllers\DashboardController;
        use App\Http\Controllers\DocumentCommentController;
        use App\Http\Controllers\DocumentController;
        use App\Http\Controllers\DocumentLogController;
        use App\Http\Controllers\DocumentSignatureController;
        use App\Http\Controllers\DocumentWorkflowController;
        use App\Http\Controllers\DocumentVersionController;
        use App\Http\Controllers\MessageController;
        use App\Http\Controllers\ProfileController;
        use App\Http\Controllers\SearchController;
        use App\Http\Controllers\SettingsController;
        use App\Http\Controllers\SuperAdminController;
        use App\Http\Controllers\UserController;
        use App\Http\Controllers\NotificationController;
        use App\Http\Controllers\AnalysisController;
        use App\Http\Controllers\AIController;
        use Illuminate\Support\Facades\Route;
        use Illuminate\Support\Facades\DB;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;

        // ===== ПУБЛИЧНЫЕ РОУТЫ =====
        Route::get('/', function () {
            return view('layouts.site');
        })->name('site.home');

        Route::get('/site', function () {
            return view('layouts.site');
        })->name('site.main');

        // ===== АУТЕНТИФИКАЦИЯ (login, register, forgot-password, reset-password) =====
        require __DIR__ . '/auth.php';

        // ===== ЛОКАЛЬНЫЙ РОУТ ДЛЯ ТЕСТИРОВАНИЯ =====
        if (app()->environment('local')) {
            Route::post('/login-as', function (Request $request) {
                Auth::loginUsingId($request->user_id);
                return back()->with('success', 'Switched to users: ' . Auth::user()->name);
            })->name('login.as');
        }

        // ===== АВТОРИЗОВАННЫЕ ПОЛЬЗОВАТЕЛИ =====
        Route::middleware(['auth', 'last.seen'])->group(function () {

            // Дашборд
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
            Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
                ->name('dashboard.chart-data');

            // Профиль
            Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            // Настройки
            Route::get('/setting', function () {
                return view('settings.index');
            })->name('settings');
            Route::post('/settings/signature', [ProfileController::class, 'updateSignature'])->name('settings.signature.update');
            Route::post('/settings/general', [ProfileController::class, 'updateGeneral'])->name('settings.general.update');
            Route::put('/settings/edi', [SettingsController::class, 'update'])->name('settings.update');

            // Документы - специальные действия
            Route::get('/documents/{id}/download-pdf', [DocumentController::class, 'downloadPdf'])->name('documents.downloadPdf');
            Route::get('/documents/{id}/download-word', [DocumentController::class, 'downloadWord'])->name('documents.downloadWord');
            Route::post('/documents/ai-process', [DocumentController::class, 'storeFromPdf'])->name('documents.ai-process');
            Route::post('/documents/{id}/sign', [DocumentController::class, 'sign'])->name('documents.sign');
            Route::post('/documents/{id}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

            // Ресурсные роуты
            Route::resource('documents', DocumentController::class);
            Route::resource('users', UserController::class);
            Route::resource('signatures', DocumentSignatureController::class);
            Route::resource('versions', DocumentVersionController::class);
            Route::resource('logs', DocumentLogController::class);
            Route::resource('workflow', DocumentWorkflowController::class);
            Route::post('/logs/clear', [DocumentLogController::class, 'clear'])->name('logs.clear');

            // Поиск
            Route::get('/search', [SearchController::class, 'index'])->name('search');
            Route::get('/api/users/search', function (Request $request) {
                $user = \App\Models\User::where('email', $request->email)->first();
                return response()->json([
                    'exists' => !!$user,
                    'name' => $user ? $user->name : null
                ]);
            })->name('users.search_api');

            // Комментарии
            Route::post('/comments', [DocumentCommentController::class, 'store'])->name('comments.store');
            Route::get('/documents/{documentId}/comments', [DocumentCommentController::class, 'index'])->name('comments.index');
            Route::delete('/comments/{comment}', [DocumentCommentController::class, 'destroy'])->name('comments.destroy');

            // Уведомления
            Route::prefix('notifications')->name('notifications.')->group(function () {
                Route::get('/', [NotificationController::class, 'index'])->name('index');
                Route::get('/create', [NotificationController::class, 'create'])->name('create');
                Route::any('/{id}/read', [NotificationController::class, 'read'])->name('read');
                Route::patch('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('read_patch');
                Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
                Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clearAll');
                Route::post('/read-all', [NotificationController::class, 'readAll'])->name('readAll');
            });
            Route::get('/notifications/check', [NotificationController::class, 'checkNew'])->name('notifications.check');
            Route::post('/comments/store_notification', [NotificationController::class, 'store'])->name('comments.store_notification');

            // Сообщения
            Route::resource('messages', MessageController::class);

            // ИИ генератор документов
            Route::post('/ai/test', function() {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Роут работает!',
                    'needs_questions' => false,
                    'document_data' => [
                        'number' => '№ 123',
                        'type' => 'Договор аренды',
                        'title' => 'Тестовый документ',
                        'content' => 'Это тестовый контент от ИИ',
                        'deadline' => date('Y-m-d', strtotime('+30 days')),
                        'status' => 'draft'
                    ],
                    'download_url' => null
                ]);
            })->name('ai.test');

            Route::post('/ai/generate-document', [AIController::class, 'generateDocument'])
                ->name('ai.generate-document');

            // Heartbeat для отслеживания онлайн статуса
            Route::get('/heartbeat', function () {
                $user = Auth::user();

                if (!$user) {
                    return response()->json(['status' => 'error'], 401);
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['last_seen_at' => now()]);

                return response()->json([
                    'status' => 'ok',
                    'online' => true
                ]);
            })->name('heartbeat');
        });

        // ===== СУПЕР-АДМИН =====

Route::middleware(['auth', 'last.seen'])
            ->prefix('super-admin')
            ->name('superadmin.')
            ->group(function () {

                // Дашборд
                Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');

                // Пользователи
                Route::get('/users', [SuperAdminController::class, 'usersIndex'])->name('users.index');
                Route::get('/users/create', [SuperAdminController::class, 'create'])->name('users.create');
                Route::post('/users', [SuperAdminController::class, 'store'])->name('users.store');
                Route::get('/users/{user}/edit', [SuperAdminController::class, 'edit'])->name('users.edit');
                Route::put('/users/{user}', [SuperAdminController::class, 'update'])->name('users.update');
                Route::delete('/users/{user}', [SuperAdminController::class, 'destroy'])->name('users.destroy');

                // Компании
                Route::get('/companies', [SuperAdminController::class, 'companiesIndex'])->name('companies.index');
                Route::get('/companies/create', [SuperAdminController::class, 'createCompany'])->name('companies.create');
                Route::post('/companies', [SuperAdminController::class, 'storeCompany'])->name('companies.store');
                Route::get('/companies/{company}', [SuperAdminController::class, 'showCompany'])->name('companies.show');
                Route::get('/companies/{company}/edit', [SuperAdminController::class, 'editCompany'])->name('companies.edit');
                Route::put('/companies/{company}', [SuperAdminController::class, 'updateCompany'])->name('companies.update');
                Route::delete('/companies/{company}', [SuperAdminController::class, 'destroyCompany'])->name('companies.destroy');

                // Профиль супер-админа
                Route::get('/profile', [SuperAdminController::class, 'profile'])->name('profile');
                Route::put('/profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');

                // Активность
                Route::get('/activity', [SuperAdminController::class, 'activityIndex'])->name('activity.index');
                Route::get('/user/{user}/activity', [SuperAdminController::class, 'userActivity'])->name('user.activity');
            });
        Route::get('/test-email', function () {
            try {
                \Illuminate\Support\Facades\Mail::raw('Тестовое письмо от DocSign', function ($message) {
                    $message->to('test@example.com') // Замените на свой email для теста
                    ->subject('Тест email из Laravel');
                });

                return response()->json([
                    'status' => 'success',
                    'message' => 'Email отправлен успешно!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        });

Route::get('/users/no-companies', function () {
    return view('users.no-companies'); // или ваш контроллер
})->name('users_no.companies');

Route::middleware(['auth'])->group(function () {
    Route::get('/strel', [StrelController::class, 'index'])->name('strel.index');
});
Route::get('/dashboard-update', function () {
    return 'Данные обновлены успешно!';
})->name('dashboard.update');

Route::get('/verify/{code}', [App\Http\Controllers\DocumentSignatureController::class, 'verify'])
    ->name('document.verify');
