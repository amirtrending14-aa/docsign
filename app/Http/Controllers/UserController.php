<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();

        // Супер-админ видит все компании
        if ($authUser->isSuperAdmin()) {
            $companies = Company::all();
            $users = User::where('is_super_admin', false)->get();
            $companyName = 'Все компании';
        }
        // Админ или работник видят только свою компанию
        elseif ($authUser->company_id) {
            $users = User::where('company_id', $authUser->company_id)
                ->where('is_super_admin', false)
                ->get();
            $company = $authUser->companyRelation;
            $companyName = $company ? $company->name : ($authUser->company ?? 'Моя команда');
        }
        // Пользователь без компании не видит ничего
        else {
            return view('users.no_company');
        }

        $groupedByLevel = $users->groupBy('level')->sortKeys();

        return view('users.index', compact('users', 'groupedByLevel', 'authUser', 'companyName'));
    }

    public function create()
    {
        $authUser = auth()->user();

        // Только админ может добавлять
        if (!$authUser->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'Только администратор может добавлять пользователей');
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        if (!$authUser->isAdmin()) {
            return redirect()->route('users.index')->with('error', 'Только администратор может добавлять пользователей');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'phone'    => 'nullable|string',
            'role'     => 'required|string|max:50',
            'level'    => 'required|integer|min:2|max:20',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Работник получает компанию админа
        $companyId = $authUser->company_id;
        $companyName = $authUser->company;

        // Если у админа нет company_id, создаём компанию
        if (!$companyId && $companyName) {
            $company = Company::firstOrCreate(
                ['name' => $companyName],
                ['owner_id' => $authUser->id]
            );
            $companyId = $company->id;
            $authUser->update(['company_id' => $companyId]);
        }

        $data['password'] = Hash::make($data['password']);
        $data['created_by'] = $authUser->id;
        $data['company_id'] = $companyId;
        $data['company'] = $companyName;
        $data['is_admin'] = false;
        $data['is_super_admin'] = false;

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Пользователь создан');
    }

    public function show(User $user)
    {
        $authUser = auth()->user();

        // Проверка: пользователь должен быть из той же компании
        if (!$authUser->isSuperAdmin() && $authUser->company_id && $user->company_id !== $authUser->company_id) {
            abort(403, 'Нет доступа');
        }

        $year = now()->year;
        $firstDayOfYear = Carbon::create($year, 1, 1);
        $startDate = $firstDayOfYear->copy()->startOfWeek(Carbon::MONDAY);
        $lastDayOfYear = Carbon::create($year, 12, 31);
        $endDate = $lastDayOfYear->copy()->endOfWeek(Carbon::SUNDAY);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $weeksCount = (int)ceil($totalDays / 7);

        $activityData = Document::where('created_by', $user->id)
            ->whereYear('created_at', $year)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return view('users.show', compact('user', 'activityData', 'year', 'startDate', 'weeksCount'));
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();

        // Проверка компании
        if (!$authUser->isSuperAdmin() && $authUser->company_id && $user->company_id !== $authUser->company_id) {
            return redirect()->route('users.index')->with('error', 'Нет доступа');
        }

        // Админ может редактировать всех, работник только себя
        $canEdit = $authUser->isAdmin() || ($user->id === $authUser->id);

        if ($canEdit) {
            return view('users.edit', compact('user'));
        }

        return redirect()->route('users.index')->with('error', 'Нет прав для редактирования');
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->isSuperAdmin() && $authUser->company_id && $user->company_id !== $authUser->company_id) {
            return redirect()->route('users.index')->with('error', 'Нет доступа');
        }

        $canEdit = $authUser->isAdmin() || ($user->id === $authUser->id);

        if (!$canEdit) {
            return redirect()->route('users.index')->with('error', 'Нет прав для редактирования');
        }

        $rules = [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_avatar' => 'nullable|string|in:0,1',
        ];

        // Только админ может менять роль и уровень
        if ($authUser->isAdmin()) {
            $rules['role'] = 'required|string|max:50';
            $rules['level'] = 'required|integer|min:2|max:20';
        }

        $data = $request->validate($rules);

        if (!$authUser->isAdmin()) {
            $data['role'] = $user->role;
            $data['level'] = $user->level;
        }

        if ($request->input('remove_avatar') === '1') {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Данные обновлены');
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->isSuperAdmin() && $authUser->company_id && $user->company_id !== $authUser->company_id) {
            return back()->with('error', 'Нет доступа');
        }

        if ($user->id === $authUser->id) {
            return back()->with('error', 'Нельзя удалить себя');
        }

        if (!$authUser->isAdmin()) {
            return back()->with('error', 'Только администратор может удалять');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Пользователь удалён');
    }
}