<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    // Главная панель
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

    // Список всех пользователей
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

    // Создание пользователя
    public function create()
    {
        $companies = Company::orderBy('name')->get();
        return view('superadmin.users.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
            'phone'      => 'nullable|string',
            'role'       => 'required|string|max:50',
            'level'      => 'required|integer|min:1|max:20',
            'company_id' => 'nullable|exists:companies,id',
            'is_admin'   => 'nullable|boolean',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = Auth::id();
        $data['is_admin'] = $request->boolean('is_admin');

        if ($data['company_id']) {
            $company = Company::find($data['company_id']);
            $data['company'] = $company->name;
        }

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Пользователь успешно создан');
    }

    // Редактирование
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

    // Удаление
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

    // Список всех компаний
    public function companiesIndex()
    {
        $companies = Company::withCount('users')
            ->with('owner')
            ->latest()
            ->paginate(20);

        return view('superadmin.companies.index', compact('companies'));
    }

    // Активность
    public function activityIndex()
    {
        $activity = Document::with('creator')
            ->latest()
            ->take(50)
            ->get()
            ->groupBy(function ($doc) {
                return $doc->created_at->format('Y-m-d');
            });

        return view('superadmin.activity', compact('activity'));
    }

    public function userActivity(User $user)
    {
        $documents = Document::where('created_by', $user->id)
            ->latest()
            ->paginate(30);

        return view('superadmin.user-activity', compact('user', 'documents'));
    }
}