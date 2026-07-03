<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_companies' => Company::count(),
            'online_now' => User::where('last_seen_at', '>=', now()->subMinutes(5))->count(),
            'admins' => User::where('is_admin', true)->orWhere('level', 1)->count(),
            'documents' => Document::count(),
            'new_users_today' => User::whereDate('created_at', today())->count(),
        ];

        $recentUsers = User::with('companyRelation')
            ->latest()
            ->take(8)
            ->get();

        $recentCompanies = Company::withCount('users')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'recentCompanies'));
    }

    public function usersIndex(Request $request)
    {
        $query = User::with('companyRelation');

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

        return view('superadmin.users.index', compact('users', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('superadmin.users.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6',
            'phone'             => 'nullable|string',
            'role'              => 'required|string|max:50',
            'level'             => 'required|integer|min:1|max:20',
            'company_id'        => 'nullable|exists:companies,id',
            'new_company_name'  => 'nullable|string|max:255',
            'is_admin'          => 'nullable|boolean',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $companyId = null;
        $companyName = null;

        // Вариант 1: Выбрана существующая компания
        if (!empty($data['company_id'])) {
            $company = Company::find($data['company_id']);
            if ($company) {
                $companyId = $company->id;
                $companyName = $company->name;
            }
        }
        // Вариант 2: Введено название новой компании
        elseif (!empty($data['new_company_name'])) {
            $company = Company::create([
                'name' => $data['new_company_name'],
            ]);
            $companyId = $company->id;
            $companyName = $company->name;
        }

        // Формируем данные пользователя
        $userData = [
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'phone'          => $data['phone'] ?? null,
            'role'           => $data['role'],
            'level'          => $data['level'],
            'company_id'     => $companyId,
            'company'        => $companyName,
            'is_admin'       => $request->boolean('is_admin'),
            'is_super_admin' => false,
            'created_by'     => Auth::id(),
        ];

        // Загрузка аватара
        if ($request->hasFile('avatar')) {
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Создаём пользователя
        $user = User::create($userData);

        // Если создана новая компания - назначаем пользователя владельцем
        if ($companyId && empty($data['company_id'])) {
            Company::where('id', $companyId)->update(['owner_id' => $user->id]);
        }

        return redirect()->route('superadmin.users.index')
            ->with('success', '✅ Пользователь "' . $user->name . '" создан успешно!');
    }

    public function edit(User $user)
    {
        $companies = Company::orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string',
            'role'       => 'required|string|max:50',
            'level'      => 'required|integer|min:1|max:20',
            'company_id' => 'nullable|exists:companies,id',
            'is_admin'   => 'nullable|boolean',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['is_admin'] = $request->boolean('is_admin');

        if ($data['company_id']) {
            $company = Company::find($data['company_id']);
            $data['company'] = $company->name;
        } else {
            $data['company'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Пользователь обновлён');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Нельзя удалить самого себя');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return back()->with('success', 'Пользователь удалён');
    }

    public function companiesIndex()
    {
        try {
            $companies = Company::withCount('users')
                ->with('owner')
                ->latest()
                ->paginate(20);
        } catch (\Exception $e) {
            $companies = Company::with('owner')
                ->latest()
                ->paginate(20);

            foreach ($companies as $company) {
                $company->users_count = $company->users()->count();
            }
        }

        return view('superadmin.companies.index', compact('companies'));
    }

    public function activityIndex(Request $request)
    {
        $users = User::orderBy('name')->get();
        $query = Document::with('creator');

        if ($request->filled('user_id')) {
            $query->where('created_by', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(50);

        return view('superadmin.activity', compact('activities', 'users'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('superadmin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Обновляем аватар
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Обновляем пароль
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // Обновляем пользователя
        $user->update($data);

        return back()->with('success', '✅ Профиль успешно обновлён');
    }

    public function editCompany(Company $company)
    {
        $users = User::where('company_id', $company->id)->get();
        return view('superadmin.companies.edit', compact('company', 'users'));
    }

    public function showCompany(Company $company)
    {
        $users = User::where('company_id', $company->id)
            ->withCount('documents')
            ->get();

        $documents = Document::whereHas('creator', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })->latest()->take(20)->get();

        $stats = [
            'total_users' => $users->count(),
            'online_users' => $users->filter(fn($u) => $u->isOnline())->count(),
            'total_documents' => $documents->count(),
            'admins' => $users->filter(fn($u) => $u->isAdmin())->count(),
        ];

        return view('superadmin.companies.show', compact('company', 'users', 'documents', 'stats'));
    }

    public function updateCompany(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $company->update($data);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Компания обновлена');
    }

    public function destroyCompany(Company $company)
    {
        if ($company->users()->count() > 0) {
            return back()->with('error', 'Нельзя удалить компанию с пользователями');
        }

        $company->delete();

        return back()->with('success', 'Компания удалена');
    }

    public function userActivity(User $user)
    {
        $documents = Document::where('created_by', $user->id)
            ->latest()
            ->paginate(30);

        return view('superadmin.user-activity', compact('user', 'documents'));
    }
    // Добавь эти методы в SuperAdminController

    /**
     * Форма создания компании
     */
    public function createCompany()
    {
        return view('superadmin.companies.create');
    }

    /**
     * Сохранение новой компании
     */
    public function storeCompany(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255|unique:companies,name',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
        ]);

        $company = Company::create($data);

        return redirect()->route('superadmin.companies.index')
            ->with('success', '✅ Компания "' . $company->name . '" создана успешно!');
    }
}