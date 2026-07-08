<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;

$name = $argv[1] ?? '';
$admin_name = $argv[2] ?? '';
$email = $argv[3] ?? '';
$phone = $argv[4] ?? '';
$password = $argv[5] ?? '';
$telegram_id = $argv[6] ?? null;

if (!$name || !$admin_name || !$email || !$phone || !$password) {
    echo "ERROR:Missing arguments";
    exit(1);
}

try {
    // Проверяем email
    if (User::where('email', $email)->exists()) {
        echo "ERROR:Email already exists";
        exit(1);
    }

    // Проверяем телефон
    if (User::where('phone', $phone)->exists()) {
        echo "ERROR:Phone already exists";
        exit(1);
    }

    // Создаём компанию
    $company = Company::create([
        'name' => $name,
        'slug' => Str::slug($name) . '-' . time(),
        'email' => $email,
        'phone' => $phone,
        'owner_id' => 0,
    ]);

    // Создаём админа (пароль сам захешируется в мутаторе модели)
    $user = User::create([
        'name' => $admin_name,
        'email' => $email,
        'phone' => $phone,
        'password' => $password,  // ← НЕ хешируем! Мутатор сам сделает
        'company' => $name,
        'company_id' => $company->id,
        'is_admin' => true,
        'is_super_admin' => false,
        'email_verified_at' => now(),
        'role' => 'admin',
        'level' => 1,
    ]);

    // Обновляем owner_id
    $company->update(['owner_id' => $user->id]);

    echo "OK:" . $user->id;
} catch (Exception $e) {
    $msg = $e->getMessage();
    if (strpos($msg, 'Duplicate entry') !== false) {
        if (strpos($msg, 'phone') !== false) {
            echo "ERROR:Phone already exists";
        } else {
            echo "ERROR:Email already exists";
        }
    } else {
        echo "ERROR:" . $msg;
    }
}