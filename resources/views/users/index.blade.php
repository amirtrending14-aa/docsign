@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    .users-tree-page {
        min-height: 100vh;
        padding: 40px 24px 60px;
        color: var(--text);
        font-family: 'Inter', sans-serif;
        position: relative;
    }

    /* Фоновые blob-ы */
    .tree-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        filter: blur(100px);
        opacity: 0.35;
    }

    .tree-blob-1 {
        top: -100px;
        left: -100px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(var(--glow), 0.3) 0%, transparent 70%);
        animation: blobFloat 20s ease-in-out infinite;
    }

    .tree-blob-2 {
        bottom: -100px;
        right: -100px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.25) 0%, transparent 70%);
        animation: blobFloat 25s ease-in-out infinite reverse;
    }

    .tree-blob-3 {
        top: 50%;
        left: 50%;
        width: 400px;
        height: 400px;
        transform: translate(-50%, -50%);
        background: radial-gradient(circle, rgba(236, 72, 153, 0.2) 0%, transparent 70%);
        animation: blobFloat3 30s ease-in-out infinite;
    }

    @keyframes blobFloat {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(30px, -30px); }
    }

    @keyframes blobFloat3 {
        0%, 100% { transform: translate(-50%, -50%); }
        50% { transform: translate(calc(-50% + 30px), calc(-50% - 30px)); }
    }

    /* === TOP BAR === */
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

    .users-topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .users-topbar-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.4));
        display: grid;
        place-items: center;
        box-shadow: 0 0 24px rgba(var(--glow), 0.5), inset 0 0 12px rgba(255,255,255,0.2);
    }

    .users-topbar-icon svg {
        width: 26px;
        height: 26px;
        color: #0a0d14;
    }

    .users-topbar-title {
        font-size: 24px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.3px;
        margin: 0;
        line-height: 1.2;
    }

    .users-topbar-subtitle {
        font-size: 13px;
        color: var(--muted);
        font-weight: 600;
        margin-top: 3px;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        background: linear-gradient(180deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.65));
        color: #0a0d14;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.25s ease;
        box-shadow: 0 8px 24px rgba(var(--glow), 0.35), inset 0 1px 0 rgba(255,255,255,0.3);
        border: 1px solid transparent;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(var(--glow), 0.5);
        filter: brightness(1.08);
    }

    .btn-add svg {
        width: 16px;
        height: 16px;
    }

    /* === STATS ROW === */
    .stats-row {
        max-width: 1400px;
        margin: 0 auto 32px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 7px;
        position: relative;
        z-index: 1;
    }

    @media (min-width: 768px) {
        .stats-row {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .stat-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 22px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 18px;
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
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -20px rgba(var(--glow), 0.3);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        font-size: 24px;
        border: 1px solid;
        flex-shrink: 0;
    }

    .stat-icon.users {
        background: rgba(var(--glow), 0.18);
        border-color: rgba(var(--glow), 0.35);
        color: rgba(var(--glow), 1);
        box-shadow: inset 0 0 12px rgba(var(--glow), 0.25), 0 0 16px rgba(var(--glow), 0.2);
    }

    .stat-icon.leaders {
        background: rgba(168, 85, 247, 0.18);
        border-color: rgba(168, 85, 247, 0.35);
        color: #a855f7;
        box-shadow: inset 0 0 12px rgba(168, 85, 247, 0.25), 0 0 16px rgba(168, 85, 247, 0.2);
    }

    .stat-icon.levels {
        background: rgba(76, 217, 130, 0.18);
        border-color: rgba(76, 217, 130, 0.35);
        color: #4cd982;
        box-shadow: inset 0 0 12px rgba(76, 217, 130, 0.25), 0 0 16px rgba(76, 217, 130, 0.2);
    }

    .stat-icon.online {
        background: rgba(255, 181, 71, 0.18);
        border-color: rgba(255, 181, 71, 0.35);
        color: #ffb547;
        box-shadow: inset 0 0 12px rgba(255, 181, 71, 0.25), 0 0 16px rgba(255, 181, 71, 0.2);
    }

    .stat-icon svg, .stat-icon i {
        width: 24px;
        height: 24px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.5px;
        line-height: 1;
    }

    .stat-label {
        font-size: 12px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        margin-top: 6px;
    }

    /* === TREE WRAP === */
    .tree-wrap {
        max-width: 1400px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
        background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 40px 32px;
    }

    .tree-wrap::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow),0.5), transparent 40%, transparent 60%, rgba(var(--glow),0.25));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.7;
        pointer-events: none;
    }

    .tree-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .tree-header h2 {
        font-size: 26px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.5px;
        margin: 0 0 8px;
    }

    .tree-header p {
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
        margin: 0;
    }

    /* === LEVEL SECTION === */
    .level-section {
        margin-bottom: 40px;
    }

    .level-connector {
        width: 2px;
        height: 36px;
        margin: 0 auto 24px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.1));
        position: relative;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.4);
    }

    .level-connector::before,
    .level-connector::after {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #000000;
        border: 2px solid #ffffff;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.6);
    }

    .level-connector::before { top: -5px; }
    .level-connector::after { bottom: -5px; }

    .level-header {
        text-align: center;
        margin-bottom: 22px;
    }

    .level-bar {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 9px 20px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        background-color: black !important;
        color: white !important;
        box-shadow: 0 8px 24px rgba(255, 255, 255, 0.2);
    }

    .level-bar .count {
        background: rgba(255, 255, 255, 0.25);
        padding: 3px 10px;
        border-radius: 7px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 11px;
        font-weight: 700;
        color: white;
    }

    .lvl-1, .lvl-2, .lvl-3, .lvl-4, .lvl-5, .lvl-default {
        background: black !important;
        color: white !important;
        border: 1px solid #333;
    }

    /* === USERS GRID === */
    .users-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }

    /* === USER CARD (COMPACT) === */
    .user-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        width: 210px;
        flex-shrink: 0;
    }

    .user-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(var(--glow), 0.9), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .user-card:hover::before {
        opacity: 1;
    }

    .user-card:hover {
        border-color: rgba(var(--glow), 0.5);
        box-shadow: 0 16px 36px -12px rgba(var(--glow), 0.45), 0 0 0 1px rgba(var(--glow), 0.15);
    }

    /* User photo */
    .user-photo {
        position: relative;
        width: 100%;
        height: 105px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(var(--glow), 0.35), rgba(168, 85, 247, 0.25));
    }

    .user-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .user-card:hover .user-photo img {
        transform: scale(1.08);
    }

    .user-photo-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: 900;
        font-style: italic;
        color: rgba(255,255,255,0.85);
        background: linear-gradient(135deg, rgba(var(--glow), 0.6), rgba(168, 85, 247, 0.4));
        text-shadow: 0 4px 24px rgba(0,0,0,0.5);
    }

    .user-photo-gradient {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 30%, rgba(10, 13, 20, 0.95) 100%);
        pointer-events: none;
    }

    .photo-top {
        position: absolute;
        top: 8px;
        left: 8px;
        right: 8px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        z-index: 2;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        background: rgba(10, 13, 20, 0.92);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.18);
        box-shadow: 0 4px 14px rgba(0,0,0,0.35);
    }

    .status-pill.online {
        color: #4cd982;
        border-color: rgba(76, 217, 130, 0.45);
    }

    .status-pill.offline {
        color: #ff6363;
        border-color: rgba(255, 99, 99, 0.35);
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-dot.active {
        animation: pulse 2s infinite;
        box-shadow: 0 0 10px currentColor;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    .level-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
        background: rgba(var(--glow), 0.95);
        color: #0a0d14;
        box-shadow: 0 0 14px rgba(var(--glow), 0.7);
    }

    .level-pill svg {
        width: 10px;
        height: 10px;
    }

    /* User body */
    .user-body {
        padding: 12px 12px 14px;
    }

    .user-name {
        font-size: 14px;
        font-weight: 800;
        color: var(--text);
        text-align: center;
        margin: 0 0 8px;
        letter-spacing: -0.2px;
        line-height: 1.3;
    }

    .user-name a {
        color: inherit;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .user-name a:hover {
        color: rgba(var(--glow), 1);
        text-shadow: 0 0 14px rgba(var(--glow), 0.6);
    }

    .user-role {
        display: block;
        text-align: center;
        padding: 5px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: rgba(var(--glow), 1);
        background: rgba(var(--glow), 0.15);
        border: 1px solid rgba(var(--glow), 0.3);
        margin-bottom: 8px;
    }

    .admin-tag {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        background: rgba(255, 181, 71, 0.18);
        color: #ffb547;
        border: 1px solid rgba(255, 181, 71, 0.35);
    }

    .admin-tag-wrapper {
        text-align: center;
        margin-bottom: 8px;
    }

    .contact-row {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px 6px;
        font-size: 11px;
        color: var(--text);
        border-radius: 6px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .contact-row:hover {
        background: rgba(255,255,255,0.05);
    }

    .contact-row i {
        width: 13px;
        height: 13px;
        color: rgba(var(--glow), 0.9);
        flex-shrink: 0;
        font-size: 11px;
    }

    .contact-row span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 500;
    }

    .creator-box {
        padding: 7px 10px;
        background: rgba(255,255,255,0.04);
        border-radius: 7px;
        margin: 8px 0;
        border-left: 2px solid rgba(var(--glow), 0.7);
    }

    .creator-box-label {
        font-size: 8px;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .creator-box-name {
        font-size: 11px;
        font-weight: 700;
        color: var(--text);
    }

    /* Action row */
    .action-row {
        display: flex;
        gap: 5px;
        padding-top: 10px;
        margin-top: 10px;
        border-top: 1px solid var(--line);
    }

    .act-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        padding: 7px 4px;
        border-radius: 7px;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: 1px solid var(--line);
        background: rgba(255,255,255,0.03);
        color: var(--muted);
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .act-btn i {
        width: 11px;
        height: 11px;
        font-size: 11px;
    }

    .act-btn.view:hover {
        background: rgba(var(--glow), 0.15);
        border-color: rgba(var(--glow), 0.5);
        color: rgba(var(--glow), 1);
        box-shadow: 0 0 18px rgba(var(--glow), 0.3);
    }

    .act-btn.edit:hover {
        background: rgba(255, 181, 71, 0.15);
        border-color: rgba(255, 181, 71, 0.5);
        color: #ffb547;
        box-shadow: 0 0 18px rgba(255, 181, 71, 0.3);
    }

    .act-btn.delete:hover {
        background: rgba(255, 99, 99, 0.15);
        border-color: rgba(255, 99, 99, 0.5);
        color: #ff6363;
        box-shadow: 0 0 18px rgba(255, 99, 99, 0.3);
    }

    /* Empty state */
    .empty-wrap {
        text-align: center;
        padding: 70px 20px;
    }

    .empty-icon {
        width: 90px;
        height: 90px;
        border-radius: 24px;
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        border: 1px solid var(--line);
        box-shadow: 0 0 28px rgba(var(--glow), 0.2);
    }

    .empty-icon i {
        font-size: 40px;
        color: var(--muted);
    }

    .empty-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin-bottom: 10px;
    }

    .empty-desc {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 28px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .users-tree-page { padding: 24px 16px 40px; }
        .users-topbar { flex-direction: column; align-items: flex-start; }
        .btn-add { width: 100%; justify-content: center; }
        .tree-wrap { padding: 24px 16px; }
        .tree-header h2 { font-size: 20px; }
        .user-card { width: 100%; max-width: 100%; }
        .stat-value { font-size: 24px; }
    }
</style>

<div class="users-tree-page">

    {{-- Фоновые blob-ы --}}
    <div class="tree-blob tree-blob-1"></div>
    <div class="tree-blob tree-blob-2"></div>
    <div class="tree-blob tree-blob-3"></div>

    {{-- TOP BAR --}}
    <div class="users-topbar">
        <div class="users-topbar-left">
            <div class="users-topbar-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div class="users-topbar-title">{{ $companyName }}</div>
                <div class="users-topbar-subtitle" data-i18n="teamStructure">Структура команды</div>
            </div>
        </div>

        @if($authUser->isAdmin())
        <a href="{{ route('users.create') }}" class="btn-add">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span data-i18n="addBtn">Добавить</span>
        </a>
        @endif
    </div>

    {{-- STATS ROW --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon users">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <div class="stat-value">{{ $users->count() }}</div>
                <div class="stat-label" data-i18n="totalMembers">Всего участников</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon leaders">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <div class="stat-value">{{ $users->where('level', 1)->count() }}</div>
                <div class="stat-label" data-i18n="leaders">Лидеры</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon levels">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
            </div>
            <div>
                <div class="stat-value">{{ $groupedByLevel->count() }}</div>
                <div class="stat-label" data-i18n="levels">Уровней</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon online">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <div>
                <div class="stat-value">{{ $users->filter(fn($u) => $u->isOnline())->count() }}</div>
                <div class="stat-label" data-i18n="onlineNow">Сейчас онлайн</div>
            </div>
        </div>
    </div>

    {{-- TREE WRAP --}}
    <div class="tree-wrap">
        <div class="tree-header">
            <h2 data-i18n="hierarchyTitle">Иерархическое дерево</h2>
            <p data-i18n="hierarchySubtitle">Структура команды по уровням</p>
        </div>

        @foreach($groupedByLevel as $level => $levelUsers)
        @php
        $lvlClass = match($level) {
        1 => 'lvl-1', 2 => 'lvl-2', 3 => 'lvl-3',
        4 => 'lvl-4', 5 => 'lvl-5', default => 'lvl-default',
        };
        $lvlIcon = match($level) {
        1 => '', 2 => '', 3 => '', 4 => '', 5 => '', default => '',
        };
        @endphp

        @if(!$loop->first)
        <div class="level-connector"></div>
        @endif

        <div class="level-section">
            <div class="level-header">
                <div class="level-bar {{ $lvlClass }}">
                    <span>{{ $lvlIcon }} Уровень {{ $level }}</span>
                </div>
            </div>

            <div class="users-grid">
                @foreach($levelUsers as $user)
                @php
                $creator = ($user->created_by && $users->has($user->created_by)) ? $users->get($user->created_by) : null;
                $canEdit = $authUser->isAdmin() || ($user->id === $authUser->id);
                $canDelete = $authUser->isAdmin() && ($user->id !== $authUser->id);
                @endphp

                <div class="user-card">
                    <div class="user-photo">
                        @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                        @else
                        <div class="user-photo-placeholder">
                            {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                        </div>
                        @endif
                        <div class="user-photo-gradient"></div>

                        <div class="photo-top">
                            @if($user->isOnline())
                            <span class="status-pill online">
                                <span class="status-dot active"></span>
                                <span data-i18n="online">Онлайн</span>
                            </span>
                            @else
                            <span class="status-pill offline">
                                <span class="status-dot"></span>
                                <span data-i18n="offline">Офлайн</span>
                            </span>
                            @endif

                            <span class="level-pill">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                L{{ $user->level }}
                            </span>
                        </div>
                    </div>

                    <div class="user-body">
                        <h3 class="user-name">
                            <a href="{{ route('users.show', $user->id) }}">{{ $user->name }}</a>
                        </h3>

                        <span class="user-role">{{ $user->role }}</span>

                        @if($user->isAdmin())
                        <div class="admin-tag-wrapper">
                            <span class="admin-tag">Admin</span>
                        </div>
                        @endif

                        <div>
                            <div class="contact-row">
                                <i class="bi bi-envelope-fill"></i>
                                <span title="{{ $user->email }}">{{ $user->email }}</span>
                            </div>
                            <div class="contact-row">
                                <i class="bi bi-telephone-fill"></i>
                                <span>{{ $user->phone ?? '—' }}</span>
                            </div>
                        </div>

                        @if($creator)
                        <div class="creator-box">
                            <div class="creator-box-label" data-i18n="createdBy">Создан пользователем</div>
                            <div class="creator-box-name">{{ $creator->name }}</div>
                        </div>
                        @endif

                        <div class="action-row">
                            <a href="{{ route('users.show', $user->id) }}" class="act-btn view">
                                <i class="bi bi-eye-fill"></i>
                                <span data-i18n="view">Смотреть</span>
                            </a>

                            @if($canEdit)
                            <a href="{{ route('users.edit', $user->id) }}" class="act-btn edit">
                                <i class="bi bi-pencil-fill"></i>
                                <span data-i18n="edit">Изменить</span>
                            </a>
                            @endif

                            @if($canDelete)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="flex: 1; margin: 0;" onsubmit="return confirm('Удалить пользователя?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="act-btn delete" style="width: 100%;">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        @if($users->isEmpty())
        <div class="empty-wrap">
            <div class="empty-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="empty-title" data-i18n="noUsersTitle">Команда пуста</div>
            <div class="empty-desc" data-i18n="noUsers">Пока нет участников</div>
            @if($authUser->isAdmin())
            <a href="{{ route('users.create') }}" class="btn-add" style="display: inline-flex;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span data-i18n="addFirst">Добавить первого</span>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ ДЕРЕВА ПОЛЬЗОВАТЕЛЕЙ
        // ============================================================
        const USERS_TREE_TRANSLATIONS = {
            ru: {
                addBtn: 'Добавить',
                online: 'Онлайн',
                offline: 'Офлайн',
                view: 'Смотреть',
                edit: 'Изменить',
                delete: 'Удалить',
                createdBy: 'Создан пользователем',
                teamStructure: 'Структура команды',
                totalMembers: 'Всего участников',
                leaders: 'Лидеры',
                levels: 'Уровней',
                onlineNow: 'Сейчас онлайн',
                hierarchyTitle: 'Иерархическое дерево',
                hierarchySubtitle: 'Структура команды по уровням',
                noUsers: 'Пока нет участников',
                noUsersTitle: 'Команда пуста',
                addFirst: 'Добавить первого',
                levelPrefix: 'Уровень',
                adminTag: 'Admin',
                confirmDelete: 'Удалить пользователя? Это действие необратимо.'
            },
            tj: {
                addBtn: 'Илова кардан',
                online: 'Онлайн',
                offline: 'Офлайн',
                view: 'Дидан',
                edit: 'Таҳрир',
                delete: 'Нест кардан',
                createdBy: 'Аз ҷониби',
                teamStructure: 'Сохтори даста',
                totalMembers: 'Ҳамагӣ аъзоён',
                leaders: 'Роҳбарон',
                levels: 'Сатҳҳо',
                onlineNow: 'Ҳозир онлайн',
                hierarchyTitle: 'Дарахти иерархия',
                hierarchySubtitle: 'Сохтори даста аз рӯи сатҳҳо',
                noUsers: 'Ҳоло иштирокчиён нестанд',
                noUsersTitle: 'Даста холӣ аст',
                addFirst: 'Аввалинро илова кунед',
                levelPrefix: 'Сатҳи',
                adminTag: 'Админ',
                confirmDelete: 'Корбарро нест мекунед? Ин амал бозгашт надорад.'
            },
            en: {
                addBtn: 'Add',
                online: 'Online',
                offline: 'Offline',
                view: 'View',
                edit: 'Edit',
                delete: 'Delete',
                createdBy: 'Created by',
                teamStructure: 'Team Structure',
                totalMembers: 'Total Members',
                leaders: 'Leader',
                levels: 'Levels',
                onlineNow: 'Online Now',
                hierarchyTitle: 'Hierarchy Tree',
                hierarchySubtitle: 'Team structure by levels',
                noUsers: 'No participants yet',
                noUsersTitle: 'Team is empty',
                addFirst: 'Add first',
                levelPrefix: 'Level',
                adminTag: 'Admin',
                confirmDelete: 'Delete this user? This action cannot be undone.'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applyUsersTreeTranslations(lang) {
            const dict = USERS_TREE_TRANSLATIONS[lang] || USERS_TREE_TRANSLATIONS.ru;

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

                if (newEl.tagName === 'FORM') {
                    newEl.onsubmit = (e) => {
                        if (!confirm(message)) e.preventDefault();
                    };
                } else {
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
        // ПАРАЛЛАКС ДЛЯ ФОНОВЫХ ПЯТЕН
        // ============================================================
        const blobs = document.querySelectorAll('.tree-blob');
        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 30;
            const y = (e.clientY / window.innerHeight - 0.5) * 30;
            blobs.forEach((blob, i) => {
                const factor = (i + 1) * 0.4;
                if (i === 2) {
                    blob.style.transform = `translate(calc(-50% + ${x * factor}px), calc(-50% + ${y * factor}px))`;
                } else {
                    blob.style.transform = `translate(${x * factor}px, ${y * factor}px)`;
                }
            });
        });

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyUsersTreeTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyUsersTreeTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyUsersTreeTranslations(e.newValue);
            }
        });
    });
</script>

@endsection