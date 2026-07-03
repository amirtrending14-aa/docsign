
@extends('layouts.admin')

@section('content')

<style>
    .users-tree-page {
        min-height: 100vh;
        padding: 40px 24px 60px;
        color: var(--text);
        font-family: 'Inter', sans-serif;
        position: relative;

    }
    .tree-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        filter: blur(100px);
        opacity: 0.35;
    }
    .tree-blob-1 {
        top: -100px; left: -100px;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(var(--glow), 0.3) 0%, transparent 70%);
        animation: blobFloat 20s ease-in-out infinite;
    }
    .tree-blob-2 {
        bottom: -100px; right: -100px;
        width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, transparent 70%);
        animation: blobFloat 25s ease-in-out infinite reverse;
    }
    @keyframes blobFloat {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(30px, -30px); }
    }
    .users-topbar {
        max-width: 1400px;
        margin: 0 auto 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        position: relative;
        z-index: 1;
    }
    .users-topbar-left { display: flex; align-items: center; gap: 16px; }
    .users-topbar-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.4));
        display: grid; place-items: center;
        box-shadow: 0 0 24px rgba(var(--glow), 0.5);
    }
    .users-topbar-icon svg { width: 26px; height: 26px; color: #0a0d14; }
    .users-topbar-title { font-size: 24px; font-weight: 800; color: var(--text); margin: 0; }

    .stats-row {
        max-width: 1400px;
        margin: 0 auto 32px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        position: relative;
        z-index: 1;
    }
    .stat-card {
        background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .stat-icon {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: grid; place-items: center;
        font-size: 24px;
        background: rgba(var(--glow), 0.18);
        border: 1px solid rgba(var(--glow), 0.35);
        color: rgba(var(--glow), 1);
    }
    .stat-value { font-size: 28px; font-weight: 800; color: var(--text); }
    .stat-label {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        font-weight: 600;
    }

    .tree-wrap {
        max-width: 1400px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 40px 32px;
        min-height: 600px;
    }
    .tree-header { text-align: center; margin-bottom: 40px; }
    .tree-header h2 { font-size: 26px; font-weight: 800; color: var(--text); margin: 0 0 8px; }

    .level-section { margin-bottom: 40px; position: relative; }
    .level-connector {
        width: 2px; height: 40px;
        margin: 0 auto 30px;
        background: linear-gradient(180deg, rgba(var(--glow), 0.8), rgba(var(--glow), 0.2));
    }
    .level-header { text-align: center; margin-bottom: 30px; }
    .level-bar {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 9px 20px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        background: linear-gradient(135deg, rgba(var(--glow), 0.2), rgba(var(--glow), 0.1));
        border: 1px solid rgba(var(--glow), 0.4);
    }

    .users-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        position: relative;
        padding: 20px 0;
    }
    .user-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 2px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        width: 220px;
        z-index: 2;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .user-card:hover {
        border-color: rgba(var(--glow), 0.6);
        box-shadow: 0 16px 36px -12px rgba(var(--glow), 0.5);
        transform: translateY(-3px);
    }
    .user-photo {
        position: relative;
        width: 100%;
        height: 110px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(var(--glow), 0.4), rgba(168, 85, 247, 0.3));
    }
    .user-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .user-photo-placeholder {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 48px; font-weight: 900;
        color: rgba(255,255,255,0.9);
    }
    .user-photo-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, transparent 40%, rgba(10, 13, 20, 0.95) 100%);
    }
    .photo-top {
        position: absolute;
        top: 8px; left: 8px; right: 8px;
        display: flex;
        justify-content: space-between;
    }
    .status-pill {
        padding: 4px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(10, 13, 20, 0.92);
        border: 1px solid rgba(255,255,255,0.18);
    }
    .status-pill.online  { color: #4cd982; }
    .status-pill.offline { color: #ff6363; }
    .level-pill {
        padding: 4px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 800;
        background: rgba(var(--glow), 0.95);
        color: #0a0d14;
    }
    .user-body { padding: 14px 12px; }
    .user-name {
        font-size: 15px; font-weight: 800;
        color: var(--text);
        text-align: center;
        margin: 0 0 8px;
    }
    .user-role {
        display: block;
        text-align: center;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: rgba(var(--glow), 1);
        background: rgba(var(--glow), 0.15);
        border: 1px solid rgba(var(--glow), 0.3);
        margin-bottom: 10px;
    }
    .contact-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 6px;
        font-size: 11px;
        color: var(--text);
    }
    .contact-row i { width: 13px; height: 13px; color: rgba(var(--glow), 0.9); }

    .svg-arrows {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        pointer-events: none;
        z-index: 1;
    }

    /* Анимация "текущих" документов по стрелке */
    @keyframes flowDash {
        to { stroke-dashoffset: -24; }
    }
    .arrow-flow {
        animation: flowDash 1.2s linear infinite;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(8px);
    }
    .modal-overlay.active { display: flex; }
    .modal-content {
        background: linear-gradient(180deg, rgba(30, 30, 50, 0.98), rgba(20, 20, 40, 0.98));
        border: 1px solid rgba(var(--glow), 0.3);
        border-radius: 16px;
        padding: 32px;
        max-width: 800px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(var(--glow), 0.3);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .modal-title { font-size: 22px; font-weight: 800; color: var(--text); margin: 0; }
    .modal-close {
        width: 36px; height: 36px;
        border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: var(--text);
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: all 0.2s;
    }
    .modal-close:hover {
        background: rgba(255, 100, 100, 0.2);
        border-color: rgba(255, 100, 100, 0.4);
    }
    .document-list { display: flex; flex-direction: column; gap: 12px; }
    .document-item {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 16px;
        transition: all 0.2s;
    }
    .document-item:hover {
        background: rgba(255,255,255,0.05);
        border-color: rgba(var(--glow), 0.3);
    }
    .doc-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 8px;
    }
    .doc-title { font-size: 15px; font-weight: 700; color: var(--text); margin: 0; }
    .doc-status {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .doc-status.pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; }
    .doc-status.approved { background: rgba(76, 217, 130, 0.2); color: #4cd982; }
    .doc-status.rejected { background: rgba(255, 99, 99, 0.2); color: #ff6363; }
    .doc-meta { display: flex; gap: 16px; font-size: 12px; color: var(--muted); flex-wrap: wrap; }
    .doc-meta-item { display: flex; align-items: center; gap: 6px; }
    .doc-meta-item i { color: rgba(var(--glow), 0.7); }
</style>



<!-- ВАШ CSS ОСТАЕТСЯ БЕЗ ИЗМЕНЕНИЙ -->

<div class="users-tree-page">
    <div class="tree-blob tree-blob-1"></div>
    <div class="tree-blob tree-blob-2"></div>

    <div class="users-topbar">
        <div class="users-topbar-left">
            <div class="users-topbar-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <div class="users-topbar-title">{{ $companyName }}</div>
            </div>
        </div>
    </div>

    <div class="tree-wrap" id="treeWrap">
        <div class="tree-header">
            <h2 data-i18n="tree_doc_flow_title">Документооборот</h2>
            <p style="color: var(--muted); font-size: 13px;" data-i18n="tree_doc_flow_subtitle">Нажмите на стрелку или карточку чтобы увидеть детали документов</p>
        </div>

        <svg class="svg-arrows" id="svgArrows"></svg>

        @foreach($groupedByLevel as $level => $levelUsers)
        @if(!$loop->first)
        <div class="level-connector"></div>
        @endif

        <div class="level-section">
            <div class="users-grid">
                @foreach($levelUsers as $user)
                <div class="user-card" id="user-{{ $user->id }}" data-user-id="{{ $user->id }}">
                    <div class="user-photo">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                        @else
                        <div class="user-photo-placeholder">
                            {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="user-photo-gradient"></div>
                        <div class="photo-top">
                            <span class="status-pill {{ $user->isOnline() ? 'online' : 'offline' }}"
                                  data-i18n="{{ $user->isOnline() ? 'tree_status_online' : 'tree_status_offline' }}">
                                {{ $user->isOnline() ? 'Онлайн' : 'Офлайн' }}
                            </span>
                            <span class="level-pill">L{{ $user->level }}</span>
                        </div>
                    </div>
                    <div class="user-body">
                        <h3 class="user-name">{{ $user->name }}</h3>
                        <span class="user-role">{{ $user->role }}</span>
                        <div class="contact-row">
                            <i class="bi bi-envelope-fill"></i>
                            <span>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="modal-overlay" id="documentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle" data-i18n="tree_documents_title">Документы</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="document-list" id="documentList"></div>
    </div>
</div>

@push('scripts')
<script>
    // ============================================================
    // ===== ЛОКАЛЬНЫЙ СЛОВАРЬ ПЕРЕВОДОВ ДЛЯ СТРАНИЦЫ USERS TREE =====
    // ============================================================
    const TREE_TRANSLATIONS = {
        ru: {
            tree_doc_flow_title: 'Документооборот',
            tree_doc_flow_subtitle: 'Нажмите на стрелку или карточку чтобы увидеть детали документов',
            tree_status_online: 'Онлайн',
            tree_status_offline: 'Офлайн',
            tree_documents_title: 'Документы',
            tree_docs_label: 'док.',
            tree_no_docs_info: 'Нет информации о документах',
            tree_no_docs: 'Нет документов',
            tree_incoming_doc: 'Входящий',
            tree_outgoing_doc: 'Исходящий',
            tree_unknown: 'Неизвестно',
            tree_documents_from: 'Документы:',
            tree_documents_of_user: 'Документы пользователя:',
            tree_sender: 'Отправитель',
            tree_receiver: 'Получатель',
            tree_user: 'Пользователь',
            tree_no_title: 'Без названия'
        },
        tj: {
            tree_doc_flow_title: 'Ҳуҷҷатгардонӣ',
            tree_doc_flow_subtitle: 'Ба тир ё корточка пахш кунед, то тафсилотҳои ҳуҷҷатҳоро бинед',
            tree_status_online: 'Онлайн',
            tree_status_offline: 'Офлайн',
            tree_documents_title: 'Ҳуҷҷатҳо',
            tree_docs_label: 'ҳуҷҷат',
            tree_no_docs_info: 'Маълумот оиди ҳуҷҷатҳо нест',
            tree_no_docs: 'Ҳуҷҷатҳо нест',
            tree_incoming_doc: 'Воридшаванда',
            tree_outgoing_doc: 'Содиршаванда',
            tree_unknown: 'Номаълум',
            tree_documents_from: 'Ҳуҷҷатҳо:',
            tree_documents_of_user: 'Ҳуҷҷатҳои корбар:',
            tree_sender: 'Фиристанда',
            tree_receiver: 'Қабулкунанда',
            tree_user: 'Корбар',
            tree_no_title: 'Бе ном'
        },
        en: {
            tree_doc_flow_title: 'Document Flow',
            tree_doc_flow_subtitle: 'Click on arrow or card to see document details',
            tree_status_online: 'Online',
            tree_status_offline: 'Offline',
            tree_documents_title: 'Documents',
            tree_docs_label: 'docs',
            tree_no_docs_info: 'No document information',
            tree_no_docs: 'No documents',
            tree_incoming_doc: 'Incoming',
            tree_outgoing_doc: 'Outgoing',
            tree_unknown: 'Unknown',
            tree_documents_from: 'Documents:',
            tree_documents_of_user: 'User documents:',
            tree_sender: 'Sender',
            tree_receiver: 'Receiver',
            tree_user: 'User',
            tree_no_title: 'No title'
        }
    };

    // Функция применения переводов для этой страницы
    function applyTreeTranslations(lang) {
        if (!TREE_TRANSLATIONS[lang]) lang = 'ru';
        const dict = TREE_TRANSLATIONS[lang];

        // Применяем ко всем элементам с data-i18n
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (dict[key] !== undefined) {
                el.textContent = dict[key];
            }
        });

        // Обновляем бейджи на стрелках
        document.querySelectorAll('.arrow-badge-text').forEach(el => {
            const count = el.dataset.count;
            el.textContent = `${count} ${dict.tree_docs_label}`;
        });
    }

    // Слушаем глобальное событие смены языка из layout
    window.addEventListener('docsign:lang-changed', function(e) {
        const lang = e.detail.lang;
        applyTreeTranslations(lang);
        // Перерисовываем стрелки чтобы обновились бейджи
        if (typeof drawArrows === 'function') {
            setTimeout(drawArrows, 50);
        }
    });

    // Применяем переводы при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        const currentLang = localStorage.getItem('docsign_lang') || 'ru';
        applyTreeTranslations(currentLang);
    });

    // ============================================================
    // ===== ОСНОВНОЙ КОД СТРАНИЦЫ =====
    // ============================================================
    const connections = @json($connections);
    const documentCounts = @json($documentCounts);
    const documentDetails = @json($documentDetails);
    const users = @json($users->toArray());

    document.addEventListener('DOMContentLoaded', function() {
        requestAnimationFrame(() => {
            setTimeout(drawArrows, 300);
        });
        window.addEventListener('resize', () => {
            clearTimeout(window._arrowResizeTimer);
            window._arrowResizeTimer = setTimeout(drawArrows, 200);
        });

        document.querySelectorAll('.user-card').forEach(card => {
            card.addEventListener('click', function() {
                showUserDocuments(parseInt(this.dataset.userId));
            });
        });
    });

    /**
     * Возвращает точку на ВЕРХНЕЙ или НИЖНЕЙ грани карточки,
     * в зависимости от того, где находится цель (выше или ниже).
     * Если цель на том же уровне — берём боковую грань.
     */
    function getAnchorPoint(rect, wrapRect, targetX, targetY) {
        const cx = rect.left + rect.width / 2 - wrapRect.left;
        const cy = rect.top + rect.height / 2 - wrapRect.top;
        const dx = targetX - cx;
        const dy = targetY - cy;

        const halfW = rect.width / 2;
        const halfH = rect.height / 2;

        // Если цель почти на той же высоте — выходим сбоку
        if (Math.abs(dy) < halfH * 0.6) {
            return {
                x: cx + (dx >= 0 ? halfW : -halfW),
                y: cy,
                side: dx >= 0 ? 'right' : 'left'
            };
        }

        // Иначе — сверху или снизу
        if (dy < 0) {
            // Цель выше → выходим из ВЕРХА карточки
            return { x: cx, y: rect.top - wrapRect.top, side: 'top' };
        } else {
            // Цель ниже → выходим из НИЗА карточки
            return { x: cx, y: rect.top + rect.height - wrapRect.top, side: 'bottom' };
        }
    }

    function drawArrows() {
        const svg = document.getElementById('svgArrows');
        const treeWrap = document.getElementById('treeWrap');
        if (!svg || !treeWrap) return;

        svg.innerHTML = '';
        const wrapRect = treeWrap.getBoundingClientRect();
        svg.setAttribute('width', wrapRect.width);
        svg.setAttribute('height', wrapRect.height);
        svg.style.overflow = 'visible';

        const strokeColor = 'rgb(0, 242, 254)';
        const arrowW = 16;
        const arrowH = 12;

        const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
        defs.innerHTML = `
            <marker id="arrowhead" markerWidth="${arrowW}" markerHeight="${arrowH}"
                    refX="${arrowW - 1}" refY="${arrowH / 2}" orient="auto" markerUnits="userSpaceOnUse">
                <path d="M 0 1 L ${arrowW} ${arrowH/2} L 0 ${arrowH - 1} Z"
                      fill="${strokeColor}" stroke="${strokeColor}" stroke-width="1" />
            </marker>
        `;
        svg.appendChild(defs);

        const allPairs = [];
        Object.keys(connections).forEach(fromId => {
            connections[fromId].forEach(toId => {
                allPairs.push({
                    from: parseInt(fromId),
                    to: parseInt(toId),
                    count: documentCounts[`${fromId}-${toId}`] || 1
                });
            });
        });

        // Группируем пары по (from,to) чтобы разнести двунаправленные
        const pairOffsets = new Map();
        allPairs.forEach(pair => {
            const hasReverse = allPairs.some(p => p.from === pair.to && p.to === pair.from);
            if (hasReverse) {
                const side = pair.from < pair.to ? 1 : -1;
                pairOffsets.set(pair, { x: 18 * side, y: 0 });
            } else {
                pairOffsets.set(pair, { x: 0, y: 0 });
            }
        });

        // Получаем текущий язык для локализации
        const currentLang = localStorage.getItem('docsign_lang') || 'ru';
        const dict = TREE_TRANSLATIONS[currentLang] || TREE_TRANSLATIONS.ru;

        allPairs.forEach((pair) => {
            const fromCard = document.getElementById(`user-${pair.from}`);
            const toCard = document.getElementById(`user-${pair.to}`);
            if (!fromCard || !toCard) return;

            const fromRect = fromCard.getBoundingClientRect();
            const toRect = toCard.getBoundingClientRect();

            const fcx = fromRect.left + fromRect.width / 2 - wrapRect.left;
            const fcy = fromRect.top + fromRect.height / 2 - wrapRect.top;
            const tcx = toRect.left + toRect.width / 2 - wrapRect.left;
            const tcy = toRect.top + toRect.height / 2 - wrapRect.top;

            const fromAnchor = getAnchorPoint(fromRect, wrapRect, tcx, tcy);
            const toAnchor = getAnchorPoint(toRect, wrapRect, fcx, fcy);

            const offset = pairOffsets.get(pair);
            let startX = fromAnchor.x + offset.x;
            let startY = fromAnchor.y + offset.y;
            let endX = toAnchor.x + offset.x;
            let endY = toAnchor.y + offset.y;

            const dx = endX - startX;
            const dy = endY - startY;
            const len = Math.sqrt(dx * dx + dy * dy);
            if (len > 0) {
                const shortenBy = 3;
                endX -= (dx / len) * shortenBy;
                endY -= (dy / len) * shortenBy;
            }

            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            let d;

            if (fromAnchor.side === 'top' || fromAnchor.side === 'bottom') {
                const midY = (startY + endY) / 2;
                d = `M ${startX} ${startY} C ${startX} ${midY}, ${endX} ${midY}, ${endX} ${endY}`;
            } else {
                const midX = (startX + endX) / 2;
                d = `M ${startX} ${startY} C ${midX} ${startY}, ${midX} ${endY}, ${endX} ${endY}`;
            }

            path.setAttribute('d', d);
            path.setAttribute('stroke', strokeColor);
            path.setAttribute('stroke-width', '3');
            path.setAttribute('fill', 'none');
            path.setAttribute('marker-end', 'url(#arrowhead)');
            path.style.filter = 'drop-shadow(0 0 6px rgba(0, 242, 254, 0.85))';
            path.style.opacity = '0.95';
            svg.appendChild(path);

            const flowPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            flowPath.setAttribute('d', d);
            flowPath.setAttribute('stroke', '#ffffff');
            flowPath.setAttribute('stroke-width', '1.5');
            flowPath.setAttribute('stroke-dasharray', '4 20');
            flowPath.setAttribute('fill', 'none');
            flowPath.setAttribute('opacity', '0.7');
            flowPath.setAttribute('class', 'arrow-flow');
            flowPath.style.pointerEvents = 'none';
            svg.appendChild(flowPath);

            // Бейдж с количеством документов
            if (pair.count > 0) {
                const midX = (startX + endX) / 2;
                const midY = (startY + endY) / 2;

                const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                g.style.cursor = 'pointer';
                g.style.pointerEvents = 'all';
                g.addEventListener('click', () => showDocumentsBetween(pair.from, pair.to));

                const labelText = `${pair.count} ${dict.tree_docs_label}`;
                const pillWidth = Math.max(60, labelText.length * 7 + 24);
                const pillHeight = 26;

                const pill = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                pill.setAttribute('x', midX - pillWidth / 2);
                pill.setAttribute('y', midY - pillHeight / 2);
                pill.setAttribute('width', pillWidth);
                pill.setAttribute('height', pillHeight);
                pill.setAttribute('rx', pillHeight / 2);
                pill.setAttribute('ry', pillHeight / 2);
                pill.setAttribute('fill', 'rgba(10, 13, 20, 0.98)');
                pill.setAttribute('stroke', strokeColor);
                pill.setAttribute('stroke-width', '2');
                pill.style.filter = 'drop-shadow(0 0 8px rgba(0, 242, 254, 0.9))';
                g.appendChild(pill);

                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                icon.setAttribute('x', midX - pillWidth / 2 + 12);
                icon.setAttribute('y', midY);
                icon.setAttribute('fill', strokeColor);
                icon.setAttribute('font-size', '13');
                icon.setAttribute('text-anchor', 'middle');
                icon.setAttribute('dominant-baseline', 'central');
                icon.textContent = '📄';
                g.appendChild(icon);

                const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                text.setAttribute('x', midX + 6);
                text.setAttribute('y', midY);
                text.setAttribute('fill', '#ffffff');
                text.setAttribute('font-size', '12');
                text.setAttribute('font-weight', '800');
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('dominant-baseline', 'central');
                text.textContent = labelText;
                text.classList.add('arrow-badge-text');
                text.dataset.count = pair.count;
                g.appendChild(text);

                svg.appendChild(g);
            }
        });
    }

    function showDocumentsBetween(fromId, toId) {
        const key = `${fromId}-${toId}`;
        const docs = documentDetails[key] || [];
        const fromUser = users[fromId];
        const toUser = users[toId];

        const modalTitle = document.getElementById('modalTitle');
        const documentList = document.getElementById('documentList');

        const currentLang = localStorage.getItem('docsign_lang') || 'ru';
        const dict = TREE_TRANSLATIONS[currentLang] || TREE_TRANSLATIONS.ru;

        modalTitle.textContent = `${dict.tree_documents_from} ${fromUser?.name || dict.tree_sender} → ${toUser?.name || dict.tree_receiver}`;

        if (docs.length === 0) {
            documentList.innerHTML = `<p style="text-align:center; color:var(--muted);">${dict.tree_no_docs_info}</p>`;
        } else {
            documentList.innerHTML = docs.map(doc => `
                <div class="document-item">
                    <div class="doc-header">
                        <h4 class="doc-title">${doc.title || dict.tree_no_title}</h4>
                        ${doc.status ? `<span class="doc-status ${doc.status}">${doc.status}</span>` : ''}
                    </div>
                    <div class="doc-meta">
                        ${doc.type ? `<div class="doc-meta-item"><i class="bi bi-file-earmark"></i> ${doc.type}</div>` : ''}
                        ${doc.created_at ? `<div class="doc-meta-item"><i class="bi bi-calendar"></i> ${new Date(doc.created_at).toLocaleDateString(currentLang === 'ru' ? 'ru-RU' : currentLang === 'tj' ? 'tg-TJ' : 'en-US')}</div>` : ''}
                        ${doc.id ? `<div class="doc-meta-item"><i class="bi bi-hash"></i> ID: ${doc.id}</div>` : ''}
                    </div>
                </div>
            `).join('');
        }

        document.getElementById('documentModal').classList.add('active');
    }

    function showUserDocuments(userId) {
        const user = users[userId];
        const modalTitle = document.getElementById('modalTitle');
        const documentList = document.getElementById('documentList');

        const currentLang = localStorage.getItem('docsign_lang') || 'ru';
        const dict = TREE_TRANSLATIONS[currentLang] || TREE_TRANSLATIONS.ru;

        modalTitle.textContent = `${dict.tree_documents_of_user} ${user?.name || dict.tree_user}`;

        const allDocs = [];
        Object.keys(documentDetails).forEach(key => {
            const [fromId, toId] = key.split('-').map(Number);
            if (fromId === userId || toId === userId) {
                documentDetails[key].forEach(doc => {
                    allDocs.push({
                        ...doc,
                        direction: fromId === userId ? dict.tree_outgoing_doc : dict.tree_incoming_doc,
                        counterpart: fromId === userId ? users[toId]?.name : users[fromId]?.name
                    });
                });
            }
        });

        if (allDocs.length === 0) {
            documentList.innerHTML = `<p style="text-align:center; color:var(--muted);">${dict.tree_no_docs}</p>`;
        } else {
            documentList.innerHTML = allDocs.map(doc => `
                <div class="document-item">
                    <div class="doc-header">
                        <h4 class="doc-title">${doc.title || dict.tree_no_title}</h4>
                        <span class="doc-status" style="background:rgba(var(--glow), 0.2); color:rgba(var(--glow), 1);">
                            ${doc.direction}
                        </span>
                    </div>
                    <div class="doc-meta">
                        <div class="doc-meta-item"><i class="bi bi-person"></i> ${doc.counterpart || dict.tree_unknown}</div>
                        ${doc.type ? `<div class="doc-meta-item"><i class="bi bi-file-earmark"></i> ${doc.type}</div>` : ''}
                        ${doc.created_at ? `<div class="doc-meta-item"><i class="bi bi-calendar"></i> ${new Date(doc.created_at).toLocaleDateString(currentLang === 'ru' ? 'ru-RU' : currentLang === 'tj' ? 'tg-TJ' : 'en-US')}</div>` : ''}
                    </div>
                </div>
            `).join('');
        }

        document.getElementById('documentModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('documentModal').classList.remove('active');
    }

    document.getElementById('documentModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush

@endsection