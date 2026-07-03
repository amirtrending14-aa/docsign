@extends('layouts.superadmin')

@section('title', 'Мой профиль')
@section('page-title', 'Мой профиль')
@section('page-subtitle', 'Управление личной информацией')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Левая колонка - Аватар и информация --}}
    <div class="lg:col-span-1">
        <div class="card text-center">
            <div class="mb-6">
                <div class="relative inline-block">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-red-500/30 shadow-2xl shadow-red-500/20 mx-auto">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center text-4xl font-bold text-white">
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 right-0 w-8 h-8 rounded-full bg-green-500 border-4 border-black flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-white"></div>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h2>
            <p class="text-sm text-zinc-400 mb-4">{{ $user->email }}</p>

            <div class="space-y-2 text-left">
                <div class="flex items-center gap-2 text-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="text-zinc-300">Super Administrator</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span class="text-zinc-300">{{ $user->phone ?? 'Не указан' }}</span>
                </div>
                <div class="flex items-center gap-2 text-sm">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 text-green-400">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-zinc-300">Регистрация: {{ $user->created_at->format('d.m.Y') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-white/10">
                <div class="grid grid-cols-2 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $user->level }}</div>
                        <div class="text-xs text-zinc-500 uppercase">Уровень</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-white">{{ $user->documents_count ?? 0 }}</div>
                        <div class="text-xs text-zinc-500 uppercase">Документов</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Правая колонка - Форма редактирования --}}
    <div class="lg:col-span-2">
        <div class="card">
            <h3 class="text-lg font-bold text-white mb-6">Редактировать профиль</h3>

            <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-300 mb-2">Имя *</label>
                        <input type="text" name="name"
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-zinc-300 mb-2">Email *</label>
                        <input type="email" name="email"
                               class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-300 mb-2">Телефон</label>
                    <input type="text" name="phone"
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50"
                           value="{{ old('phone', $user->phone) }}" placeholder="+992 XXX XX XX XX">
                    @error('phone')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-semibold text-zinc-300 mb-2">Аватар</label>
                    <input type="file" name="avatar" accept="image/*"
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-red-500/20 file:text-red-400 hover:file:bg-red-500/30">
                    @error('avatar')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="border-t border-white/10 pt-4 mt-6">
                    <h4 class="text-sm font-bold text-zinc-300 mb-4">Изменить пароль <span class="text-zinc-500 font-normal">(оставьте пустым, если не меняете)</span></h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-zinc-300 mb-2">Новый пароль</label>
                            <input type="password" name="password"
                                   class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50">
                            @error('password')<div class="text-red-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-zinc-300 mb-2">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-white/10">
                    <a href="{{ route('superadmin.dashboard') }}" class="btn-ghost">Отмена</a>
                    <button type="submit" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection