<?php
// verify_email.php - Помечает email как верифицированный
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Cache;

$email = $argv[1] ?? '';

if (!$email) {
    echo "ERROR:Missing email";
    exit(1);
}

try {
    // Устанавливаем верификацию на 24 часа
    Cache::put("email_verified:{$email}", true, 86400);
    echo "OK:1";
} catch (Exception $e) {
    echo "ERROR:" . $e->getMessage();
}