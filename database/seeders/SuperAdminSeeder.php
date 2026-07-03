<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Убедись, что путь к твоей модели User правильный

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Метод updateOrCreate найдет пользователя по email.
        // Если его нет — создаст, если есть — обновит данные.
        User::updateOrCreate(
            ['email' => 'amirtrending14@gmail.com'], // Условие поиска
            [
                'name' => 'Amir SuperAdmin', // Твое имя
                'password' => Hash::make('1404trend'), // Придумай надежный пароль
                'role' => 'super_admin', // Или как у тебя называется роль в базе (is_admin = 1, и т.д.)
                'email_verified_at' => now(), // Чтобы почта считалась подтвержденной
            ]
        );
    }
}