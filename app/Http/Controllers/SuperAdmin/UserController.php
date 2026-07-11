<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\DocumentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('companyRelation');

        // 🔒 Скрываем других супер-админов из списка (кроме себя)
        $currentUserId = Auth::id();
        $query->where(function($q) use ($currentUserId) {
            $q->where('is_super_admin', false)
                ->orWhere('id', $currentUserId);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'online') {
                $query->where('last_seen_at', '>=', now()->subMinutes(5));
            } elseif ($request->status === 'offline') {
                $query->where(function ($q) {
                    $q->where('last_seen_at', '<', now()->subMinutes(5))
                        ->orWhereNull('last_seen_at');
                });
            }
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        $companies = Company::orderBy('name')->get();

        // 🔒 Логируем просмотр списка пользователей
        $this->logAction(null, 'просмотр списка пользователей',
            "Супер-админ просмотрел список пользователей. Фильтры: " . json_encode($request->only(['search', 'company_id', 'status'])));

        return view('superadmin.users.index', compact('users', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('superadmin.users.create', compact('companies'));
    }

    public function noCompanies()
    {
        $users = User::where(function($q) {
            $q->whereNull('company_id')
                ->orWhere('company_id', 0);
        })
            ->where('is_super_admin', false) // 🔒 Исключаем супер-админов
            ->latest()
            ->paginate(20);

        return view('superadmin.users.no-companies', compact('users'));
    }

    public function store(Request $request)
    {
        // 🔒 КРИТИЧЕСКАЯ ЗАЩИТА: Запрещаем создание супер-админов через UI!
        if ($request->input('role') === 'super_admin') {
            $this->logAction(null, 'ПОПЫТКА СОЗДАНИЯ СУПЕР-АДМИНА',
                "Попытка создать супер-админа заблокирована. IP: " . $request->ip());

            return back()->withErrors([
                'role' => '⛔ Создание супер-админов через интерфейс ЗАПРЕЩЕНО! Используйте только консоль.'
            ]);
        }

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8|confirmed',
            'phone'             => 'nullable|string|unique:users,phone', // 🔒 ДОБАВЛЕНО
            'role'              => 'required|in:employee,admin',
            'level'             => 'required|integer|min:1|max:20',
            'company_id'        => 'nullable|exists:companies,id',
            'new_company_name'  => 'nullable|string|max:255',
            'is_admin'          => 'nullable|boolean',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

// 🔒 Нормализуем телефон
        if (!empty($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);
        }
        $companyId = $data['company_id'] ?? null;
        $companyName = null;

        if ($companyId) {
            $company = Company::find($companyId);
            $companyName = $company->name;
        } elseif (!empty($data['new_company_name'])) {
            $company = Company::create([
                'name' => $data['new_company_name'],
                'slug' => \Illuminate\Support\Str::slug($data['new_company_name']),
                'email' => $data['email'], // ✅ Email пользователя = email компании
                'owner_id' => null, // Будет обновлён после создания пользователя
            ]);
            $companyId = $company->id;
            $companyName = $company->name;
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'level' => $data['level'],
            'company_id' => $companyId,
            'company' => $companyName,
            'is_admin' => $data['role'] === 'admin' || ($data['is_admin'] ?? false),
            'is_super_admin' => false, // 🔒 ВСЕГДА false!
        ];

        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($userData);

        if ($companyId && empty($data['company_id'])) {
            Company::find($companyId)->update(['owner_id' => $user->id]);
        }

        // 🔒 Логируем создание пользователя
        $this->logAction($user->id, 'создание пользователя',
            "Создан пользователь: {$user->name} ({$user->email}), роль: {$user->role}");

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Пользователь создан успешно');
    }

    public function edit(User $user)
    {
        // 🔒 Защита: нельзя редактировать другого супер-админа
        if ($user->is_super_admin && $user->id !== Auth::id()) {
            abort(403, 'Редактирование других супер-админов запрещено');
        }

        $companies = Company::all();
        return view('superadmin.users.edit', compact('user', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        // 🔒 Защита: нельзя редактировать другого супер-админа
        if ($user->is_super_admin && $user->id !== Auth::id()) {
            $this->logAction($user->id, 'ПОПЫТКА РЕДАКТИРОВАНИЯ СУПЕР-АДМИНА',
                "Попытка редактировать другого супер-админа заблокирована. IP: " . $request->ip());
            abort(403, 'Редактирование других супер-админов запрещено');
        }

        // 🔒 КРИТИЧЕСКАЯ ЗАЩИТА: Запрещаем изменение роли на super_admin
        if ($request->input('role') === 'super_admin' && !$user->is_super_admin) {
            $this->logAction($user->id, 'ПОПЫТКА ПОВЫШЕНИЯ ДО СУПЕР-АДМИНА',
                "Попытка повысить пользователя {$user->email} до супер-админа заблокирована");

            return back()->withErrors([
                'role' => '⛔ Повышение до супер-админа через интерфейс ЗАПРЕЩЕНО!'
            ]);
        }

        // 🔒 Защита: нельзя понизить себя (чтобы не потерять доступ)
        if ($user->id === Auth::id() && $request->input('role') !== 'super_admin') {
            return back()->withErrors([
                'role' => '⛔ Вы не можете понизить свои собственные права!'
            ]);
        }

        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email,' . $user->id,
            'password'          => 'nullable|min:8|confirmed',
            'phone'             => 'nullable|string|unique:users,phone,' . $user->id, // 🔒 ДОБАВЛЕНО
            'role'              => 'required|in:employee,admin,super_admin',
            'level'             => 'required|integer|min:1|max:20',
            'company_id'        => 'nullable|exists:companies,id',
            'is_admin'          => 'nullable|boolean',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_avatar'     => 'nullable|boolean',
        ]);

// 🔒 Нормализуем телефон
        if (!empty($data['phone'])) {
            $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);
        }

        $companyId = $data['company_id'] ?? null;
        $companyName = null;

        if ($companyId) {
            $company = Company::find($companyId);
            $companyName = $company->name;
        }

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'],
            'level' => $data['level'],
            'company_id' => $companyId,
            'company' => $companyName,
            'is_admin' => $data['role'] === 'admin' || ($data['is_admin'] ?? false),
            // 🔒 Сохраняем статус супер-админа, если он уже был
            'is_super_admin' => $user->is_super_admin,
        ];

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if ($request->input('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($userData);

        // 🔒 Логируем обновление
        $this->logAction($user->id, 'обновление пользователя',
            "Обновлён пользователь: {$user->name} ({$user->email})");

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Пользователь обновлён');
    }

    public function destroy(User $user)
    {
        // 🔒 КРИТИЧЕСКАЯ ЗАЩИТА 1: Нельзя удалить самого себя
        if ($user->id === Auth::id()) {
            $this->logAction($user->id, 'ПОПЫТКА САМОУДАЛЕНИЯ',
                "Супер-админ попытался удалить себя. IP: " . request()->ip());

            return back()->with('error', '⛔ Вы не можете удалить свой собственный аккаунт!');
        }

        // 🔒 КРИТИЧЕСКАЯ ЗАЩИТА 2: Нельзя удалить другого супер-админа
        if ($user->is_super_admin) {
            $this->logAction($user->id, 'ПОПЫТКА УДАЛЕНИЯ СУПЕР-АДМИНА',
                "Попытка удалить супер-админа {$user->email} заблокирована. IP: " . request()->ip());

            return back()->with('error', '⛔ Удаление супер-админов запрещено!');
        }

        // 🔒 КРИТИЧЕСКАЯ ЗАЩИТА 3: Проверка что останется хотя бы один супер-админ
        $superAdminsCount = User::where('is_super_admin', true)->count();
        if ($superAdminsCount <= 1) {
            return back()->with('error', '⛔ Нельзя удалить последнего супер-админа в системе!');
        }

        // 🔒 Логируем удаление
        $this->logAction($user->id, 'удаление пользователя',
            "Удалён пользователь: {$user->name} ({$user->email})");

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Пользователь удалён');
    }

    public function userActivity($id)
    {
        $user = User::findOrFail($id);

        // 🔒 Защита: нельзя смотреть активность другого супер-админа
        if ($user->is_super_admin && $user->id !== Auth::id()) {
            abort(403, 'Просмотр активности других супер-админов запрещён');
        }

        $users = User::orderBy('name')->get();

        $activities = \App\Models\Activity::where('user_id', $id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('superadmin.activity', compact('user', 'users', 'activities'));
    }

    /**
     * 🔒 Универсальный метод логирования действий супер-админа
     */
    private function logAction($targetUserId, $action, $description)
    {
        try {
            DocumentLog::create([
                'document_id' => null,
                'user_id' => Auth::id(),
                'action' => 'super_admin: ' . $action,
                'description' => $description . ' | IP: ' . request()->ip() . ' | User Agent: ' . request()->userAgent()
            ]);
        } catch (\Exception $e) {
            \Log::error("Ошибка логирования супер-админа: " . $e->getMessage());
        }
    }
}