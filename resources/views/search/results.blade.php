@extends('layouts.admin')

@section('content')
<style>
    /* === СТРАНИЦА ПОИСКА В СТИЛЕ АДМИНКИ === */
    .search-page-custom {
        color: var(--text);
    }

    .search-page-custom .page-head-custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .search-page-custom h1 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .search-page-custom h1 .accent-bar {
        width: 4px;
        height: 26px;
        background: rgba(var(--glow), 1);
        border-radius: 2px;
        box-shadow: 0 0 12px rgba(var(--glow), 0.8);
    }

    .search-page-custom .query-highlight {
        color: rgba(var(--glow), 1);
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
    }

    /* Счётчик результатов */
    .results-counter {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 16px;
        background: rgba(255,255,255,0.035);
        border: 1px solid var(--line);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
    }

    .results-counter .count-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: 14px;
        font-weight: 700;
        color: rgba(var(--glow), 1);
        text-shadow: 0 0 10px rgba(var(--glow), 0.5);
    }

    .results-counter .count-label {
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 10px;
    }

    /* Контейнер таблицы */
    .search-table-wrap {
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 4px;
        overflow: hidden;
        position: relative;
    }

    .search-table-wrap::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: 14px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.4), transparent 40%, transparent 60%, rgba(var(--glow), 0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0.6;
    }

    .search-table {
        width: 100%;
        border-collapse: collapse;
    }

    .search-table th {
        text-align: left;
        padding: 14px 16px;
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 1px solid var(--line);
        background: rgba(255,255,255,0.02);
    }

    .search-table th.center { text-align: center; }
    .search-table th.right { text-align: right; }

    .search-table td {
        padding: 16px;
        font-size: 13px;
        color: var(--text);
        border-bottom: 1px solid var(--line);
        transition: all .2s ease;
    }

    .search-table td.center { text-align: center; }
    .search-table td.right { text-align: right; }

    .search-table tbody tr {
        transition: all .25s ease;
    }

    .search-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .search-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(var(--glow), 0.06), transparent 60%);
    }

    .search-table tbody tr:hover td:first-child {
        box-shadow: inset 3px 0 0 0 rgba(var(--glow), 1);
    }

    /* Иконка типа (User/Doc/Sig) */
    .type-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: 'JetBrains Mono', monospace;
        font-size: 13px;
        font-weight: 800;
        flex-shrink: 0;
        transition: all .25s ease;
    }

    .search-table tbody tr:hover .type-icon {
        transform: scale(1.08);
        box-shadow: 0 0 16px currentColor;
    }

    .type-icon.user {
        background: rgba(76, 217, 130, 0.12);
        border: 1px solid rgba(76, 217, 130, 0.3);
        color: #4cd982;
    }

    .type-icon.signature {
        background: rgba(255, 181, 71, 0.12);
        border: 1px solid rgba(255, 181, 71, 0.3);
        color: #ffb547;
    }

    .type-icon.document {
        background: rgba(var(--glow), 0.12);
        border: 1px solid rgba(var(--glow), 0.3);
        color: rgba(var(--glow), 1);
    }

    .type-icon svg {
        width: 16px;
        height: 16px;
    }

    /* Ячейка с названием */
    .item-title {
        font-weight: 600;
        font-size: 13.5px;
        color: var(--text);
        display: block;
        max-width: 350px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .item-subtitle {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
        display: block;
        max-width: 350px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        opacity: 0.85;
    }

    /* Бейджи типов */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        border: 1px solid;
        transition: all .2s ease;
    }

    .type-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 0 12px currentColor;
    }

    .type-badge.user {
        background: rgba(76, 217, 130, 0.1);
        border-color: rgba(76, 217, 130, 0.3);
        color: #4cd982;
    }

    .type-badge.signature {
        background: rgba(255, 181, 71, 0.1);
        border-color: rgba(255, 181, 71, 0.3);
        color: #ffb547;
    }

    .type-badge.document {
        background: rgba(var(--glow), 0.1);
        border-color: rgba(var(--glow), 0.3);
        color: rgba(var(--glow), 1);
    }

    /* Детали */
    .item-details {
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
    }

    /* Дата и статус */
    .item-date {
        font-family: 'JetBrains Mono', monospace;
        font-size: 12px;
        color: var(--text);
        font-weight: 600;
    }

    .item-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    .item-status::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 6px currentColor;
    }

    .item-status.signed { color: #4cd982; }
    .item-status.processing { color: #ffb547; }
    .item-status.active { color: rgba(var(--glow), 1); }

    /* Кнопка действия (View) */
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--line);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        text-decoration: none;
        transition: all .2s ease;
    }

    .action-btn:hover {
        color: rgba(var(--glow), 1);
        border-color: rgba(var(--glow), 0.4);
        background: rgba(var(--glow), 0.1);
        box-shadow: 0 0 12px rgba(var(--glow), 0.3);
        transform: scale(1.08) translateX(2px);
    }

    .action-btn svg {
        width: 13px;
        height: 13px;
    }

    /* Пустое состояние */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(var(--glow), 0.08);
        border: 1px solid rgba(var(--glow), 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: rgba(var(--glow), 1);
        font-size: 24px;
        box-shadow: 0 0 20px rgba(var(--glow), 0.15);
    }

    .empty-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 4px;
    }

    .empty-sub {
        font-size: 12px;
        color: var(--muted);
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .search-page-custom h1 { font-size: 20px; }
        .search-table td, .search-table th { padding: 12px 10px; font-size: 11px; }
        .item-title { max-width: 180px; }
        .item-subtitle { max-width: 180px; }
    }
</style>

<div class="search-page-custom">
    {{-- Заголовок страницы --}}
    <div class="page-head-custom">
        <h1>
            <span class="accent-bar"></span>
            <span data-i18n="resultsFor">Результаты:</span>
            <span class="query-highlight">"{{ $query }}"</span>
        </h1>

        <div class="results-counter">
            <span class="count-num">{{ $results->count() }}</span>
            <span class="count-label" data-i18n="totalFound">Всего найдено</span>
        </div>
    </div>

    {{-- Таблица результатов --}}
    <div class="search-table-wrap">
        <table class="search-table">
            <thead>
            <tr>
                <th><span data-i18n="thObject">Объект и описание</span></th>
                <th class="center"><span data-i18n="thCategory">Категория</span></th>
                <th class="center"><span data-i18n="thDetails">Детали</span></th>
                <th class="center"><span data-i18n="thStatus">Статус / Дата</span></th>
                <th class="right"><span data-i18n="thAction">Действие</span></th>
            </tr>
            </thead>
            <tbody>
            @forelse($results as $item)
            @php
            $isUser = $item instanceof \App\Models\User;
            $isSig = $item instanceof \App\Models\DocumentSignature;

            if ($isUser) {
            $typeClass = 'user';
            $typeKey = 'typeUser';
            $title = $item->name ?? 'User #' . $item->id;
            $subtitle = $item->email ?? '';
            $details = $item->role ?? 'User';
            $date = $item->created_at?->format('d.m.Y') ?? '—';
            $statusKey = 'statusActive';
            $statusClass = 'active';
            $route = route('users.show', $item->id);
            } elseif ($isSig) {
            $typeClass = 'signature';
            $typeKey = 'typeSig';
            $title = ($item->document->title ?? 'Signature') . ' #' . $item->id;
            $subtitle = $item->document->title ?? 'N/A';
            $details = 'ID: ' . $item->id;
            $date = $item->created_at?->format('d.m.Y') ?? '—';
            $statusKey = $item->signed_at ? 'statusSigned' : 'statusProcess';
            $statusClass = $item->signed_at ? 'signed' : 'processing';
            $route = route('signatures.show', $item->id);
            } else {
            $typeClass = 'document';
            $typeKey = 'typeDoc';
            $title = $item->title ?? 'Document #' . $item->id;
            $subtitle = \Illuminate\Support\Str::limit($item->content ?? '', 60);
            $details = 'standard';
            $date = $item->created_at?->format('d.m.Y') ?? '—';
            $statusKey = 'statusActive';
            $statusClass = 'active';
            $route = route('documents.show', $item->id);
            }
            @endphp
            <tr>
                {{-- Объект --}}
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="type-icon {{ $typeClass }}">
                            @if($isUser)
                            {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($item->name ?? 'U', 0, 1)) }}
                            @elseif($isSig)
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            @endif
                        </div>
                        <div style="min-width:0; flex:1;">
                            <span class="item-title">{{ $title }}</span>
                            <span class="item-subtitle">
                                    @if($isUser)
                                        {{ $subtitle }}
                                    @elseif($isSig)
                                        <span data-i18n="docPrefix">Документ:</span> {{ $subtitle }}
                                    @else
                                        {{ $subtitle ?: '—' }}
                                    @endif
                                </span>
                        </div>
                    </div>
                </td>

                {{-- Категория --}}
                <td class="center">
                        <span class="type-badge {{ $typeClass }}" data-i18n="{{ $typeKey }}">
                            {{ $typeKey === 'typeUser' ? 'User' : ($typeKey === 'typeSig' ? 'Signature' : 'Document') }}
                        </span>
                </td>

                {{-- Детали --}}
                <td class="center">
                        <span class="item-details">
                            @if($isUser)
                                {{ $details }}
                            @elseif($isSig)
                                ID: {{ $item->id }}
                            @else
                                <span data-i18n="typeStandard">Стандартный</span>
                            @endif
                        </span>
                </td>

                {{-- Статус / Дата --}}
                <td class="center">
                    <div style="display:flex; flex-direction:column; align-items:center;">
                        <span class="item-date">{{ $date }}</span>
                        <span class="item-status {{ $statusClass }}" data-i18n="{{ $statusKey }}">
                                Активен
                            </span>
                    </div>
                </td>

                {{-- Действие --}}
                <td class="right">
                    <a href="{{ $route }}" class="action-btn" data-i18n-title="viewAction" title="View">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                        </div>
                        <div class="empty-title" data-i18n="noResults">По запросу ничего не найдено</div>
                        <div class="empty-sub" data-i18n="tryDifferentQuery">Попробуйте изменить запрос</div>
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ ПОИСКА
        // ============================================================
        const SEARCH_TRANSLATIONS = {
            ru: {
                resultsFor: 'Результаты:',
                totalFound: 'Всего найдено',
                thObject: 'Объект и описание',
                thCategory: 'Категория',
                thDetails: 'Детали',
                thStatus: 'Статус / Дата',
                thAction: 'Действие',
                typeUser: 'Пользователь',
                typeSig: 'Подпись',
                typeDoc: 'Документ',
                typeStandard: 'Стандартный',
                statusSigned: 'Подписан',
                statusProcess: 'В процессе',
                statusActive: 'Активен',
                docPrefix: 'Документ:',
                noResults: 'По запросу ничего не найдено',
                tryDifferentQuery: 'Попробуйте изменить запрос',
                viewAction: 'Просмотр'
            },
            tj: {
                resultsFor: 'Натиҷаҳо:',
                totalFound: 'Ҳамагӣ ёфт шуд',
                thObject: 'Объект ва тавсиф',
                thCategory: 'Категория',
                thDetails: 'Тафсилот',
                thStatus: 'Статус / Сана',
                thAction: 'Амал',
                typeUser: 'Корбар',
                typeSig: 'Имзо',
                typeDoc: 'Ҳуҷҷат',
                typeStandard: 'Стандартӣ',
                statusSigned: 'Имзо шуд',
                statusProcess: 'Дар ҷараён',
                statusActive: 'Фаъол',
                docPrefix: 'Ҳуҷҷат:',
                noResults: 'Тибқи дархост чизе ёфт нашуд',
                tryDifferentQuery: 'Кӯшиш кунед дархостро иваз кунед',
                viewAction: 'Дидан'
            },
            en: {
                resultsFor: 'Results:',
                totalFound: 'Total found',
                thObject: 'Object & Description',
                thCategory: 'Category',
                thDetails: 'Details',
                thStatus: 'Status / Date',
                thAction: 'Action',
                typeUser: 'User',
                typeSig: 'Signature',
                typeDoc: 'Document',
                typeStandard: 'Standard',
                statusSigned: 'Signed',
                statusProcess: 'In Process',
                statusActive: 'Active',
                docPrefix: 'Document:',
                noResults: 'No results found',
                tryDifferentQuery: 'Try changing your query',
                viewAction: 'View'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applySearchTranslations(lang) {
            const dict = SEARCH_TRANSLATIONS[lang] || SEARCH_TRANSLATIONS.ru;

            // 1) Переводим все элементы с data-i18n
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] !== undefined) el.textContent = dict[key];
            });

            // 2) Переводим placeholder
            document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                const key = el.getAttribute('data-i18n-placeholder');
                if (dict[key] !== undefined) el.setAttribute('placeholder', dict[key]);
            });

            // 3) Переводим title (подсказки)
            document.querySelectorAll('[data-i18n-title]').forEach(el => {
                const key = el.getAttribute('data-i18n-title');
                if (dict[key] !== undefined) el.setAttribute('title', dict[key]);
            });
        }

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applySearchTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applySearchTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applySearchTranslations(e.newValue);
            }
        });
    });
</script>
@endsection