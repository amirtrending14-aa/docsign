
@extends('layouts.admin')

@section('content')

<div class="logs-page">

    {{-- Ambient Lighting --}}
    <div class="ambient-lighting">
        <div class="ambient-blob ambient-blob-1"></div>
        <div class="ambient-blob ambient-blob-2"></div>
        <div class="ambient-blob ambient-blob-3"></div>
    </div>

    <div class="logs-container">

        <style>
            /* ============================================ */
            /* === БАЗОВЫЕ СТИЛИ СТРАНИЦЫ === */
            /* ============================================ */
            .logs-page {
                font-family: 'Inter', sans-serif !important;
                min-height: 100vh;
                padding: 24px 20px;
                position: relative;
                overflow: hidden;
                color: var(--text);
            }

            .logs-page * {
                font-family: 'Inter', sans-serif;
            }

            .logs-page .mono {
                font-family: 'JetBrains Mono', monospace !important;
            }

            /* Ambient Lighting */
            .ambient-lighting {
                position: absolute;
                inset: 0;
                pointer-events: none;
                overflow: hidden;
                z-index: 0;
            }

            .ambient-blob {
                position: absolute;
                border-radius: 50%;
                filter: blur(120px);
            }

            .ambient-blob-1 {
                top: -160px;
                left: -160px;
                width: 420px;
                height: 420px;
                opacity: 0.18;
                background: radial-gradient(circle, rgba(var(--glow), 1) 0%, transparent 70%);
            }

            .ambient-blob-2 {
                bottom: -160px;
                right: -160px;
                width: 500px;
                height: 500px;
                opacity: 0.12;
                background: radial-gradient(circle, #7c3aed 0%, transparent 70%);
            }

            .ambient-blob-3 {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 340px;
                height: 340px;
                opacity: 0.08;
                background: radial-gradient(circle, rgba(var(--glow), 1) 0%, transparent 70%);
            }

            .logs-container {
                max-width: 1200px;
                margin: 0 auto;
                position: relative;
                z-index: 10;
            }

            /* ============================================ */
            /* === HEADER === */
            /* ============================================ */
            .logs-header {
                display: flex;
                align-items: flex-end;
                justify-content: space-between;
                margin-bottom: 20px;
                gap: 16px;
                flex-wrap: wrap;
            }

            .logs-title {
                font-size: 22px;
                font-weight: 800;
                letter-spacing: -0.5px;
                color: var(--text);
                margin: 0;
                text-shadow: 0 0 30px rgba(var(--glow), 0.3);
                text-transform: uppercase;
            }

            .logs-subtitle {
                font-family: 'JetBrains Mono', monospace;
                font-size: 10px;
                font-weight: 600;
                color: var(--muted);
                text-transform: uppercase;
                letter-spacing: 0.25em;
                margin-top: 4px;
            }

            .logs-counter {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                padding: 8px 14px;
                background: rgba(255, 255, 255, 0.035);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.06);
                border-radius: 12px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.05);
            }

            .pulse-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                background: #10b981;
                box-shadow: 0 0 10px #10b981;
                animation: pulse-glow 2s ease-in-out infinite;
            }

            @keyframes pulse-glow {
                0%, 100% { opacity: 1; box-shadow: 0 0 10px #10b981; }
                50% { opacity: 0.6; box-shadow: 0 0 18px #10b981; }
            }

            .logs-counter-text {
                font-family: 'JetBrains Mono', monospace;
                font-size: 11px;
                font-weight: 700;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .logs-counter-number {
                color: rgba(var(--glow), 1);
                font-weight: 800;
            }

            .logs-counter-label {
                color: var(--muted);
            }

            /* ============================================ */
            /* === GLASS CARD === */
            /* ============================================ */
            .glass-card {
                background: rgba(255, 255, 255, 0.035);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.06);
                border-radius: 16px;
                box-shadow:
                    0 8px 32px rgba(0, 0, 0, 0.3),
                    inset 0 1px 0 rgba(255, 255, 255, 0.05);
                overflow: hidden;
                position: relative;
            }

            .glass-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: linear-gradient(90deg, transparent, rgba(var(--glow), 0.3), transparent);
                pointer-events: none;
            }

            /* ============================================ */
            /* === TABLE === */
            /* ============================================ */
            .logs-scroll {
                overflow-x: auto;
            }

            .logs-scroll::-webkit-scrollbar {
                height: 6px;
            }

            .logs-scroll::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.02);
            }

            .logs-scroll::-webkit-scrollbar-thumb {
                background: rgba(var(--glow), 0.3);
                border-radius: 20px;
            }

            .logs-scroll::-webkit-scrollbar-thumb:hover {
                background: rgba(var(--glow), 0.5);
            }

            .logs-table {
                width: 100%;
                text-align: left;
                border-collapse: collapse;
                min-width: 820px;
            }

            .logs-table th {
                padding: 10px 14px;
                font-family: 'JetBrains Mono', monospace;
                font-size: 9px;
                font-weight: 700;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: rgba(148, 163, 184, 0.7);
                background: rgba(255, 255, 255, 0.02);
                border-bottom: 1px solid rgba(255, 255, 255, 0.06);
                white-space: nowrap;
            }

            .logs-table td {
                padding: 10px 14px;
                font-size: 12px;
                color: rgba(226, 232, 240, 0.9);
                vertical-align: middle;
                border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            }

            .tr-hover {
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .tr-hover:hover {
                background: rgba(var(--glow), 0.05);
                transform: translateX(2px);
            }

            .tr-hover:hover td {
                color: #fff;
            }

            /* ID ячейка */
            .cell-id {
                text-align: center;
                font-family: 'JetBrains Mono', monospace;
                font-weight: 700;
                color: var(--muted);
                font-size: 11px;
            }

            /* Document */
            .cell-doc-title {
                font-weight: 700;
                color: var(--text);
                display: block;
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                font-size: 12px;
            }

            /* User */
            .user-cell {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mini-avatar {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'JetBrains Mono', monospace;
                font-size: 11px;
                font-weight: 800;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
                transition: all 0.2s ease;
                flex-shrink: 0;
            }

            .tr-hover:hover .mini-avatar {
                transform: scale(1.1);
                box-shadow: 0 0 16px rgba(var(--glow), 0.4);
            }

            .avatar-red { background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.1)); color: #f87171; border-color: rgba(239, 68, 68, 0.3); }
            .avatar-blue { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.1)); color: #60a5fa; border-color: rgba(59, 130, 246, 0.3); }
            .avatar-slate { background: linear-gradient(135deg, rgba(100, 116, 139, 0.2), rgba(71, 85, 105, 0.1)); color: #94a3b8; border-color: rgba(100, 116, 139, 0.3); }
            .avatar-indigo { background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(79, 70, 229, 0.1)); color: #818cf8; border-color: rgba(99, 102, 241, 0.3); }

            .user-name {
                font-weight: 600;
                color: #cbd5e1;
                font-size: 12px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 120px;
                display: block;
            }

            /* Action badge */
            .action-badge {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                padding: 4px 10px;
                font-family: 'JetBrains Mono', monospace;
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                border-radius: 999px;
                border: 1px solid;
                backdrop-filter: blur(10px);
                transition: all 0.2s ease;
                white-space: nowrap;
            }

            .action-badge:hover {
                transform: scale(1.05);
                box-shadow: 0 0 16px currentColor;
            }

            .badge-created { background: rgba(16, 185, 129, 0.1); color: #34d399; border-color: rgba(16, 185, 129, 0.3); }
            .badge-updated { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border-color: rgba(59, 130, 246, 0.3); }
            .badge-deleted { background: rgba(239, 68, 68, 0.1); color: #f87171; border-color: rgba(239, 68, 68, 0.3); }
            .badge-signed  { background: rgba(99, 102, 241, 0.1); color: #818cf8; border-color: rgba(99, 102, 241, 0.3); }
            .badge-status  { background: rgba(245, 158, 11, 0.1); color: #fbbf24; border-color: rgba(245, 158, 11, 0.3); }
            .badge-unknown { background: rgba(100, 116, 139, 0.1); color: #94a3b8; border-color: rgba(100, 116, 139, 0.3); }

            /* Meta */
            .cell-meta {
                color: var(--muted);
                font-weight: 500;
                font-size: 11px;
                font-style: italic;
                line-height: 1.4;
                display: block;
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            /* Time */
            .cell-time {
                font-family: 'JetBrains Mono', monospace;
                font-weight: 600;
                color: #cbd5e1;
                font-size: 11px;
                white-space: nowrap;
            }

            /* Delete button */
            .delete-btn {
                width: 30px;
                height: 30px;
                border-radius: 8px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                background: rgba(239, 68, 68, 0.1);
                border: 1px solid rgba(239, 68, 68, 0.2);
                color: #ef4444;
                cursor: pointer;
            }

            .delete-btn:hover {
                transform: scale(1.1) rotate(-5deg);
                background: rgba(239, 68, 68, 0.2);
                box-shadow: 0 0 16px rgba(239, 68, 68, 0.4);
                border-color: rgba(239, 68, 68, 0.4);
            }

            .delete-btn i {
                font-size: 13px;
                transition: transform 0.3s ease;
            }

            .delete-btn:hover i {
                transform: rotate(12deg);
            }

            /* Empty state */
            .empty-state {
                padding: 50px 20px;
                text-align: center;
            }

            .empty-icon-wrap {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                background: rgba(100, 116, 139, 0.1);
                border: 1px solid rgba(100, 116, 139, 0.2);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 12px;
            }

            .empty-icon-wrap i {
                font-size: 22px;
                color: var(--muted);
            }

            .empty-text {
                font-family: 'JetBrains Mono', monospace;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.25em;
                color: var(--muted);
            }

            /* ============================================ */
            /* === PAGINATION === */
            /* ============================================ */
            .pagination-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 10px;
                margin-top: 20px;
            }

            .pagination-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 130px;
                height: 40px;
                padding: 0 20px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 700;
                font-family: 'Inter', sans-serif;
                color: rgba(226, 232, 240, 0.9);
                background: rgba(255, 255, 255, 0.035);
                border: 1px solid rgba(255, 255, 255, 0.06);
                text-decoration: none;
                transition: all 0.25s ease;
                cursor: pointer;
                letter-spacing: 0.5px;
                backdrop-filter: blur(10px);
            }

            .pagination-btn:hover:not(.disabled) {
                color: #fff;
                border-color: rgba(var(--glow), 0.4);
                background: rgba(var(--glow), 0.1);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(var(--glow), 0.3);
            }

            .pagination-btn.disabled {
                opacity: 0.3;
                cursor: not-allowed;
                pointer-events: none;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .logs-page { padding: 16px 12px; }
                .logs-title { font-size: 18px; }
                .logs-table th, .logs-table td { padding: 8px 10px; font-size: 11px; }
                .cell-doc-title, .user-name, .cell-meta { max-width: 140px; }
                .pagination-btn { min-width: 110px; height: 38px; padding: 0 16px; font-size: 11px; }
            }
        </style>

        {{-- HEADER --}}
        <div class="logs-header">
            <div>
                <h1 class="logs-title">
                    <span data-i18n="historyTitle">История событий</span>
                </h1>
                <p class="logs-subtitle">
                    <span data-i18n="systemArchive">System Log Archive</span>
                </p>
            </div>

            <div class="logs-counter">
                <div class="pulse-dot"></div>
                <span class="logs-counter-text">
                    <span id="logsCountNumber" class="logs-counter-number">{{ count($logs) }}</span>
                    <span data-i18n="logsText" class="logs-counter-label">логов</span>
                </span>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="glass-card">
            <div class="logs-scroll">
                <table class="logs-table">
                    <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;" data-i18n="colId">ID</th>
                        <th data-i18n="colDoc">Документ</th>
                        <th data-i18n="colUser">Инициатор</th>
                        <th data-i18n="colAction">Тип действия</th>
                        <th class="hidden-lg" data-i18n="colMeta">Мета-данные</th>
                        <th data-i18n="colTime">Время</th>
                        <th style="text-align: right;" data-i18n="colManage">Управление</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($logs as $index => $log)
                    <tr class="tr-hover">

                        {{-- ID --}}
                        <td class="cell-id">
                            #{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- DOCUMENT --}}
                        <td>
                                <span class="cell-doc-title">
                                    {{ $log->document->title ?? '—' }}
                                </span>
                        </td>

                        {{-- USER --}}
                        <td>
                            <div class="user-cell">
                                @php
                                $name = $log->user->name ?? 'System';
                                $firstChar = mb_substr($name, 0, 1);
                                $avatarStyle = match(strtoupper($firstChar)) {
                                'A','R' => 'avatar-red',
                                'B','D' => 'avatar-blue',
                                'S'     => 'avatar-slate',
                                default => 'avatar-indigo'
                                };
                                @endphp
                                <div class="mini-avatar {{ $avatarStyle }}">
                                    {{ strtoupper($firstChar) }}
                                </div>
                                <span class="user-name">{{ $name }}</span>
                            </div>
                        </td>

                        {{-- ACTION --}}
                        <td>
                            @php
                            $action = strtolower($log->action);
                            $actionKey = match(true) {
                            str_contains($action, 'create') || str_contains($action, 'создание') => 'actionCreated',
                            str_contains($action, 'update') || str_contains($action, 'обновление') => 'actionUpdated',
                            str_contains($action, 'delete') || str_contains($action, 'удаление') => 'actionDeleted',
                            str_contains($action, 'sign') || str_contains($action, 'подпись') => 'actionSigned',
                            str_contains($action, 'status') || str_contains($action, 'статус') => 'actionStatus',
                            default => 'actionUnknown'
                            };
                            $badgeClass = match($actionKey) {
                            'actionDeleted' => 'badge-deleted',
                            'actionCreated' => 'badge-created',
                            'actionUpdated' => 'badge-updated',
                            'actionSigned'  => 'badge-signed',
                            'actionStatus'  => 'badge-status',
                            default         => 'badge-unknown'
                            };
                            @endphp
                            <span class="action-badge {{ $badgeClass }}" data-i18n="{{ $actionKey }}">
                                    {{ $log->action }}
                                </span>
                        </td>

                        {{-- META --}}
                        <td class="hidden-lg">
                            <span class="cell-meta">{{ $log->description }}</span>
                        </td>

                        {{-- TIME --}}
                        <td class="cell-time">
                            {{ $log->created_at->format('d.m.y / H:i') }}
                        </td>

                        {{-- DELETE --}}
                        <td style="text-align: right;">
                            <form action="{{ route('logs.destroy', $log->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" data-confirm-i18n="confirmDelete" class="delete-btn-visible">
                                    <i class="bi bi-trash3-fill"></i>
                                    <span class="delete-label">Delete</span>
                                </button>
                            </form>
                        </td>
                        <style>
                            /* === ЗАМЕТНАЯ КНОПКА УДАЛЕНИЯ === */
.delete-btn-visible {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.1));
    border: 1px solid rgba(239, 68, 68, 0.4);
    color: #f87171;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Inter', sans-serif;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.delete-btn-visible i {
    font-size: 14px;
    filter: drop-shadow(0 0 4px rgba(239, 68, 68, 0.5));
}

.delete-btn-visible:hover {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(220, 38, 38, 0.2));
    border-color: rgba(239, 68, 68, 0.6);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(239, 68, 68, 0.4), 0 0 20px rgba(239, 68, 68, 0.3);
}

.delete-btn-visible:hover i {
    transform: rotate(-8deg) scale(1.1);
    filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.8));
}

