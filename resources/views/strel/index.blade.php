    @extends('layouts.admin')

    @section('content')

    <style>
        .users-tree-page {
            min-height: 100vh;
            padding: 40px 24px 60px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            position: relative;
            --card-scale: 1;
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

        /* Кнопки зума */
        .zoom-toolbar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 6px;
        }
        .zoom-btn {
            width: 34px; height: 34px;
            border-radius: 8px;
            border: 1px solid rgba(var(--glow), 0.35);
            background: rgba(var(--glow), 0.12);
            color: var(--text);
            font-size: 18px;
            font-weight: 800;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: all 0.15s;
            line-height: 1;
        }
        .zoom-btn:hover { background: rgba(var(--glow), 0.25); }
        .zoom-btn:active { transform: scale(0.92); }
        .zoom-level {
            min-width: 46px;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
        }

        .tree-wrap {
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 40px 200px;
            min-height: 600px;
            overflow-x: auto;
        }
        .tree-header { text-align: center; margin-bottom: 40px; }
        .tree-header h2 { font-size: 26px; font-weight: 800; color: var(--text); margin: 0 0 8px; }

        /* Колонка уровней: каждый уровень — отдельный ряд-контейнер,
           внутри которого все карточки этого уровня становятся рядом друг с другом */
        .users-column {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: calc(56px * var(--card-scale));
            position: relative;
            width: 100%;
        }
        .level-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: calc(24px * var(--card-scale));
            width: 100%;
        }

        .user-card {
            position: relative;
            background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
            border: 2px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            width: calc(220px * var(--card-scale));
            z-index: 2;
            cursor: pointer;
            transition: border-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            flex-shrink: 0;
        }
        .user-card:hover {
            border-color: rgba(var(--glow), 0.6);
            box-shadow: 0 16px 36px -12px rgba(var(--glow), 0.5);
            transform: translateY(-3px);
        }
        .user-photo {
            position: relative;
            width: 100%;
            height: calc(110px * var(--card-scale));
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
            font-size: calc(48px * var(--card-scale));
            font-weight: 900;
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
            font-size: calc(9px * var(--card-scale));
            font-weight: 700;
            text-transform: uppercase;
            background: rgba(10, 13, 20, 0.92);
            border: 1px solid rgba(255,255,255,0.18);
            white-space: nowrap;
        }
        .status-pill.online  { color: #4cd982; }
        .status-pill.offline { color: #ff6363; }
        .level-pill {
            padding: 4px 8px;
            border-radius: 7px;
            font-size: calc(9px * var(--card-scale));
            font-weight: 800;
            background: rgba(var(--glow), 0.95);
            color: #0a0d14;
            white-space: nowrap;
        }
        .user-body { padding: calc(14px * var(--card-scale)) calc(12px * var(--card-scale)); }
        .user-name {
            font-size: calc(15px * var(--card-scale));
            font-weight: 800;
            color: var(--text);
            text-align: center;
            margin: 0 0 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role {
            display: block;
            text-align: center;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: calc(10px * var(--card-scale));
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
            font-size: calc(11px * var(--card-scale));
            color: var(--text);
        }
        .contact-row span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .contact-row i { width: 13px; height: 13px; color: rgba(var(--glow), 0.9); flex-shrink: 0; }

        .svg-arrows {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 1;
            overflow: visible;
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

        /* Легенда цветов источников */
        .arrow-legend {
            max-width: 1400px;
            margin: 20px auto 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px 18px;
            justify-content: center;
            position: relative;
            z-index: 1;
        }
        .arrow-legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
        }
        .arrow-legend-dot {
            width: 12px; height: 12px;
            border-radius: 3px;
            flex-shrink: 0;
        }
    </style>

    <div class="users-tree-page" id="usersTreePage">
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

            <div class="zoom-toolbar">
                <button class="zoom-btn" id="zoomOutBtn" type="button" title="Уменьшить">−</button>
                <span class="zoom-level" id="zoomLevelLabel">100%</span>
                <button class="zoom-btn" id="zoomInBtn" type="button" title="Увеличить">+</button>
            </div>
        </div>

        <div class="tree-wrap" id="treeWrap">
            <div class="tree-header">
                <h2 data-i18n="tree_doc_flow_title">Документооборот</h2>
                <p style="color: var(--muted); font-size: 13px;" data-i18n="tree_doc_flow_subtitle">Нажмите на стрелку или карточку чтобы увидеть детали документов</p>
            </div>

            <svg class="svg-arrows" id="svgArrows"></svg>

            {{-- Колонка уровней: пользователи одного уровня помещаются в один ряд-контейнер --}}
            <div class="users-column" id="usersColumn">
                @foreach($groupedByLevel as $level => $levelUsers)
                <div class="level-row" data-level="{{ $level }}">
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
                @endforeach
            </div>
        </div>

        <div class="arrow-legend" id="arrowLegend"></div>
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
                tree_no_title: 'Без названия',
                tree_legend_from: 'Отправитель:'
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
                tree_no_title: 'Бе ном',
                tree_legend_from: 'Фиристанда:'
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
                tree_no_title: 'No title',
                tree_legend_from: 'Sender:'
            }
        };

        function applyTreeTranslations(lang) {
            if (!TREE_TRANSLATIONS[lang]) lang = 'ru';
            const dict = TREE_TRANSLATIONS[lang];

            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.getAttribute('data-i18n');
                if (dict[key] !== undefined) {
                    el.textContent = dict[key];
                }
            });

            if (typeof drawArrows === 'function') {
                setTimeout(drawArrows, 50);
            }
        }

        window.addEventListener('docsign:lang-changed', function(e) {
            const lang = e.detail.lang;
            applyTreeTranslations(lang);
        });

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

        // ------------------------------------------------------------
        // Автоматический масштаб карточек в зависимости от их количества
        // + ручной зум кнопками "+" / "−"
        // ------------------------------------------------------------
        const ZOOM_MIN = 0.5;
        const ZOOM_MAX = 1.5;
        const ZOOM_STEP = 0.1;
        let currentScale = 1;

        function computeAutoScale(count) {
            if (count <= 6) return 1.2;
            if (count <= 12) return 1.0;
            if (count <= 20) return 0.85;
            if (count <= 30) return 0.7;
            return 0.55;
        }

        function applyScale(scale) {
            currentScale = Math.min(ZOOM_MAX, Math.max(ZOOM_MIN, scale));
            const page = document.getElementById('usersTreePage');
            if (page) page.style.setProperty('--card-scale', currentScale);
            const label = document.getElementById('zoomLevelLabel');
            if (label) label.textContent = Math.round(currentScale * 100) + '%';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cardCount = document.querySelectorAll('.user-card').length;
            applyScale(computeAutoScale(cardCount));

            const zoomInBtn = document.getElementById('zoomInBtn');
            const zoomOutBtn = document.getElementById('zoomOutBtn');

            if (zoomInBtn) {
                zoomInBtn.addEventListener('click', function() {
                    applyScale(currentScale + ZOOM_STEP);
                    setTimeout(drawArrows, 60);
                });
            }
            if (zoomOutBtn) {
                zoomOutBtn.addEventListener('click', function() {
                    applyScale(currentScale - ZOOM_STEP);
                    setTimeout(drawArrows, 60);
                });
            }

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

        // Автоматический уникальный цвет для каждого отправителя (источника стрелок).
        // Золотой угол даёт хорошо различимые цвета вне зависимости от количества источников.
        const _userColorCache = new Map();
        let _userColorSeed = 0;
        function colorForUser(id) {
            if (!_userColorCache.has(id)) {
                const hue = Math.round((_userColorSeed * 137.508) % 360);
                _userColorCache.set(id, `hsl(${hue}, 82%, 60%)`);
                _userColorSeed++;
            }
            return _userColorCache.get(id);
        }

        /**
         * Рисует схему "ствол + ветки". Чтобы не перегружать одну сторону,
         * стволы отправителей распределяются то справа, то слева от карточек
         * (через одного) — так связей на каждой стороне вдвое меньше и
         * читать схему проще. Наконечники стрелок увеличены для наглядности.
         */
        function drawArrows() {
            const svg = document.getElementById('svgArrows');
            const treeWrap = document.getElementById('treeWrap');
            const legend = document.getElementById('arrowLegend');
            if (!svg || !treeWrap) return;

            svg.innerHTML = '';
            if (legend) legend.innerHTML = '';

            const wrapRect = treeWrap.getBoundingClientRect();
            svg.setAttribute('width', wrapRect.width);
            svg.setAttribute('height', wrapRect.height);

            const arrowW = 22; // увеличенный наконечник
            const arrowH = 18;
            const trunkGap = Math.max(22, 34 * currentScale);
            const trunkStart = Math.max(30, 46 * currentScale);

            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
            svg.appendChild(defs);

            function ensureMarker(color, safeId) {
                const markerId = `arrowhead-${safeId}`;
                if (defs.querySelector(`#${markerId}`)) return markerId;
                const marker = document.createElementNS('http://www.w3.org/2000/svg', 'marker');
                marker.setAttribute('id', markerId);
                marker.setAttribute('markerWidth', arrowW);
                marker.setAttribute('markerHeight', arrowH);
                marker.setAttribute('refX', arrowW - 1);
                marker.setAttribute('refY', arrowH / 2);
                marker.setAttribute('orient', 'auto');
                marker.setAttribute('markerUnits', 'userSpaceOnUse');
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', `M 0 1 L ${arrowW} ${arrowH / 2} L 0 ${arrowH - 1} Z`);
                path.setAttribute('fill', color);
                marker.appendChild(path);
                defs.appendChild(marker);
                return markerId;
            }

            // Группируем связи по отправителю
            const bySource = new Map();
            Object.keys(connections).forEach(fromId => {
                const from = parseInt(fromId);
                connections[fromId].forEach(toIdRaw => {
                    const to = parseInt(toIdRaw);
                    if (!bySource.has(from)) bySource.set(from, []);
                    bySource.get(from).push({
                        to,
                        count: documentCounts[`${from}-${to}`] || 1
                    });
                });
            });

            // Стабильный порядок отправителей (по позиции карточки в документе сверху вниз, слева направо)
            const orderedIds = Array.from(document.querySelectorAll('.user-card')).map(c => parseInt(c.dataset.userId));
            const sourceIds = orderedIds.filter(id => bySource.has(id));

            const currentLang = localStorage.getItem('docsign_lang') || 'ru';
            const dict = TREE_TRANSLATIONS[currentLang] || TREE_TRANSLATIONS.ru;

            let rightCounter = 0;
            let leftCounter = 0;
            let maxRightEdge = wrapRect.width;
            let minLeftEdge = 0;

            sourceIds.forEach((fromId, sourceIndex) => {
                const fromCard = document.getElementById(`user-${fromId}`);
                if (!fromCard) return;
                const fromRect = fromCard.getBoundingClientRect();

                const color = colorForUser(fromId);
                const safeId = `src${fromId}`;
                const markerId = ensureMarker(color, safeId);

                // Через одного: чётные — справа, нечётные — слева
                const side = sourceIndex % 2 === 0 ? 'right' : 'left';

                let startX, trunkX;
                if (side === 'right') {
                    startX = fromRect.right - wrapRect.left;
                    trunkX = startX + trunkStart + rightCounter * trunkGap;
                    rightCounter++;
                    maxRightEdge = Math.max(maxRightEdge, trunkX);
                } else {
                    startX = fromRect.left - wrapRect.left;
                    trunkX = startX - trunkStart - leftCounter * trunkGap;
                    leftCounter++;
                    minLeftEdge = Math.min(minLeftEdge, trunkX);
                }

                const startY = fromRect.top + fromRect.height / 2 - wrapRect.top;
                const targets = bySource.get(fromId);
                const targetYs = [];

                targets.forEach(({ to, count }) => {
                    const toCard = document.getElementById(`user-${to}`);
                    if (!toCard) return;
                    const toRect = toCard.getBoundingClientRect();
                    const endY = toRect.top + toRect.height / 2 - wrapRect.top;
                    targetYs.push(endY);

                    const enterX = side === 'right'
                        ? (toRect.right - wrapRect.left + 10)
                        : (toRect.left - wrapRect.left - 10);

                    const d = `M ${trunkX} ${endY} L ${enterX} ${endY}`;

                    const branch = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    branch.setAttribute('d', d);
                    branch.setAttribute('stroke', color);
                    branch.setAttribute('stroke-width', '3');
                    branch.setAttribute('fill', 'none');
                    branch.setAttribute('marker-end', `url(#${markerId})`);
                    branch.style.filter = `drop-shadow(0 0 6px ${color})`;
                    branch.style.opacity = '0.95';
                    svg.appendChild(branch);

                    const flow = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    flow.setAttribute('d', d);
                    flow.setAttribute('stroke', '#ffffff');
                    flow.setAttribute('stroke-width', '1.3');
                    flow.setAttribute('stroke-dasharray', '5 12');
                    flow.setAttribute('fill', 'none');
                    flow.setAttribute('opacity', '0.7');
                    flow.setAttribute('class', 'arrow-flow');
                    flow.style.pointerEvents = 'none';
                    svg.appendChild(flow);

                    if (count > 0) {
                        const midX = (trunkX + enterX) / 2;
                        const midY = endY;

                        const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                        g.style.cursor = 'pointer';
                        g.style.pointerEvents = 'all';
                        g.addEventListener('click', () => showDocumentsBetween(fromId, to));

                        const labelText = `${count} ${dict.tree_docs_label}`;
                        const pillWidth = Math.max(60, labelText.length * 7 + 24);
                        const pillHeight = 24;

                        const pill = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                        pill.setAttribute('x', midX - pillWidth / 2);
                        pill.setAttribute('y', midY - pillHeight / 2 - 16);
                        pill.setAttribute('width', pillWidth);
                        pill.setAttribute('height', pillHeight);
                        pill.setAttribute('rx', pillHeight / 2);
                        pill.setAttribute('ry', pillHeight / 2);
                        pill.setAttribute('fill', 'rgba(10, 13, 20, 0.95)');
                        pill.setAttribute('stroke', color);
                        pill.setAttribute('stroke-width', '2');
                        pill.style.filter = `drop-shadow(0 0 8px ${color})`;
                        g.appendChild(pill);

                        const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                        text.setAttribute('x', midX);
                        text.setAttribute('y', midY - 16 + 1);
                        text.setAttribute('fill', '#ffffff');
                        text.setAttribute('font-size', '11');
                        text.setAttribute('font-weight', '800');
                        text.setAttribute('text-anchor', 'middle');
                        text.setAttribute('dominant-baseline', 'central');
                        text.textContent = labelText;
                        g.appendChild(text);

                        svg.appendChild(g);
                    }

                    if (side === 'right') maxRightEdge = Math.max(maxRightEdge, enterX);
                    else minLeftEdge = Math.min(minLeftEdge, enterX);
                });

                // Ствол: вертикальная линия, охватывающая источник и всех получателей
                const allYs = [startY, ...targetYs];
                const minY = Math.min(...allYs);
                const maxY = Math.max(...allYs);

                const trunk = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                trunk.setAttribute('d', `M ${trunkX} ${minY} L ${trunkX} ${maxY}`);
                trunk.setAttribute('stroke', color);
                trunk.setAttribute('stroke-width', '3');
                trunk.setAttribute('fill', 'none');
                trunk.style.filter = `drop-shadow(0 0 6px ${color})`;
                trunk.style.opacity = '0.85';
                svg.insertBefore(trunk, svg.firstChild.nextSibling);

                // Стаб от карточки-источника до ствола
                const stub = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                stub.setAttribute('d', `M ${startX} ${startY} L ${trunkX} ${startY}`);
                stub.setAttribute('stroke', color);
                stub.setAttribute('stroke-width', '3');
                stub.setAttribute('fill', 'none');
                stub.style.filter = `drop-shadow(0 0 6px ${color})`;
                stub.style.opacity = '0.85';
                svg.insertBefore(stub, svg.firstChild.nextSibling);

                // Легенда
                if (legend) {
                    const user = users[fromId];
                    const item = document.createElement('div');
                    item.className = 'arrow-legend-item';
                    item.innerHTML = `<span class="arrow-legend-dot" style="background:${color}; box-shadow:0 0 6px ${color};"></span>
                        <span>${dict.tree_legend_from} <strong style="color:var(--text)">${user?.name || fromId}</strong> (${side === 'right' ? '→' : '←'})</span>`;
                    legend.appendChild(item);
                }
            });

            // Подгоняем ширину/сдвиг SVG, если стрелки вышли за исходные границы wrap
            const totalWidth = maxRightEdge - minLeftEdge + 40;
            svg.setAttribute('width', totalWidth);
            svg.style.left = minLeftEdge - 20 + 'px';
            svg.style.transform = minLeftEdge < 0 ? `translateX(0)` : '';
            // Смещаем координаты всех элементов, если ушли влево от 0
            if (minLeftEdge < 0) {
                svg.querySelectorAll('path, g').forEach(el => {
                    // сдвигаем через viewBox вместо пересчёта каждой точки
                });
                svg.setAttribute('viewBox', `${minLeftEdge - 20} 0 ${totalWidth} ${wrapRect.height}`);
                svg.style.left = '0px';
                svg.style.width = wrapRect.width + 'px';
            } else {
                svg.removeAttribute('viewBox');
                svg.style.left = '0px';
                svg.setAttribute('width', wrapRect.width);
            }
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