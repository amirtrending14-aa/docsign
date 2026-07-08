<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\CompanyController as SuperAdminCompanyController;
use App\Http\Controllers\Admin\AvatarController;
use App\Http\Controllers\StrelController;
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
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\AIController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| 1. ПУБЛИЧНЫЕ МАРШРУТЫ
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('layouts.site'))->name('site.home');
Route::get('/site', fn() => view('layouts.site'))->name('site.main');
Route::get('/verify/{code}', [DocumentSignatureController::class, 'verify'])
    ->name('document.verify');

/*
|--------------------------------------------------------------------------
| 2. АУТЕНТИФИКАЦИЯ
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| 3. ЛОКАЛЬНЫЕ МАРШРУТЫ (только для разработки)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::post('/login-as', function (Request $request) {
        Auth::loginUsingId($request->user_id);
        return back()->with('success', 'Switched to user: ' . Auth::user()->name);
    })->name('login.as');
}

/*
|--------------------------------------------------------------------------
| 4. АВТОРИЗОВАННЫЕ ПОЛЬЗОВАТЕЛИ
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'last.seen'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])
        ->name('dashboard.chart-data');
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/setting', fn() => view('settings.index'))->name('settings');
    Route::post('/settings/signature', [ProfileController::class, 'updateSignature'])
        ->name('settings.signature.update');
    Route::post('/settings/general', [ProfileController::class, 'updateGeneral'])
        ->name('settings.general.update');
    Route::put('/settings/edi', [SettingsController::class, 'update'])
        ->name('settings.update');

    // Documents
    Route::get('/documents/{id}/download-pdf', [DocumentController::class, 'downloadPdf'])
        ->name('documents.downloadPdf');
    Route::get('/documents/{id}/download-word', [DocumentController::class, 'downloadWord'])
        ->name('documents.downloadWord');
    Route::post('/documents/ai-process', [DocumentController::class, 'storeFromPdf'])
        ->name('documents.ai-process');
    Route::post('/documents/{id}/sign', [DocumentController::class, 'sign'])
        ->name('documents.sign');
    Route::post('/documents/{id}/reject', [DocumentController::class, 'reject'])
        ->name('documents.reject');

    // Resources
    Route::resource('documents', DocumentController::class);
    Route::resource('users', UserController::class);
    Route::resource('signatures', DocumentSignatureController::class);
    Route::resource('versions', DocumentVersionController::class);

    // ✅ ИСТОРИЯ СОБЫТИЙ (logs) - только один раз!
    Route::resource('logs', DocumentLogController::class);
    Route::post('/logs/clear', [DocumentLogController::class, 'clear'])->name('logs.clear');
    Route::get('/documents/{document}/logs', [DocumentLogController::class, 'documentLogs'])
        ->name('logs.document');

    Route::resource('workflow', DocumentWorkflowController::class);

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/api/users/search', function (Request $request) {
        $user = \App\Models\User::where('email', $request->email)->first();
        return response()->json([
            'exists' => !!$user,
            'name' => $user?->name,
        ]);
    })->name('users.search_api');

    // Comments
    Route::post('/comments', [DocumentCommentController::class, 'store'])->name('comments.store');
    Route::get('/documents/{documentId}/comments', [DocumentCommentController::class, 'index'])
        ->name('comments.index');
    Route::delete('/comments/{comment}', [DocumentCommentController::class, 'destroy'])
        ->name('comments.destroy');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/create', [NotificationController::class, 'create'])->name('create');
        Route::get('/check', [NotificationController::class, 'checkNew'])->name('check');
        Route::any('/{id}/read', [NotificationController::class, 'read'])->name('read');
        Route::patch('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('read_patch');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clearAll');
        Route::post('/read-all', [NotificationController::class, 'readAll'])->name('readAll');
    });
    Route::post('/comments/store_notification', [NotificationController::class, 'store'])
        ->name('comments.store_notification');

    // Messages
    Route::resource('messages', MessageController::class);

    // AI
    Route::post('/ai/generate-document', [AIController::class, 'generateDocument'])
        ->name('ai.generate-document');

    if (app()->environment('local')) {
        Route::post('/ai/test', fn() => response()->json([
            'status' => 'success',
            'message' => 'Роут работает!',
            'needs_questions' => false,
            'document_data' => [
                'number' => '№ 123',
                'type' => 'Договор аренды',
                'title' => 'Тестовый документ',
                'content' => 'Это тестовый контент от ИИ',
                'deadline' => date('Y-m-d', strtotime('+30 days')),
                'status' => 'draft',
            ],
            'download_url' => null,
        ]))->name('ai.test');
    }

    // Heartbeat
    Route::get('/heartbeat', function () {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'error'], 401);
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update(['last_seen_at' => now()]);

        return response()->json(['status' => 'ok', 'online' => true]);
    })->name('heartbeat');

    // Strel
    Route::get('/strel', [StrelController::class, 'index'])->name('strel.index');
});

/*
|--------------------------------------------------------------------------
| 5. СУПЕР-АДМИН
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin', 'last.seen'])
    ->prefix('super-admin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');

        // Пользователи
        Route::get('users/export', [SuperAdminUserController::class, 'exportUsers'])
            ->name('users.export');
        Route::post('users/bulk-delete', [SuperAdminUserController::class, 'bulkDelete'])
            ->name('users.bulk');
        Route::resource('users', SuperAdminUserController::class);
        Route::post('users/{user}/reset-password', [SuperAdminUserController::class, 'resetPassword'])
            ->name('users.reset-password');
        Route::post('users/{user}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Активность пользователя
        Route::get('users/{user}/activity', [SuperAdminUserController::class, 'userActivity'])
            ->name('users.activity');

        // Компании
        Route::resource('companies', SuperAdminCompanyController::class);

        // ИСТОРИЯ АКТИВНОСТИ
        Route::get('activity', [ActivityController::class, 'index'])->name('activity');

        // Профиль супер-админа
        Route::get('profile', [SuperAdminController::class, 'profile'])->name('profile');
        Route::put('profile', [SuperAdminController::class, 'updateProfile'])->name('profile.update');
    });

/*
|--------------------------------------------------------------------------
| 6. СЛУЖЕБНЫЕ МАРШРУТЫ
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/test-email', function () {
        try {
            \Illuminate\Support\Facades\Mail::raw('Тестовое письмо от DocSign', function ($message) {
                $message->to('test@example.com')->subject('Тест email из Laravel');
            });
            return response()->json(['status' => 'success', 'message' => 'Email отправлен!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    })->name('test.email');
}

Route::middleware('auth')->get('/users/no-companies', function () {
    return view('users.no-companies');
})->name('users.no-companies');

Route::get('/dashboard-update', fn() => 'Данные обновлены успешно!')
    ->name('dashboard.update');