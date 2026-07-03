@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

@php
$hasOverdue = $signatures->contains(fn($s) => !$s->signed_at && $s->expires_at && $s->expires_at->isPast());
$allSigned = $signatures->isNotEmpty() && $signatures->every(fn($s) => $s->signed_at);

// Статистика
$totalSignatures = $signatures->total();
$signedCount = $signatures->filter(fn($s) => $s->signed_at)->count();
$pendingCount = $totalSignatures - $signedCount;
$overdueCount = $signatures->filter(fn($s) => !$s->signed_at && $s->expires_at && $s->expires_at->isPast())->count();
@endphp

<style>
    .sig-page {
        min-height: 100vh;
        padding: 32px 24px;
        color: var(--text);
    }

    /* Заголовок страницы */
    .sig-header {
        max-width: 1400px;
        margin: 0 auto 28px;
    }

    .sig-title {
        font-size: 26px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.5px;
        margin: 0 0 6px;
    }

    .sig-title::before {
        content: "";
        width: 4px;
        height: 28px;
        background: linear-gradient(180deg, rgba(var(--glow), 1), rgba(var(--glow), 0.3));
        border-radius: 2px;
        box-shadow: 0 0 10px rgba(var(--glow), 0.6);
    }

    .sig-subtitle {
        font-size: 13px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .status-indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        box-shadow: 0 0 8px currentColor;
    }

    .status-indicator.urgent {
        background: #ff6363;
        color: #ff6363;
        animation: pulse 1.5s infinite;
    }

    .status-indicator.complete {
        background: #4cd982;
        color: #4cd982;
    }

    .status-indicator.active {
        background: var(--muted);
        color: var(--muted);
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    /* Статистика */
    .stats-grid {
        max-width: 1400px;
        margin: 0 auto 24px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    @media (min-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .stat-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 18px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow),0.4), transparent 40%, transparent 60%, rgba(var(--glow),0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -20px rgba(var(--glow), 0.3);
    }

    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        font-size: 20px;
        border: 1px solid;
        transition: all 0.6s ease;
    }

    .stat-icon.total {
        background: rgba(var(--glow), 0.12);
        border-color: rgba(var(--glow), 0.25);
        color: rgba(var(--glow), 1);
        box-shadow: inset 0 0 10px rgba(var(--glow), 0.15);
    }

    .stat-icon.signed {
        background: rgba(76, 217, 130, 0.12);
        border-color: rgba(76, 217, 130, 0.25);
        color: #4cd982;
        box-shadow: inset 0 0 10px rgba(76, 217, 130, 0.15);
    }

    .stat-icon.pending {
        background: rgba(255, 181, 71, 0.12);
        border-color: rgba(255, 181, 71, 0.25);
        color: #ffb547;
        box-shadow: inset 0 0 10px rgba(255, 181, 71, 0.15);
    }

    .stat-icon.overdue {
        background: rgba(255, 99, 99, 0.12);
        border-color: rgba(255, 99, 99, 0.25);
        color: #ff6363;
        box-shadow: inset 0 0 10px rgba(255, 99, 99, 0.15);
    }

    .stat-value {
        font-size: 26px;
        font-weight: 700;
        margin-top: 12px;
        letter-spacing: -0.5px;
    }

    .stat-value.total { color: var(--text); }
    .stat-value.signed { color: #4cd982; }
    .stat-value.pending { color: #ffb547; }
    .stat-value.overdue { color: #ff6363; }

    .stat-label {
        font-size: 10px;
        font-family: 'JetBrains Mono', monospace;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
        margin-top: 4px;
    }

    /* Фильтры */
    .filters-bar {
        max-width: 1400px;
        margin: 0 auto 24px;
        display: flex;
        gap: 6px;
        background: rgba(255,255,255,0.03);
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--line);
        flex-wrap: wrap;
    }

    .filter-btn {
        appearance: none;
        border: 0;
        background: transparent;
        color: var(--muted);
        font: 600 12px 'Inter', sans-serif;
        padding: 8px 16px;
        border-radius: 9px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-btn:hover {
        color: var(--text);
        background: rgba(255,255,255,0.04);
    }

    .filter-btn.active {
        color: #fff;
        background: linear-gradient(180deg, rgba(var(--glow), 0.25), rgba(var(--glow), 0.08));
        box-shadow: inset 0 0 0 1px rgba(var(--glow), 0.35), 0 0 18px rgba(var(--glow), 0.25);
    }

    /* Сетка карточек */
    .signatures-grid {
        max-width: 1400px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    @media (min-width: 768px) {
        .signatures-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1200px) {
        .signatures-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Карточка подписи */
    .sig-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .sig-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow),0.4), transparent 40%, transparent 60%, rgba(var(--glow),0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
    }

    .sig-card:hover::before {
        opacity: 1;
    }

    .sig-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -20px rgba(var(--glow), 0.3);
    }

    .sig-card.overdue {
        border-color: rgba(255, 99, 99, 0.4);
    }

    .sig-card.overdue::before {
        opacity: 1;
        background: linear-gradient(135deg, rgba(255, 99, 99, 0.5), transparent 40%, transparent 60%, rgba(255, 99, 99, 0.3));
    }

    .sig-card.signed {
        border-color: rgba(76, 217, 130, 0.3);
    }

    .sig-card.signed::before {
        opacity: 1;
        background: linear-gradient(135deg, rgba(76, 217, 130, 0.4), transparent 40%, transparent 60%, rgba(76, 217, 130, 0.2));
    }

    /* Header карточки */
    .sig-card-header {
        padding: 16px 18px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--line);
    }

    .sig-card-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sig-card-label.overdue {
        color: #ff6363;
    }

    .sig-card-id {
        font-size: 10px;
        font-family: 'JetBrains Mono', monospace;
        color: var(--muted);
        background: rgba(255,255,255,0.04);
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid var(--line);
    }

    /* Title */
    .sig-card-title {
        padding: 14px 18px 10px;
    }

    .sig-card-title h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        letter-spacing: -0.3px;
        display: flex;
        align-items: center;
        gap: 8px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .doc-icon {
        width: 28px;
        height: 32px;
        border-radius: 6px;
        display: grid;
        place-items: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .doc-icon.pdf {
        background: rgba(255, 99, 99, 0.15);
        color: #ff6363;
        border: 1px solid rgba(255, 99, 99, 0.3);
    }

    .doc-icon.word {
        background: rgba(79, 140, 255, 0.15);
        color: rgba(79, 140, 255, 1);
        border: 1px solid rgba(79, 140, 255, 0.3);
    }

    .doc-icon.excel {
        background: rgba(76, 217, 130, 0.15);
        color: #4cd982;
        border: 1px solid rgba(76, 217, 130, 0.3);
    }

    /* Signature Area */
    .sig-area {
        min-height: 130px;
        background: rgba(255,255,255,0.02);
        border-top: 1px solid var(--line);
        border-bottom: 1px solid var(--line);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        transition: all 0.2s ease;
    }

    .sig-area.clickable {
        cursor: pointer;
        text-decoration: none;
    }

    .sig-area.clickable:hover {
        background: rgba(var(--glow), 0.05);
    }

    .sig-area.clickable:hover .sig-placeholder-icon {
        color: rgba(var(--glow), 1);
        transform: scale(1.15);
    }

    .sig-area.clickable:hover .sig-placeholder-text {
        color: rgba(var(--glow), 1);
        opacity: 1;
    }

    .sig-placeholder-icon {
        font-size: 32px;
        color: rgba(255,255,255,0.15);
        transition: all 0.3s ease;
    }

    .sig-placeholder-text {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--muted);
        opacity: 0.5;
        transition: all 0.3s ease;
        margin-top: 8px;
    }

    .sig-qr-wrapper {
        background: #ffffff;
        padding: 10px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    }

    .sig-qr-wrapper img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        display: block;
    }

    .format-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        color: white;
        text-transform: uppercase;
        font-family: 'JetBrains Mono', monospace;
        letter-spacing: 0.5px;
        z-index: 10;
    }

    .format-badge.pdf { background: #ff6363; }
    .format-badge.word { background: rgba(79, 140, 255, 1); }
    .format-badge.excel { background: #4cd982; }

    .signed-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: rgba(76, 217, 130, 0.15);
        color: #4cd982;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: 1px solid rgba(76, 217, 130, 0.3);
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .signed-badge::before {
        content: "✓";
        font-weight: 900;
    }

    /* Executor & Deadline */
    .sig-card-meta {
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex: 1;
    }

    .executor-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .executor-avatar {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.5), rgba(var(--glow), 0.15));
        border: 1px solid rgba(var(--glow), 0.3);
        display: grid;
        place-items: center;
        font-weight: 700;
        font-size: 12px;
        color: #fff;
        flex-shrink: 0;
    }

    .executor-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .executor-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        letter-spacing: -0.2px;
    }

    .deadline-info {
        text-align: right;
    }

    .deadline-label {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        margin-bottom: 3px;
    }

    .deadline-value {
        font-size: 13px;
        font-weight: 700;
        color: var(--text);
        font-family: 'JetBrains Mono', monospace;
    }

    .deadline-value.overdue {
        color: #ff6363;
    }

    /* Progress Bar */
    .sig-progress {
        padding: 0 18px 14px;
    }

    .progress-bar {
        height: 4px;
        background: rgba(255,255,255,0.06);
        border-radius: 2px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4cd982, #059669);
        border-radius: 2px;
        box-shadow: 0 0 8px rgba(76, 217, 130, 0.5);
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-top: 6px;
        font-size: 10px;
        font-weight: 600;
    }

    .progress-info .complete {
        color: #4cd982;
    }

    .progress-info .date {
        color: var(--muted);
        font-family: 'JetBrains Mono', monospace;
    }

    /* Actions */
    .sig-card-actions {
        padding: 12px 18px;
        border-top: 1px solid var(--line);
        background: rgba(255,255,255,0.02);
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .action-link {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .action-link.open {
        color: rgba(var(--glow), 1);
    }

    .action-link.open:hover {
        color: rgba(var(--glow), 0.8);
        text-shadow: 0 0 8px rgba(var(--glow), 0.5);
    }

    .action-link.delete {
        color: #ff6363;
    }

    .action-link.delete:hover {
        color: #ff4444;
        text-shadow: 0 0 8px rgba(255, 99, 99, 0.5);
    }

    .action-link button {
        background: none;
        border: none;
        color: inherit;
        font: inherit;
        cursor: pointer;
        padding: 0;
    }

    /* Empty State */
    .empty-state {
        grid-column: 1 / -1;
        padding: 60px 20px;
        text-align: center;
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        border: 2px dashed var(--line);
        border-radius: var(--radius);
    }

    .empty-state-icon {
        font-size: 56px;
        color: rgba(255,255,255,0.15);
        margin-bottom: 16px;
    }

    .empty-state-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--muted);
        margin: 0;
    }

    /* ============================================ */
    /* === ПРОСТАЯ ПАГИНАЦИЯ (ТОЛЬКО PREV/NEXT) === */
    /* ============================================ */
    .pagination-wrapper {
        max-width: 1400px;
        margin: 40px auto 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        padding: 0 16px;
    }

    .pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 140px;
        height: 44px;
        padding: 0 24px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        color: var(--muted);
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--line);
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
        letter-spacing: 0.5px;
    }

    .pagination-btn:hover:not(.disabled) {
        color: var(--text);
        border-color: rgba(var(--glow), 0.5);
        background: rgba(var(--glow), 0.1);
        transform: translateY(-1px);
        box-shadow: 0 4px 16px -4px rgba(var(--glow), 0.3);
    }

    .pagination-btn.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sig-page { padding: 20px 16px; }
        .sig-title { font-size: 22px; }

        .pagination-btn {
            min-width: 120px;
            height: 40px;
            padding: 0 18px;
            font-size: 12px;
        }
    }
