<?php
// create_company.php - создаёт компанию с всеми полями
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

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
    // ❌ ВЕРИФИКАЦИЯ ОТКЛЮЧЕНА
    // $verified = Cache::get("email_verified:{$email}", false);
    // if (!$verified) {
    //     echo "ERROR:Email not verified";
    //     exit(1);
    // }

    // Проверяем что email свободен
    if (DB::table('companies')->where('email', $email)->exists()) {
        echo "ERROR:Email already exists";
        exit(1);
    }

    // Проверяем что телефон свободен
    if (DB::table('companies')->where('phone', $phone)->exists()) {
        echo "ERROR:Phone already exists";
        exit(1);
    }

    // Генерируем slug из названия
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))) . '-' . time();

    // Создаём компанию
    $companyId = DB::table('companies')->insertGetId([
        'name' => $name,
        'slug' => $slug,
        'email' => $email,
        'phone' => $phone,
        'password_hash' => Hash::make($password),
        'owner_telegram_id' => $telegram_id,
        'language' => 'ru',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Создаём пользователя-админа в таблице users
    $userId = DB::table('users')->insertGetId([
        'name' => $admin_name,
        'email' => $email,
        'phone' => $phone,
        'password' => Hash::make($password),
        'company' => $name,
        'is_admin' => true,
        'is_super_admin' => false,
        'email_verified_at' => now(),
        'role' => 'admin',
        'level' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    echo "OK:" . $companyId;
} catch (Exception $e) {
    echo "ERROR:" . $e->getMessage();
}