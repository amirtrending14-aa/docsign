@extends('layouts.superadmin')

@section('title', 'Все пользователи')
@section('page-title', 'Пользователи системы')
@section('page-subtitle', 'Управление всеми пользователями всех компаний')

@section('content')
<div class="card mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Поиск по имени, email, телефону..."
               class="bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white placeholder-zinc-500 focus:outline-none focus:border-red-500/50">

        <select name="company_id" class="bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-red-500/50">
            <option value="">Все компании</option>
            @foreach($companies as $company)
            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
            {{ $company->name }}
            </option>
            @endforeach
        </select>

        <select name="status" class="bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-red-500/50">
            <option value="">Все статусы</option>
            <option value="online" {{ request('status') === 'online' ? 'selected' : '' }}>Онлайн</option>
            <option value="offline" {{ request('status') === 'offline' ? 'selected' : '' }}>Офлайн</option>
        </select>

        <button type="submit" class="btn-primary justify-center">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Фильтр
        </button>
    </form>
</div>

<div class="card p-0">
    <div class="flex items-center justify-between p-4 border-b border-white/5">
        <h2 class="text-base font-bold text-white">
            Всего: <span class="text-red-400">{{ $users->total() }}</span>
        </h2>
        <a href="{{ route('superadmin.users.create') }}" class="btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Добавить
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>Пользователь</th>
                <th>Компания</th>
                <th>Роль</th>
                <th>Уровень</th>
                <th>Статус</th>
                <th>Создан</th>
                <th class="text-right">Действия</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="">
                            @else
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="font-semibold text-white">{{ $user->name }}</div>
                            <div class="text-xs text-zinc-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>{{ $user->companyRelation->name ?? '—' }}</td>
                <td>
                    @if($user->isSuperAdmin())
                    <span class="badge badge-super">Super</span>
                    @elseif($user->isAdmin())
                    <span class="badge badge-admin">Admin</span>
                    @else
                    <span class="text-xs text-zinc-400">{{ $user->role }}</span>
                    @endif
                </td>
                <td><span class="text-xs font-mono text-zinc-400">L{{ $user->level }}</span></td>
                <td>
                    @if($user->isOnline())
                    <span class="badge badge-online">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                                Online
                            </span>
                    @else
                    <span class="badge badge-offline">Offline</span>
                    @endif
                </td>
                <td class="text-xs text-zinc-500">{{ $user->created_at->format('d.m.Y') }}</td>
                <td class="text-right">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('superadmin.user.activity', $user->id) }}" class="btn-ghost" title="Активность">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </a>
                        <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn-ghost" title="Редактировать">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Удалить пользователя?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-ghost text-red-400 hover:text-red-300 hover:bg-red-500/10" title="Удалить">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-8 text-zinc-500">Пользователи не найдены</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="p-4 border-t border-white/5">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection