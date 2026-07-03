@extends('layouts.admin')

@section('content')
    <div class="notif-container">
        <div class="notif-header">
            <div class="max-w-5xl mx-auto mb-10 flex justify-between items-end w-full">

                <h1 class="text-xl font-bold doc-main-title tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-500 rounded-full shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span>
                    <span data-i18n="notifTitle">Уведомления</span>
                </h1>

                <div class="header-right-actions" style="display: flex; align-items: center; gap: 15px;">
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <form action="{{ route('notifications.readAll') }}" method="POST" style="display:inline;" data-confirm-i18n="confirmReadAll">
                            @csrf
                            <button type="submit" class="btn-read-all" data-i18n="btnReadAll">Прочитать все</button>
                        </form>
                        <span class="unread-count"><span data-i18n="newNotifs">У вас новые:</span> {{ $unreadCount }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="notif-list">
            @forelse($notifications as $n)
                @php
                    // 1. Декодируем JSON данные уведомления
                    $data = is_array($n->data) ? $n->data : json_decode($n->data, true);
                    $docId = $data['document_id'] ?? ($data['id'] ?? null);
                    $type = $data['type'] ?? ($n->type ?? 'system');

                    // 2. Умный поиск реального имени отправителя по всем ключам JSON
                    $userName = $data['user_name'] ??
                                ($data['sender_name'] ??
                                ($data['from_user_name'] ??
                                ($data['user']['name'] ??
                                ($data['user'] ??
                                ($data['sender'] ?? null)))));

                    // Ищем email в JSON, если он там вдруг есть
                    $userEmail = $data['user_email'] ?? ($data['sender_email'] ?? null);

                    // 3. Умный поиск названия документа в JSON
                    $docTitle = $data['document_title'] ?? ($data['document'] ?? ($data['title'] ?? null));

                    // ====================================================================
                    // СУПЕР-ЗАЩИТА: Если в JSON пусто, идем напрямую в базу данных через Модели!
                    // ====================================================================
                    if ($docId) {
                        // Пытаемся найти документ в БД
                        $dbDoc = \App\Models\Document::find($docId);

                        if ($dbDoc) {
                            // Если названия документа нет в JSON — берем реальное из базы
                            if (!$docTitle) {
                                $docTitle = $dbDoc->title;
                            }

                            // Если имени или email нет, ищем юзера-создателя
                            if (!$userName || !$userEmail) {
                                $creatorUser = null;
                                if ($dbDoc->creator) {
                                    $creatorUser = $dbDoc->creator;
                                } else {
                                    $creatorUser = \App\Models\User::find($dbDoc->created_by);
                                }

                                if ($creatorUser) {
                                    if (!$userName) {
                                        $userName = $creatorUser->name;
                                    }
                                    if (!$userEmail) {
                                        $userEmail = $creatorUser->email;
                                    }
                                }
                            }
                        }
                    }

                    // Если имя нашли, а email всё еще нет, попробуем найти юзера по имени
                    if ($userName && !$userEmail && $userName !== 'Владелец') {
                        $findUser = \App\Models\User::where('name', $userName)->first();
                        if ($findUser) {
                            $userEmail = $findUser->email;
                        }
                    }

                    // Если вообще ничего не помогло, ставим дефолтные значения
                    if (!$userName) $userName = 'Владелец';
                    if (!$docTitle) $docTitle = 'Документ #' . ($docId ?? '');
                    // ====================================================================

                    $commentText = $data['comment_preview'] ?? ($data['comment_text'] ?? null);

                    $actionKey = match($type) {
                        'assigned' => 'notifAssigned',
                        'comment'  => 'notifCommented',
                        'signed'   => 'notifSigned',
                        'created'  => 'notifCreated',
                        default    => 'notifDefault'
                    };
                @endphp

                <div class="notif-card {{ !$n->is_read ? 'unread' : '' }}">
                    <div class="notif-item">
                        <div class="notif-icon-wrapper type-{{ $type }}">
                            @switch($type)
                                @case('assigned') <span class="icon">📌</span> @break
                                @case('comment')  <span class="icon">💬</span> @break
                                @case('signed')   <span class="icon">✍️</span> @break
                                @case('created')  <span class="icon">📄</span> @break
                                @default          <span class="icon">🔔</span>
                            @endswitch
                        </div>

                        <div class="notif-content">
                            <div class="notif-title">
                                <span class="user-name">
                                    {{ $userName }}
                                    @if($userEmail)
                                        <span class="user-email">({{ $userEmail }})</span>
                                    @endif
                                </span>
                                <span data-i18n="{{ $actionKey }}"></span>

                                @if($docId)
                                    <a href="{{ url('/documents/' . $docId) }}" class="doc-link">
                                        «{{ $docTitle }}»
                                    </a>
                                @else
                                    <span class="doc-name-no-link">«{{ $docTitle }}»</span>
                                @endif
                            </div>

                            @if($commentText)
                                <div class="notif-quote">{{ $commentText }}</div>
                            @endif

                            <div class="notif-meta">
                                <span class="time-tag">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $n->created_at->diffForHumans() }}
                                </span>

                                <div class="notif-actions">
                                    @if(!$n->is_read)
                                        <form action="{{ route('notifications.read', $n->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-link mark-read" data-i18n="btnMarkRead">Прочитать</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $n->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-link delete" data-i18n="btnDelete" data-confirm-i18n="confirmDelete">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if(!$n->is_read)
                            <div class="unread-dot"></div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="notif-empty">
                    <div class="empty-icon">🔔</div>
                    <p data-i18n="noNotifs">У вас пока нет уведомлений</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="notif-pagination">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    <style>
        :root {
            --primary-color: #4f46e5;
            --bg-unread: #f8faff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #cbd5e1;
            --border-unread: rgba(79, 70, 229, 0.4);
            --danger: #ef4444;
        }
        .notif-container { max-width: 700px; margin: 20px auto; padding: 0 15px; font-family: 'Inter', sans-serif; }
        .notif-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .unread-count { font-size: 13px; background: var(--primary-color); color: white; padding: 4px 12px; border-radius: 20px; font-weight: 600; }
        .notif-list { display: flex; flex-direction: column; gap: 12px; }

        .btn-read-all {
            background: transparent;
            color: var(--primary-color);
            border: 1.5px solid var(--primary-color);
            padding: 4px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-read-all:hover {
            background: var(--primary-color);
            color: white;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .notif-card {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 16px;
            transition: all 0.2s ease;
            position: relative;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }
        .notif-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            border-color: #94a3b8;
        }
        .notif-item { display: flex; gap: 16px; padding: 16px; }

        .notif-card.unread {
            background: var(--bg-unread);
            border-color: var(--border-unread);
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.05);
        }
        .notif-card.unread:hover {
            border-color: var(--primary-color);
        }

        .notif-icon-wrapper { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; border: 1px solid rgba(0,0,0,0.04); }
        .type-assigned { background: #fff7ed; }
        .type-comment  { background: #f0fdf4; }
        .type-signed   { background: #eff6ff; }
        .type-created  { background: #f5f3ff; }
        .notif-content { flex: 1; position: relative; }
        .notif-title { font-size: 15px; line-height: 1.4; color: var(--text-main); }
        .user-name { font-weight: 700; }
        .user-email { font-weight: 400; color: var(--text-muted); font-size: 13px; margin-left: 4px; }
        .doc-link { font-weight: 700; color: var(--primary-color); text-decoration: underline; padding: 2px 4px; border-radius: 4px; background: rgba(79, 70, 229, 0.05); }
        .doc-name-no-link { color: #9ca3af; font-size: 12px; }
        .notif-quote { margin-top: 8px; padding: 10px; background: rgba(0,0,0,0.03); border-radius: 8px; font-size: 13px; color: #4b5563; border-left: 3px solid #cbd5e1; font-style: italic; border-top: 1px solid rgba(0,0,0,0.02); border-right: 1px solid rgba(0,0,0,0.02); border-bottom: 1px solid rgba(0,0,0,0.02); }
        .notif-meta { margin-top: 10px; display: flex; justify-content: space-between; align-items: center; }
        .time-tag { font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 4px; }
        .notif-actions { display: flex; gap: 12px; position: relative; z-index: 10; }
        .action-link { background: none; border: none; padding: 0; font-size: 12px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .action-link:hover { opacity: 0.8; text-decoration: underline; }
        .mark-read { color: var(--primary-color); }
        .delete { color: var(--text-muted); }
        .delete:hover { color: var(--danger); }
        .unread-dot { width: 10px; height: 10px; background: var(--primary-color); border-radius: 50%; position: absolute; right: 16px; top: 20px; }

        .notif-empty { text-align: center; padding: 40px; border: 1.5px dashed var(--border-color); border-radius: 16px; color: var(--text-muted); }
        .empty-icon { font-size: 32px; margin-bottom: 10px; opacity: 0.5; }
        .notif-pagination { margin-top: 20px; display: flex; justify-content: center; }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ УВЕДОМЛЕНИЙ
        // (дополняет глобальный TRANSLATIONS из layouts/admin.blade.php)
        // ============================================================
        const NOTIF_TRANSLATIONS = {
            ru: {
                notifTitle: 'Уведомления',
                newNotifs: 'У вас новые:',
                notifAssigned: ' назначил вам документ',
                notifCommented: ' оставил комментарий к ',
                notifSigned: ' подписал документ',
                notifCreated: ' создал документ',
                notifDefault: ' отправил уведомление',
                btnMarkRead: 'Прочитать',
                btnReadAll: 'Прочитать все',
                btnDelete: 'Удалить',
                noNotifs: 'У вас пока нет уведомлений',
                confirmDelete: 'Удалить?',
                confirmReadAll: 'Вы уверены, что хотите отметить все уведомления как прочитанные?'
            },
            tj: {
                notifTitle: 'Огоҳиномаҳо',
                newNotifs: 'Нав доред:',
                notifAssigned: ' ҳуҷҷатро ба шумо супорид',
                notifCommented: ' шарҳ гузошт ба ',
                notifSigned: ' ҳуҷҷатро имзо кард',
                notifCreated: ' ҳуҷҷат сохт',
                notifDefault: ' огоҳинома фиристод',
                btnMarkRead: 'Хондан',
                btnReadAll: 'Ҳамаро хондан',
                btnDelete: 'Нест кардан',
                noNotifs: 'Шумо огоҳинома надоред',
                confirmDelete: 'Нест кунем?',
                confirmReadAll: 'Шумо мутмаин ҳастед, ки мехоҳед ҳамаи огоҳиномаҳоро ҳамчун хондашуда қайд кунед?'
            },
            en: {
                notifTitle: 'Notifications',
                newNotifs: 'New ones:',
                notifAssigned: ' assigned a document',
                notifCommented: ' commented on ',
                notifSigned: ' signed the document',
                notifCreated: ' created a document',
                notifDefault: ' sent a notification',
                btnMarkRead: 'Read',
                btnReadAll: 'Read all',
                btnDelete: 'Delete',
                noNotifs: 'No notifications',
                confirmDelete: 'Delete?',
                confirmReadAll: 'Are you sure you want to mark all notifications as read?'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ НА ЭТОЙ СТРАНИЦЕ
        // ============================================================
        function applyNotifTranslations(lang) {
            const dict = NOTIF_TRANSLATIONS[lang] || NOTIF_TRANSLATIONS.ru;

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

            // 3) Переводим title
            document.querySelectorAll('[data-i18n-title]').forEach(el => {
                const key = el.getAttribute('data-i18n-title');
                if (dict[key] !== undefined) el.setAttribute('title', dict[key]);
            });

            // 4) Обработка confirm-диалогов (и на формах, и на кнопках)
            document.querySelectorAll('[data-confirm-i18n]').forEach(el => {
                const key = el.getAttribute('data-confirm-i18n');
                const message = dict[key] || 'Are you sure?';

                // Клонируем элемент, чтобы сбросить старые обработчики
                const newEl = el.cloneNode(true);
                el.parentNode.replaceChild(newEl, el);

                // Если дата-атрибут висит на самой форме
                if (newEl.tagName === 'FORM') {
                    newEl.onsubmit = (e) => {
                        if (!confirm(message)) e.preventDefault();
                    };
                } else {
                    // Если висит на кнопке внутри формы
                    const form = newEl.closest('form');
                    if (form) {
                        form.onsubmit = (e) => {
                            if (!confirm(message)) e.preventDefault();
                        };
                    }
                }
            });
        }

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyNotifTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        //    (когда юзер кликает на 🇷🇺/🇹🇯/🇬🇧 в админке)
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyNotifTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyNotifTranslations(e.newValue);
            }
        });
    });
</script>
@endsection
