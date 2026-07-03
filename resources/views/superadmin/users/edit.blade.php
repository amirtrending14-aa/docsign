@extends('layouts.superadmin')

@section('title', 'Редактирование пользователя')
@section('page-title', '✏️ Редактирование: ' . $user->name)
@section('page-subtitle', 'Изменение данных пользователя')

@section('content')
<div class="card">
    <form action="{{ route('superadmin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Имя *</label>
                <input type="text" name="name"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Email *</label>
                <input type="email" name="email"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">
                    Новый пароль <span class="text-zinc-500 text-xs">(оставьте пустым, если не меняете)</span>
                </label>
                <input type="password" name="password"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50">
                @error('password')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Подтверждение пароля</label>
                <input type="password" name="password_confirmation"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Роль *</label>
                <select name="role"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-red-500/50" required>
                    <option value="employee" {{ (old('role', $user->role) == 'employee') ? 'selected' : '' }}>Сотрудник</option>
                    <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Админ</option>
                    <option value="super_admin" {{ (old('role', $user->role) == 'super_admin' || $user->is_super_admin) ? 'selected' : '' }}>Супер Админ</option>
                </select>
                @error('role')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Уровень</label>
                <input type="number" name="level" min="1" max="20"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-red-500/50"
                       value="{{ old('level', $user->level) }}">
                @error('level')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Компания</label>
                <select name="company_id"
                        class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-red-500/50">
                    <option value="">Без компании</option>
                    @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ (old('company_id', $user->company_id) == $company->id) ? 'selected' : '' }}>
                    {{ $company->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">Телефон</label>
                <input type="text" name="phone"
                       class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                       value="{{ old('phone', $user->phone) }}" placeholder="+992 XXX XX XX XX">
            </div>

            <div>
                <label class="block text-sm font-semibold text-zinc-300 mb-2">
                    <input type="checkbox" name="is_admin" value="1"
                           {{ (old('is_admin', $user->is_admin)) ? 'checked' : '' }}>
                    Администратор компании
                </label>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold text-zinc-300 mb-2">Аватар</label>
            <input type="file" name="avatar" accept="image/*"
                   class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-500/20 file:text-red-400 hover:file:bg-red-500/30">
            @if($user->avatar)
            <div class="mt-2 flex items-center gap-3">
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-12 h-12 rounded-full object-cover border border-white/10">
                <span class="text-xs text-zinc-500">Текущий аватар</span>
            </div>
            @endif
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
            <a href="{{ route('superadmin.users.index') }}" class="btn-ghost">Отмена</a>
            <button type="submit" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Сохранить изменения
            </button>
        </div>
    </form>
</div>
@endsection