.delete-btn-visible:active {
    transform: translateY(0);
}

.delete-label {
    font-size: 10px;
    font-weight: 700;
}

/* Для мобильных - скрыть текст */
@media (max-width: 768px) {
    .delete-label {
        display: none;
    }

    .delete-btn-visible {
        padding: 6px 10px;
    }
}
                        </style>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon-wrap">
                                    <i class="bi bi-inbox"></i>
                                </div>
                                <div class="empty-text" data-i18n="noLogs">No logs found</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="pagination-wrapper">
            @if($logs->onFirstPage())
            <span class="pagination-btn disabled">« Previous</span>
            @else
            <a href="{{ $logs->previousPageUrl() }}" class="pagination-btn">« Previous</a>
            @endif

            @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="pagination-btn">Next »</a>
            @else
            <span class="pagination-btn disabled">Next »</span>
            @endif
        </div>
        @endif

    </div>
</div>

<style>
    @media (max-width: 1024px) {
        .hidden-lg { display: none !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const LOGS_TRANSLATIONS = {
            ru: {
                historyTitle: 'История событий',
                systemArchive: 'Архив системных логов',
                colId: 'ID',
                colDoc: 'Документ',
                colUser: 'Инициатор',
                colAction: 'Тип действия',
                colMeta: 'Мета-данные',
                colTime: 'Время',
                colManage: 'Управление',
                actionCreated: 'Создание',
                actionUpdated: 'Обновление',
                actionDeleted: 'Удаление',
                actionSigned: 'Подписание',
                actionStatus: 'Смена статуса',
                actionUnknown: 'Действие',
                noLogs: 'Записи не найдены',
                confirmDelete: 'Удалить запись?',
                logsText: 'логов'
            },
            tj: {
                historyTitle: 'Таърихи ҳодисаҳо',
                systemArchive: 'Архиви системаи логҳо',
                colId: 'ID',
                colDoc: 'Ҳуҷҷат',
                colUser: 'Ташаббускор',
                colAction: 'Навъи амал',
                colMeta: 'Мета-маълумот',
                colTime: 'Вақт',
                colManage: 'Идоракунӣ',
                actionCreated: 'Сохтан',
                actionUpdated: 'Навсозӣ',
                actionDeleted: 'Нест кардан',
                actionSigned: 'Имзо',
                actionStatus: 'Ивази статус',
                actionUnknown: 'Амал',
                noLogs: 'Сабтҳо ёфт нашуданд',
                confirmDelete: 'Сабт нест шавад?',
                logsText: 'логҳо'
            },
            en: {
                historyTitle: 'Event History',
                systemArchive: 'System Log Archive',
                colId: 'ID',
                colDoc: 'Document',
                colUser: 'Initiator',
                colAction: 'Action Type',
                colMeta: 'Meta Data',
                colTime: 'Time',
                colManage: 'Management',
                actionCreated: 'Creation',
                actionUpdated: 'Update',
                actionDeleted: 'Deletion',
                actionSigned: 'Signing',
                actionStatus: 'Status Change',
                actionUnknown: 'Action',
                noLogs: 'No logs found',
                confirmDelete: 'Delete this record?',
                logsText: 'logs'
            }
        };

        function applyLogsTranslations(lang) {
            const dict = LOGS_TRANSLATIONS[lang] || LOGS_TRANSLATIONS.ru;

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] !== undefined) el.textContent = dict[key];
            });

            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (dict[key] !== undefined) el.setAttribute('placeholder', dict[key]);
            });

            document.querySelectorAll('[data-i18n-title]').forEach(el => {
                const key = el.getAttribute('data-i18n-title');
                if (dict[key] !== undefined) el.setAttribute('title', dict[key]);
            });

            // Re-bind confirm handlers with current language
            document.querySelectorAll('[data-confirm-i18n]').forEach(el => {
                const key = el.getAttribute('data-confirm-i18n');
                const newBtn = el.cloneNode(true);
                el.parentNode.replaceChild(newBtn, el);

                newBtn.addEventListener('click', function (e) {
                    const currentLang = localStorage.getItem('docsign_lang') || 'ru';
                    const currentDict = LOGS_TRANSLATIONS[currentLang] || LOGS_TRANSLATIONS.ru;
                    const message = currentDict[key] || 'Are you sure?';

                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        }

        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyLogsTranslations(initialLang);

        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyLogsTranslations(lang);
        });

        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyLogsTranslations(e.newValue);
            }
        });
    });
</script>

@endsection