</style>

<div class="sig-page">
    {{-- HEADER --}}
    <div class="sig-header">
        <h1 class="sig-title" data-i18n="pageTitle">Реестр подписей</h1>
        <p class="sig-subtitle">
            <span class="status-indicator {{ $hasOverdue ? 'urgent' : ($allSigned ? 'complete' : 'active') }}"></span>
            <span data-i18n="{{ $hasOverdue ? 'sysUrgent' : ($allSigned ? 'sysComplete' : 'sysActive') }}">
                {{ $hasOverdue ? 'Требуется срочное внимание' : ($allSigned ? 'Все документы оформлены' : 'Система мониторинга активна') }}
            </span>
        </p>
    </div>

    {{-- STATISTICS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-value total">{{ $totalSignatures }}</div>
            <div class="stat-label" data-i18n="statTotal">Всего</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon signed">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-value signed">{{ $signedCount }}</div>
            <div class="stat-label" data-i18n="statSigned">Подписано</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">
                <i class="bi bi-clock-fill"></i>
            </div>
            <div class="stat-value pending">{{ $pendingCount }}</div>
            <div class="stat-label" data-i18n="statPending">Ожидает</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon overdue">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="stat-value overdue">{{ $overdueCount }}</div>
            <div class="stat-label" data-i18n="statOverdue">Просрочено</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="filters-bar">
        <button class="filter-btn active" data-filter="all" data-i18n="filterAll">Все</button>
        <button class="filter-btn" data-filter="signed" data-i18n="filterSigned">Подписанные</button>
        <button class="filter-btn" data-filter="pending" data-i18n="filterPending">Ожидающие</button>
        <button class="filter-btn" data-filter="overdue" data-i18n="filterOverdue">Просроченные</button>
    </div>

    {{-- SIGNATURES GRID --}}
    <div class="signatures-grid" id="signaturesGrid">
        @forelse($signatures as $index => $s)
        @php
        $isPast = !$s->signed_at && $s->expires_at && $s->expires_at->isPast();
        $isSigned = (bool) $s->signed_at;
        $extension = $s->document && $s->document->file_path ? strtolower(pathinfo($s->document->file_path, PATHINFO_EXTENSION)) : null;
        $isWord = in_array($extension, ['doc', 'docx']);
        $isExcel = in_array($extension, ['xls', 'xlsx']);
        $isPdf = $extension === 'pdf';
        $docId = $s->document->id ?? null;
        $docDeadline = $s->document->deadline ?? null;
        $isPastDate = !$s->signed_at && $docDeadline && \Carbon\Carbon::parse($docDeadline)->isPast();
        @endphp

        <div class="sig-card {{ $isPast ? 'overdue' : ($isSigned ? 'signed' : '') }}"
             data-status="{{ $isSigned ? 'signed' : ($isPast ? 'overdue' : 'pending') }}">

            {{-- Header --}}
            <div class="sig-card-header">
                    <span class="sig-card-label {{ $isPast ? 'overdue' : '' }}">
                        @if($isPast)
                            <i class="bi bi-exclamation-circle-fill"></i>
                            <span data-i18n="overdueLabel">СРОК ИСТЕК</span>
                        @else
                            <span data-i18n="docLabel">Документ</span> #{{ ($signatures->currentPage() - 1) * $signatures->perPage() + $index + 1 }}
                        @endif
                    </span>
                <span class="sig-card-id">ID-{{ $s->id }}</span>
            </div>

            {{-- Title --}}
            <div class="sig-card-title">
                <h3>
                    @if($isWord)
                    <i class="bi bi-file-earmark-word-fill doc-icon word"></i>
                    @elseif($isPdf)
                    <i class="bi bi-file-earmark-pdf-fill doc-icon pdf"></i>
                    @elseif($isExcel)
                    <i class="bi bi-file-earmark-excel-fill doc-icon excel"></i>
                    @else
                    <i class="bi bi-file-earmark doc-icon"></i>
                    @endif
                    <span title="{{ $s->document->title ?? 'Без названия' }}">
                            {{ $s->document->title ?? 'Без названия' }}
                        </span>
                </h3>
            </div>

            {{-- Signature Area --}}
            @if($s->signature)
            <div class="sig-area">
                <div class="signed-badge" data-i18n="badgeSigned">ПОДПИСАНО</div>

                @if($extension)
                <div class="format-badge {{ $isWord ? 'word' : ($isPdf ? 'pdf' : 'excel') }}">
                    {{ $extension }}
                </div>
                @endif

                <div class="sig-qr-wrapper">
                    <img src="{{ asset('storage/' . $s->signature) }}" alt="QR Code Signature">
                </div>
            </div>
            @else
            <a href="{{ route('signatures.create', ['document_id' => $docId]) }}" class="sig-area clickable">
                @if($extension)
                <div class="format-badge {{ $isWord ? 'word' : ($isPdf ? 'pdf' : 'excel') }}">
                    {{ $extension }}
                </div>
                @endif
                <div style="text-align: center;">
                    <i class="bi bi-qr-code sig-placeholder-icon"></i>
                    <div class="sig-placeholder-text" data-i18n="waitingSig">Ожидает подписи</div>
                </div>
            </a>
            @endif

            {{-- Executor & Deadline --}}
            <div class="sig-card-meta">
                <div class="executor-info">
                    <div class="executor-avatar">
                        {{ mb_strtoupper(mb_substr($s->user->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <div class="executor-label" data-i18n="labelExecutor">Исполнитель</div>
                        <div class="executor-name">{{ Str::limit($s->user->name ?? 'Неизвестен', 14) }}</div>
                    </div>
                </div>

                <div class="deadline-info">
                    <div class="deadline-label" data-i18n="{{ $s->signed_at ? 'labelDone' : 'labelDeadline' }}">
                        {{ $s->signed_at ? 'Завершено' : 'Дедлайн' }}
                    </div>
                    <div class="deadline-value {{ $isPastDate ? 'overdue' : '' }}">
                        {{ $docDeadline ? \Carbon\Carbon::parse($docDeadline)->format('d.m.Y') : '--' }}
                    </div>
                </div>
            </div>

            {{-- Progress Bar --}}
            @if($isSigned)
            <div class="sig-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 100%"></div>
                </div>
                <div class="progress-info">
                    <span class="complete" data-i18n="progressComplete">Подписано</span>
                    <span class="date">
                                {{ $s->signed_at ? \Carbon\Carbon::parse($s->signed_at)->format('d.m.Y H:i') : '--' }}
                            </span>
                </div>
            </div>
            @endif
<style>
    /* === ДЕЙСТВИЯ КАРТОЧКИ ПОДПИСИ === */
.sig-card-actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px solid rgba(255, 255, 255, 0.06);
    align-items: center;
    justify-content: flex-end;
}

/* Базовая кнопка-ссылка */
.action-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.25s ease;
    border: 1px solid;
    background: transparent;
    font-family: 'Inter', sans-serif;
    white-space: nowrap;
}

.action-link i {
    font-size: 13px;
    transition: transform 0.25s ease;
}

/* Кнопка "Открыть" */
.action-link.open {
    background: rgba(79, 140, 255, 0.12);
    border-color: rgba(79, 140, 255, 0.35);
    color: #4f8cff;
}

.action-link.open:hover {
    background: rgba(79, 140, 255, 0.22);
    border-color: rgba(79, 140, 255, 0.7);
    box-shadow: 0 0 16px rgba(79, 140, 255, 0.3);
    color: #fff;
    transform: translateY(-1px);
}

.action-link.open:hover i {
    transform: scale(1.15);
}

/* Кнопка "Удалить" */
.action-link.delete {
    background: rgba(255, 107, 107, 0.08);
    border-color: rgba(255, 107, 107, 0.25);
    color: #ff6b6b;
}

.action-link.delete:hover {
    background: rgba(255, 107, 107, 0.18);
    border-color: rgba(255, 107, 107, 0.6);
    box-shadow: 0 0 16px rgba(255, 107, 107, 0.3);
    color: #fff;
    transform: translateY(-1px);
}

.action-link.delete:hover i {
    transform: scale(1.15) rotate(-8deg);
}

/* Активное состояние (при клике) */
.action-link:active {
    transform: translateY(0) scale(0.97);
}

/* === АДАПТИВНОСТЬ === */
@media (max-width: 640px) {
    .sig-card-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .action-link {
        justify-content: center;
        padding: 10px 14px;
    }
}
</style>
            {{-- Actions --}}
            <div class="sig-card-actions">
                {{-- Кнопка Открыть --}}
                <a href="{{ route('signatures.show', $s->id) }}" class="action-link open">
                    <i class="bi bi-eye-fill"></i>
                    <span data-i18n="linkOpen">Открыть</span>
                </a>

                {{-- Кнопка Удалить (только для владельца или админа) --}}
                @if(auth()->user()->isAdmin() || auth()->id() === $s->user_id)
                <form action="{{ route('signatures.destroy', $s->id) }}" method="POST" style="display: inline;"
                      onsubmit="return confirmDelete(event, this)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-link delete">
                        <i class="bi bi-trash3-fill"></i>

                    </button>
                </form>
                @endif
            </div>

            {{-- Скрипт для красивой модалки подтверждения --}}
            <script>
                function confirmDelete(event, form) {
                    event.preventDefault();

                    const t = {
                        ru: { title: 'Удалить подпись?', desc: 'Это действие нельзя отменить.', cancel: 'Отмена', confirm: 'Удалить' },
                        tj: { title: 'Имзоро нест кардан?', desc: 'Ин амалро бекор кардан ғайриимкон аст.', cancel: 'Лағв', confirm: 'Нест кардан' },
                        en: { title: 'Delete signature?', desc: 'This action cannot be undone.', cancel: 'Cancel', confirm: 'Delete' }
                    };

                    const lang = localStorage.getItem('docsign_lang') || 'ru';
                    const dict = t[lang] || t.ru;

                    // Создаём модалку
                    const overlay = document.createElement('div');
                    overlay.className = 'modal-overlay';
                    overlay.style.cssText = `
                        position: fixed; inset: 0; z-index: 10000;
                        background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);
                        display: flex; align-items: center; justify-content: center;
                        padding: 16px; animation: fadeIn 0.2s ease;
                    `;

                    overlay.innerHTML = `
                        <div style="
                            background: linear-gradient(180deg, rgba(22,26,38,0.98), rgba(16,19,28,0.98));
                            border: 1px solid rgba(255,255,255,0.15);
                            border-radius: 14px; padding: 24px;
                            max-width: 380px; width: 100%;
                            box-shadow: 0 20px 50px rgba(0,0,0,0.6), 0 0 30px rgba(255,99,99,0.1);
                            animation: slideUp 0.3s ease;
                        ">
                            <h3 style="
                                font-size: 14px; font-weight: 700; color: #fff;
                                margin: 0 0 8px 0; display: flex; align-items: center; gap: 8px;
                            ">
                                <i class="bi bi-exclamation-triangle-fill" style="color: #ff6b6b; font-size: 16px;"></i>
                                ${dict.title}
                            </h3>
                            <p style="font-size: 12px; color: #a8b2c1; line-height: 1.5; margin: 0 0 20px 0;">
                                ${dict.desc}
                            </p>
                            <div style="display: flex; justify-content: flex-end; gap: 8px;">
                                <button id="modal-cancel" style="
                                    padding: 8px 16px; border-radius: 8px;
                                    background: rgba(255,255,255,0.04);
                                    border: 1px solid rgba(255,255,255,0.15);
                                    color: #8892a6; font-size: 11px; font-weight: 700;
                                    letter-spacing: 0.5px; text-transform: uppercase;
                                    cursor: pointer; transition: all 0.2s;
                                ">${dict.cancel}</button>
                                <button id="modal-confirm" style="
                                    padding: 8px 16px; border-radius: 8px;
                                    background: rgba(255,99,99,0.15);
                                    border: 1px solid rgba(255,99,99,0.5);
                                    color: #ff6b6b; font-size: 11px; font-weight: 700;
                                    letter-spacing: 0.5px; text-transform: uppercase;
                                    cursor: pointer; transition: all 0.2s;
                                ">${dict.confirm}</button>
                            </div>
                        </div>
                    `;

                    document.body.appendChild(overlay);

                    // Обработчики
                    const close = () => overlay.remove();
                    overlay.querySelector('#modal-cancel').onclick = close;
                    overlay.querySelector('#modal-confirm').onclick = () => form.submit();
                    overlay.addEventListener('click', (e) => {
                        if (e.target === overlay) close();
                    });

                    return false;
                }
            </script>

            <style>
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                @keyframes slideUp {
                    from { opacity: 0; transform: translateY(20px) scale(0.95); }
                    to { opacity: 1; transform: translateY(0) scale(1); }
                }
            </style>
        </div>
        @empty
        <div class="empty-state">
            <i class="bi bi-inbox empty-state-icon"></i>
            <p class="empty-state-text" data-i18n="emptyRegistry">В реестре пока нет записей</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination - только Previous и Next --}}
    @if($signatures->hasPages())
    <div class="pagination-wrapper">
        @if($signatures->onFirstPage())
        <span class="pagination-btn disabled">« Previous</span>
        @else
        <a href="{{ $signatures->previousPageUrl() }}" class="pagination-btn">« Previous</a>
        @endif

        @if($signatures->hasMorePages())
        <a href="{{ $signatures->nextPageUrl() }}" class="pagination-btn">Next »</a>
        @else
        <span class="pagination-btn disabled">Next »</span>
        @endif
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ РЕЕСТРА ПОДПИСЕЙ
        // ============================================================
        const SIGN_REGISTRY_TRANSLATIONS = {
            ru: {
                pageTitle: 'Реестр подписей',
                sysUrgent: 'Требуется срочное внимание',
                sysComplete: 'Все документы оформлены',
                sysActive: 'Система мониторинга активна',
                statTotal: 'Всего',
                statSigned: 'Подписано',
                statPending: 'Ожидает',
                statOverdue: 'Просрочено',
                filterAll: 'Все',
                filterSigned: 'Подписанные',
                filterPending: 'Ожидающие',
                filterOverdue: 'Просроченные',
                overdueLabel: 'СРОК ИСТЕК',
                docLabel: 'Документ',
                waitingSig: 'Ожидает подписи',
                badgeSigned: 'ПОДПИСАНО',
                labelExecutor: 'Исполнитель',
                labelDone: 'Завершено',
                labelDeadline: 'Дедлайн',
                progressComplete: 'Подписано',
                linkOpen: 'Открыть',
                linkDelete: 'Удалить',
                emptyRegistry: 'В реестре пока нет записей',
                confirmDelete: 'Удалить запись?'
            },
            tj: {
                pageTitle: 'Феҳристи имзоҳо',
                sysUrgent: 'Таваҷҷӯҳи фаврӣ лозим аст',
                sysComplete: 'Ҳамаи ҳуҷҷатҳо ба расмият дароварда шудаанд',
                sysActive: 'Системаи мониторинг фаъол аст',
                statTotal: 'Ҳамагӣ',
                statSigned: 'Имзо шудааст',
                statPending: 'Интизорӣ',
                statOverdue: 'Муҳлат гузашт',
                filterAll: 'Ҳама',
                filterSigned: 'Имзошуда',
                filterPending: 'Интизорӣ',
                filterOverdue: 'Муҳлаташ гузашта',
                overdueLabel: 'МУҲЛАТ ГУЗАШТ',
                docLabel: 'Ҳуҷҷат',
                waitingSig: 'Мунтазири имзо',
                badgeSigned: 'ИМЗО ШУДААСТ',
                labelExecutor: 'Иҷрокунанда',
                labelDone: 'Анҷом ёфт',
                labelDeadline: 'Муҳлат',
                progressComplete: 'Имзо шудааст',
                linkOpen: 'Кушодан',
                linkDelete: 'Нест кардан',
                emptyRegistry: 'Дар феҳрист ҳоло ягон сабт нест',
                confirmDelete: 'Сабтро нест мекунед?'
            },
            en: {
                pageTitle: 'Signature Registry',
                sysUrgent: 'Urgent attention required',
                sysComplete: 'All documents processed',
                sysActive: 'Monitoring system active',
                statTotal: 'Total',
                statSigned: 'Signed',
                statPending: 'Pending',
                statOverdue: 'Overdue',
                filterAll: 'All',
                filterSigned: 'Signed',
                filterPending: 'Pending',
                filterOverdue: 'Overdue',
                overdueLabel: 'OVERDUE',
                docLabel: 'Document',
                waitingSig: 'Awaiting signature',
                badgeSigned: 'SIGNED',
                labelExecutor: 'Executor',
                labelDone: 'Completed',
                labelDeadline: 'Deadline',
                progressComplete: 'Signed',
                linkOpen: 'Open',
                linkDelete: 'Delete',
                emptyRegistry: 'No entries in the registry yet',
                confirmDelete: 'Delete this record?'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applySignRegistryTranslations(lang) {
            const dict = SIGN_REGISTRY_TRANSLATIONS[lang] || SIGN_REGISTRY_TRANSLATIONS.ru;

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

            document.querySelectorAll('[data-confirm-i18n]').forEach(el => {
                const key = el.getAttribute('data-confirm-i18n');
                const message = dict[key] || 'Are you sure?';

                const newEl = el.cloneNode(true);
                el.parentNode.replaceChild(newEl, el);

                newEl.addEventListener('click', function (e) {
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        }

        // ============================================================
        // ФИЛЬТРЫ
        // ============================================================
        const filterBtns = document.querySelectorAll('.filter-btn');
        const cards = document.querySelectorAll('.sig-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filter = btn.getAttribute('data-filter');

                cards.forEach(card => {
                    const status = card.getAttribute('data-status');

                    if (filter === 'all' || status === filter) {
                        card.style.display = '';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applySignRegistryTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applySignRegistryTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applySignRegistryTranslations(e.newValue);
            }
        });
    });
</script>
@endsection