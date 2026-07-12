@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .font-inter { font-family: 'Inter', sans-serif; }

    /* === DOC SIGN PROFILE STYLE === */
    .profile-page {
        min-height: 100vh;
        padding: 32px 24px;
        color: var(--text);
    }

    /* Заголовок страницы */
    .profile-header {
        max-width: 1200px;
        margin: 0 auto 28px;
    }

    .profile-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .profile-title::before {
        content: "";
        width: 4px;
        height: 22px;
        background: linear-gradient(180deg, rgba(var(--glow), 1), rgba(var(--glow), 0.3));
        border-radius: 2px;
        box-shadow: 0 0 10px rgba(var(--glow), 0.6);
    }

    /* Сетка карточек */
    .profile-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
        align-items: stretch;
    }

    @media (min-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr 2fr;
        }
    }

    /* Карточка - glassmorphism */
    .profile-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .profile-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow),0.4), transparent 40%, transparent 60%, rgba(var(--glow),0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.6;
        pointer-events: none;
    }

    .profile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -20px rgba(var(--glow), 0.3);
    }

    /* Левая карточка - аватар */
    .avatar-card {
        padding: 32px 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        background: radial-gradient(ellipse at 50% 0%, rgba(var(--glow), 0.15), transparent 70%);
    }

    .avatar-box {
        width: 128px;
        height: 128px;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.6), rgba(var(--glow), 0.2));
        border: 3px solid rgba(10, 13, 20, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        box-shadow: 0 12px 28px rgba(var(--glow), 0.3), 0 0 0 1px rgba(var(--glow), 0.3);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .avatar-box:hover {
        transform: scale(1.03);
        box-shadow: 0 16px 36px rgba(var(--glow), 0.4), 0 0 0 1px rgba(var(--glow), 0.5);
    }

    .avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-letter {
        color: #ffffff;
        font-size: 56px;
        font-weight: 900;
        font-style: italic;
        text-shadow: 0 2px 12px rgba(0,0,0,0.3);
    }

    .profile-name {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 4px;
        letter-spacing: -0.3px;
    }

    .profile-email {
        font-size: 11px;
        font-weight: 600;
        color: var(--muted);
        margin: 0 0 20px;
        word-break: break-all;
        letter-spacing: 0.3px;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        background: rgba(var(--glow), 0.15);
        border: 1px solid rgba(var(--glow), 0.3);
        color: rgba(var(--glow), 1);
        font-family: 'JetBrains Mono', monospace;
        min-width: 120px;
        justify-content: center;
    }

    .role-badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #4cd982;
        box-shadow: 0 0 8px #4cd982;
    }

    /* Правая карточка - детали */
    .details-card {
        display: flex;
        flex-direction: column;
    }

    .details-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--line);
        background: rgba(255,255,255,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .details-header-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--muted);
    }

    .status-active {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        color: #4cd982;
        letter-spacing: 0.8px;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #4cd982;
        box-shadow: 0 0 0 3px rgba(76, 217, 130, 0.2), 0 0 8px rgba(76, 217, 130, 0.6);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .details-body {
        padding: 24px;
        flex-grow: 1;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    @media (min-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .info-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        margin-bottom: 6px;
        display: block;
    }

    .info-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text);
        letter-spacing: -0.2px;
        word-break: break-word;
    }

    .access-section {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--line);
    }

    .access-text {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--text);
        letter-spacing: 0.3px;
        line-height: 1.4;
    }

    .details-footer {
        padding: 0 24px 24px;
        display: flex;
        justify-content: flex-end;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(180deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.65));
        color: #0a0d14;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.25s ease;
        border: 1px solid transparent;
        box-shadow: 0 8px 24px rgba(var(--glow), 0.35), inset 0 1px 0 rgba(255,255,255,0.3);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(var(--glow), 0.5);
        filter: brightness(1.08);
    }

    .btn-edit:active {
        transform: scale(0.97);
    }

    .btn-edit svg {
        width: 12px;
        height: 12px;
    }

    /* === ACTIVITY GRID === */
    .activity-card {
        max-width: 1200px;
        margin: 20px auto 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        padding: 20px 24px;
        position: relative;
    }

    .activity-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow),0.4), transparent 40%, transparent 60%, rgba(var(--glow),0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.6;
        pointer-events: none;
    }

    .activity-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
        letter-spacing: -0.2px;
    }

    .activity-title span {
        color: rgba(var(--glow), 1);
        font-family: 'JetBrains Mono', monospace;
    }

    .gh-wrapper {
        overflow-x: auto;
        scrollbar-width: none;
        position: relative;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 8px;
    }
    .gh-wrapper::-webkit-scrollbar { display: none; }

    /* Индикатор скролла для мобильных */
    .scroll-indicator {
        display: none;
        text-align: center;
        font-size: 9px;
        color: var(--muted);
        margin-top: 8px;
        opacity: 0.7;
    }

    .gh-grid {
        display: inline-grid;
        grid-template-areas: ". months" "days squares";
        grid-template-columns: 45px 1fr;
        gap: 4px 8px;
        min-width: fit-content;
    }
    .gh-months {
        grid-area: months;
        display: grid;
        grid-template-columns: repeat({{ $weeksCount }}, 11px);
        gap: 3px;
        font-size: 9px;
        color: var(--muted);
        font-weight: 600;
        height: 16px;
        position: relative;
    }
    .gh-days {
        grid-area: days;
        display: grid;
        grid-template-rows: repeat(7, 11px);
        gap: 3px;
        font-size: 9px;
        color: var(--muted);
        font-weight: 600;
        user-select: none;
    }
    .gh-day-label {
        display: flex;
        align-items: center;
        height: 11px;
        line-height: 1;
    }
    .gh-squares {
        grid-area: squares;
        display: grid;
        grid-template-rows: repeat(7, 11px);
        grid-auto-flow: column;
        grid-auto-columns: 11px;
        gap: 3px;
    }
    .sq {
        width: 11px;
        height: 11px;
        border-radius: 2px;
        background-color: rgba(255,255,255,0.04);
        border: 1px solid var(--line);
        box-sizing: border-box;
        cursor: pointer;
        transition: all 0.1s ease;
    }
    .sq:hover {
        transform: scale(1.3);
        z-index: 5;
        border-color: rgba(var(--glow), 0.6);
    }
    .l1 { background-color: #9be9a8 !important; border: none; box-shadow: 0 0 8px rgba(155, 233, 168, 0.4); }
    .l2 { background-color: #40c463 !important; border: none; box-shadow: 0 0 8px rgba(64, 196, 99, 0.4); }
    .l3 { background-color: #30a14e !important; border: none; box-shadow: 0 0 8px rgba(48, 161, 78, 0.4); }
    .l4 { background-color: #216e39 !important; border: none; box-shadow: 0 0 8px rgba(33, 110, 57, 0.4); }

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
        font-size: 10px;
        font-weight: 600;
        color: var(--muted);
        flex-wrap: wrap;
        gap: 10px;
    }

    .activity-legend {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .activity-legend .sq {
        width: 10px;
        height: 10px;
        cursor: default;
    }

    .activity-legend .sq:hover { transform: none; }

    /* ============================================ */
    /* === ПОЛНАЯ АДАПТИВНОСТЬ === */
    /* ============================================ */

    /* Маленькие десктопы (до 1200px) */
    @media (max-width: 1200px) {
        .profile-page { padding: 28px 20px; }
        .profile-header { margin-bottom: 24px; }
        .profile-title { font-size: 21px; }
        .profile-grid { gap: 18px; }
        .avatar-card { padding: 28px 22px; }
        .avatar-box { width: 118px; height: 118px; border-radius: 18px; margin-bottom: 18px; }
        .avatar-letter { font-size: 52px; }
        .profile-name { font-size: 21px; }
        .profile-email { font-size: 10px; margin-bottom: 18px; }
        .role-badge { padding: 5px 14px; font-size: 9px; }
        .details-header { padding: 16px 22px; }
        .details-body { padding: 22px; }
        .info-grid { gap: 18px; }
        .info-value { font-size: 13px; }
        .access-section { margin-top: 22px; padding-top: 18px; }
        .access-text { font-size: 11px; }
        .details-footer { padding: 0 22px 22px; }
        .btn-edit { padding: 9px 18px; font-size: 9px; }
        .activity-card { padding: 18px 22px; }
        .activity-title { font-size: 13px; margin-bottom: 14px; }
        .activity-footer { margin-top: 12px; }
    }

    /* Планшеты (до 992px) */
    @media (max-width: 992px) {
        .profile-page { padding: 24px 18px; }
        .profile-header { margin-bottom: 22px; }
        .profile-title { font-size: 20px; gap: 9px; }
        .profile-title::before { width: 3px; height: 20px; }
        .profile-grid { gap: 16px; }
        .avatar-card { padding: 26px 20px; }
        .avatar-box { width: 110px; height: 110px; border-radius: 17px; margin-bottom: 16px; }
        .avatar-letter { font-size: 48px; }
        .profile-name { font-size: 20px; margin-bottom: 3px; }
        .profile-email { font-size: 10px; margin-bottom: 16px; }
        .role-badge { padding: 5px 13px; font-size: 9px; min-width: 110px; }
        .details-header { padding: 15px 20px; }
        .details-header-label { font-size: 9px; letter-spacing: 1.3px; }
        .status-active { font-size: 9px; }
        .status-dot { width: 7px; height: 7px; }
        .details-body { padding: 20px; }
        .info-grid { gap: 16px; }
        .info-label { font-size: 9px; margin-bottom: 5px; }
        .info-value { font-size: 13px; }
        .access-section { margin-top: 20px; padding-top: 16px; }
        .access-text { font-size: 11px; }
        .details-footer { padding: 0 20px 20px; }
        .btn-edit { padding: 9px 17px; font-size: 9px; border-radius: 9px; }
        .btn-edit svg { width: 11px; height: 11px; }
        .activity-card { padding: 16px 20px; margin-top: 18px; }
        .activity-title { font-size: 13px; margin-bottom: 13px; }
        .activity-footer { margin-top: 11px; font-size: 9px; }
        .activity-legend { gap: 5px; }
        .activity-legend .sq { width: 9px; height: 9px; }
    }

    /* Большие телефоны (до 768px) */
    @media (max-width: 768px) {
        .profile-page { padding: 20px 16px; }
        .profile-header { margin-bottom: 20px; }
        .profile-title { font-size: 19px; gap: 8px; }
        .profile-title::before { width: 3px; height: 19px; }
        .profile-grid { gap: 15px; }
        .avatar-card { padding: 24px 18px; }
        .avatar-box { width: 100px; height: 100px; border-radius: 16px; margin-bottom: 15px; border-width: 2px; }
        .avatar-letter { font-size: 44px; }
        .profile-name { font-size: 19px; margin-bottom: 3px; }
        .profile-email { font-size: 10px; margin-bottom: 15px; }
        .role-badge { padding: 5px 12px; font-size: 9px; min-width: 100px; }
        .details-header { padding: 14px 18px; }
        .details-header-label { font-size: 9px; letter-spacing: 1.2px; }
        .status-active { font-size: 9px; gap: 5px; }
        .status-dot { width: 7px; height: 7px; }
        .details-body { padding: 18px; }
        .info-grid { gap: 15px; }
        .info-label { font-size: 9px; margin-bottom: 5px; letter-spacing: 0.7px; }
        .info-value { font-size: 12px; }
        .access-section { margin-top: 18px; padding-top: 15px; }
        .access-text { font-size: 11px; }
        .details-footer { padding: 0 18px 18px; }
        .btn-edit {
            padding: 10px 18px;
            font-size: 10px;
            border-radius: 9px;
            gap: 7px;
            min-height: 44px; /* Touch-friendly */
        }
        .btn-edit svg { width: 11px; height: 11px; }
        .activity-card { padding: 15px 18px; margin-top: 16px; }
        .activity-title { font-size: 12px; margin-bottom: 12px; }
        .gh-grid { grid-template-columns: 40px 1fr; gap: 3px 7px; }
        .gh-months { grid-template-columns: repeat({{ $weeksCount }}, 10px); font-size: 8px; }
        .gh-days { grid-template-rows: repeat(7, 10px); font-size: 8px; }
        .gh-day-label { height: 10px; }
        .gh-squares { grid-template-rows: repeat(7, 10px); grid-auto-columns: 10px; }
        .sq { width: 10px; height: 10px; }
        .activity-footer { margin-top: 10px; font-size: 9px; }
        .activity-legend { gap: 4px; }
        .activity-legend .sq { width: 9px; height: 9px; }
        .scroll-indicator { display: block; }
    }

    /* Телефоны (до 640px) */
    @media (max-width: 640px) {
        .profile-page { padding: 18px 14px; }
        .profile-header { margin-bottom: 18px; }
        .profile-title { font-size: 18px; gap: 7px; }
        .profile-title::before { width: 3px; height: 18px; }
        .profile-grid { gap: 14px; }
        .avatar-card { padding: 22px 16px; }
        .avatar-box { width: 90px; height: 90px; border-radius: 14px; margin-bottom: 14px; }
        .avatar-letter { font-size: 40px; }
        .profile-name { font-size: 18px; margin-bottom: 2px; }
        .profile-email { font-size: 9px; margin-bottom: 14px; }
        .role-badge { padding: 4px 11px; font-size: 8px; min-width: 90px; }
        .details-header { padding: 13px 16px; }
        .details-header-label { font-size: 8px; letter-spacing: 1.1px; }
        .status-active { font-size: 8px; gap: 4px; }
        .status-dot { width: 6px; height: 6px; }
        .details-body { padding: 16px; }
        .info-grid { gap: 14px; }
        .info-label { font-size: 8px; margin-bottom: 4px; }
        .info-value { font-size: 12px; }
        .access-section { margin-top: 16px; padding-top: 14px; }
        .access-text { font-size: 10px; }
        .details-footer {
            padding: 0 16px 16px;
            justify-content: stretch; /* Кнопка на всю ширину */
        }
        .btn-edit {
            padding: 10px 16px;
            font-size: 10px;
            border-radius: 8px;
            gap: 6px;
            width: 100%;
            justify-content: center;
            min-height: 44px;
        }
        .btn-edit svg { width: 10px; height: 10px; }
        .activity-card { padding: 14px 16px; margin-top: 15px; }
        .activity-title { font-size: 12px; margin-bottom: 11px; }
        .gh-grid { grid-template-columns: 35px 1fr; gap: 3px 6px; }
        .gh-months { grid-template-columns: repeat({{ $weeksCount }}, 9px); font-size: 8px; }
        .gh-days { grid-template-rows: repeat(7, 9px); font-size: 8px; }
        .gh-day-label { height: 9px; }
        .gh-squares { grid-template-rows: repeat(7, 9px); grid-auto-columns: 9px; }
        .sq { width: 9px; height: 9px; }
        .activity-footer {
            margin-top: 9px;
            font-size: 9px;
            gap: 8px;
            flex-direction: column;
            align-items: flex-start;
        }
        .activity-legend { gap: 4px; }
        .activity-legend .sq { width: 8px; height: 8px; }
    }

    /* Маленькие телефоны (до 480px) */
    @media (max-width: 480px) {
        .profile-page { padding: 16px 12px; }
        .profile-header { margin-bottom: 16px; }
        .profile-title { font-size: 17px; gap: 6px; }
        .profile-title::before { width: 3px; height: 17px; }
        .profile-grid { gap: 13px; }
        .avatar-card { padding: 20px 14px; }
        .avatar-box { width: 80px; height: 80px; border-radius: 13px; margin-bottom: 13px; }
        .avatar-letter { font-size: 36px; }
        .profile-name { font-size: 17px; margin-bottom: 2px; }
        .profile-email { font-size: 9px; margin-bottom: 13px; }
        .role-badge { padding: 4px 10px; font-size: 8px; min-width: 85px; }
        .details-header { padding: 12px 14px; }
        .details-header-label { font-size: 8px; letter-spacing: 1px; }
        .status-active { font-size: 8px; }
        .details-body { padding: 14px; }
        .info-grid { gap: 13px; }
        .info-label { font-size: 8px; margin-bottom: 4px; }
        .info-value { font-size: 11px; }
        .access-section { margin-top: 14px; padding-top: 12px; }
        .access-text { font-size: 10px; }
        .details-footer { padding: 0 14px 14px; }
        .btn-edit {
            padding: 10px 14px;
            font-size: 9px;
            border-radius: 8px;
            min-height: 44px;
        }
        .activity-card { padding: 13px 14px; margin-top: 14px; }
        .activity-title { font-size: 11px; margin-bottom: 10px; }
        .gh-grid { grid-template-columns: 32px 1fr; gap: 2px 5px; }
        .gh-months { grid-template-columns: repeat({{ $weeksCount }}, 8px); font-size: 7px; }
        .gh-days { grid-template-rows: repeat(7, 8px); font-size: 7px; }
        .gh-day-label { height: 8px; }
        .gh-squares { grid-template-rows: repeat(7, 8px); grid-auto-columns: 8px; }
        .sq { width: 8px; height: 8px; }
        .activity-footer { margin-top: 8px; font-size: 8px; gap: 7px; }
        .activity-legend { gap: 3px; }
        .activity-legend .sq { width: 7px; height: 7px; }
    }

    /* Очень маленькие телефоны (до 380px) */
    @media (max-width: 380px) {
        .profile-page { padding: 14px 10px; }
        .profile-header { margin-bottom: 14px; }
        .profile-title { font-size: 16px; gap: 5px; }
        .profile-title::before { width: 2px; height: 16px; }
        .profile-grid { gap: 12px; }
        .avatar-card { padding: 18px 12px; }
        .avatar-box { width: 70px; height: 70px; border-radius: 12px; margin-bottom: 12px; }
        .avatar-letter { font-size: 32px; }
        .profile-name { font-size: 16px; margin-bottom: 2px; }
        .profile-email { font-size: 8px; margin-bottom: 12px; }
        .role-badge { padding: 4px 9px; font-size: 8px; min-width: 80px; }
        .details-header { padding: 11px 12px; }
        .details-header-label { font-size: 8px; }
        .status-active { font-size: 8px; }
        .details-body { padding: 12px; }
        .info-grid { gap: 12px; }
        .info-label { font-size: 7px; margin-bottom: 3px; }
        .info-value { font-size: 11px; }
        .access-section { margin-top: 12px; padding-top: 10px; }
        .access-text { font-size: 9px; }
        .details-footer { padding: 0 12px 12px; }
        .btn-edit {
            padding: 10px 13px;
            font-size: 9px;
            min-height: 44px;
        }
        .activity-card { padding: 12px; margin-top: 13px; }
        .activity-title { font-size: 11px; margin-bottom: 9px; }
        .gh-grid { grid-template-columns: 30px 1fr; }
        .gh-months { grid-template-columns: repeat({{ $weeksCount }}, 7px); font-size: 7px; }
        .gh-days { grid-template-rows: repeat(7, 7px); font-size: 7px; }
        .gh-day-label { height: 7px; }
        .gh-squares { grid-template-rows: repeat(7, 7px); grid-auto-columns: 7px; }
        .sq { width: 7px; height: 7px; }
        .activity-footer { margin-top: 7px; font-size: 8px; }
        .activity-legend .sq { width: 7px; height: 7px; }
    }
</style>

<div class="profile-page font-inter">

    {{-- Заголовок --}}
    <div class="profile-header">
        <h1 class="profile-title">
            <span data-i18n="profileTitle">Профиль</span>
        </h1>
    </div>

    {{-- СЕТКА КАРТОЧЕК --}}
    <div class="profile-grid">

        {{-- ЛЕВАЯ КАРТОЧКА (АВАТАР) --}}
        <div class="profile-card avatar-card">
            <div class="avatar-box">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                @else
                <span class="avatar-letter">{{ Str::upper(Str::substr($user->name, 0, 1)) }}</span>
                @endif
            </div>

            <h2 class="profile-name">{{ $user->name }}</h2>
            <p class="profile-email">{{ $user->email }}</p>

            <div class="role-badge">
                {{ $user->role ?? 'Director' }}
            </div>
        </div>

        {{-- ПРАВАЯ КАРТОЧКА (ДЕТАЛИ) --}}
        <div class="profile-card details-card">
            <div class="details-header">
                <span class="details-header-label" data-i18n="mainInfo">Основная информация</span>
                <div class="status-active">
                    <span class="status-dot"></span>
                    <span data-i18n="statusActive">Активен</span>
                </div>
            </div>

            <div class="details-body">
                <div class="info-grid">
                    <div>
                        <label class="info-label" data-i18n="labelFullName">Полное имя</label>
                        <p class="info-value">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="info-label" data-i18n="labelEmail">Email</label>
                        <p class="info-value">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="info-label" data-i18n="labelCompany">Название компании</label>
                        <p class="info-value">{{ $user->company ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="info-label" data-i18n="labelPhone">Контактный телефон</label>
                        <p class="info-value">{{ $user->phone ?? '+992 00 000 0000' }}</p>
                    </div>
                    <div>
                        <label class="info-label" data-i18n="labelCreatedAt">Дата создания</label>
                        <p class="info-value">{{ $user->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                <div class="access-section">
                    <label class="info-label" data-i18n="labelAccess">Уровень доступа</label>
                    <p class="access-text" data-i18n="accessFull">Назорати пурраи маъмурӣ</p>
                </div>
            </div>

            <div class="details-footer">
                <a href="{{ route('profile.edit', $user->id) }}" class="btn-edit">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                    </svg>
                    <span data-i18n="btnEdit">Таҳрир</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ACTIVITY GRID --}}
    <div class="activity-card">
        <div class="activity-title">
            <span>{{ array_sum($activityData) }}</span>
            <span data-i18n="activitySummary">вкладов в</span> {{ $year }}
        </div>

        <div class="gh-wrapper">
            <div class="gh-grid">
                <div class="gh-months select-none">
                    @php $lastMonth = -1; @endphp
                    @for($w = 0; $w < $weeksCount; $w++)
                    @php
                    $dateInWeek = $startDate->copy()->addWeeks($w);
                    $month = $dateInWeek->month;
                    @endphp
                    <div style="grid-column: {{ $w + 1 }}; position: relative;">
                        @if($month != $lastMonth && $dateInWeek->year == $year)
                        <span style="position: absolute; left: 0; bottom: 0; white-space: nowrap;">
                                        {{ $dateInWeek->translatedFormat('M') }}
                                    </span>
                        @php $lastMonth = $month; @endphp
                        @endif
                    </div>
                    @endfor
                </div>

                <div class="gh-squares">
                    @for($i = 0; $i < ($weeksCount * 7); $i++)
                    @php
                    $day = $startDate->copy()->addDays($i);
                    $isCurrentYear = $day->year == $year;
                    $key = $day->toDateString();
                    $count = $activityData[$key] ?? 0;

                    $level = 0;
                    if ($count > 0) $level = 1;
                    if ($count > 2) $level = 2;
                    if ($count > 5) $level = 3;
                    if ($count > 10) $level = 4;

                    $tooltipText = ($count > 0 ? $count : 'No') . ' contributions on ' . $day->translatedFormat('j F, Y');
                    @endphp
                    @if($isCurrentYear)
                    <div class="sq {{ $level ? 'l'.$level : '' }}" data-tippy-content="{{ $tooltipText }}"></div>
                    @else
                    <div class="sq" style="background: transparent; border: none; cursor: default;"></div>
                    @endif
                    @endfor
                </div>
            </div>
        </div>

        <div class="scroll-indicator" data-i18n="scrollHint">← Прокрутите для просмотра →</div>

        <div class="activity-footer">
            <span data-i18n="activityLegend">Как мы считаем вклады</span>
            <div class="activity-legend">
                <span data-i18n="legendLess">Меньше</span>
                <div class="sq"></div>
                <div class="sq l1"></div>
                <div class="sq l2"></div>
                <div class="sq l3"></div>
                <div class="sq l4"></div>
                <span data-i18n="legendMore">Больше</span>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css" />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const PROFILE_TRANSLATIONS = {
            ru: {
                profileTitle: 'Профиль',
                mainInfo: 'Основная информация',
                statusActive: 'Активен',
                labelFullName: 'Полное имя',
                labelEmail: 'Email адрес',
                labelCompany: 'Название компании',
                labelPhone: 'Контактный телефон',
                labelCreatedAt: 'Дата создания',
                labelAccess: 'Уровень доступа',
                accessFull: 'Полный административный контроль',
                btnEdit: 'Изменить',
                activitySummary: 'вкладов в',
                activityLegend: 'Как мы считаем вклады',
                legendLess: 'Меньше',
                legendMore: 'Больше',
                scrollHint: '← Прокрутите для просмотра →',
                dayMon: 'Пн', dayTue: 'Вт', dayWed: 'Ср', dayThu: 'Чт', dayFri: 'Пт', daySat: 'Сб', daySun: 'Вс'
            },
            tj: {
                profileTitle: 'Профил',
                mainInfo: 'Маълумоти асосӣ',
                statusActive: 'Фаъол',
                labelFullName: 'Номи пурра',
                labelEmail: 'Суроғаи Email',
                labelCompany: 'Номи ширкат',
                labelPhone: 'Телефони тамос',
                labelCreatedAt: 'Санаи эҷод',
                labelAccess: 'Сатҳи дастрасӣ',
                accessFull: 'Назорати пурраи маъмурӣ',
                btnEdit: 'Таҳрир',
                activitySummary: 'саҳмҳо дар соли',
                activityLegend: 'Чӣ тавр мо саҳмҳоро ҳисоб мекунем',
                legendLess: 'Камтар',
                legendMore: 'Бештар',
                scrollHint: '← Барои дидан скрол кунед →',
                dayMon: 'Дш', dayTue: 'Сш', dayWed: 'Чш', dayThu: 'Пш', dayFri: 'Ҷм', daySat: 'Шн', daySun: 'Як'
            },
            en: {
                profileTitle: 'Profile',
                mainInfo: 'Main Information',
                statusActive: 'Active',
                labelFullName: 'Full Name',
                labelEmail: 'Email Address',
                labelCompany: 'Company Name',
                labelPhone: 'Phone Number',
                labelCreatedAt: 'Created At',
                labelAccess: 'Access Level',
                accessFull: 'Full Administrative Control',
                btnEdit: 'Edit',
                activitySummary: 'contributions in',
                activityLegend: 'Learn how we count contributions',
                legendLess: 'Less',
                legendMore: 'More',
                scrollHint: '← Scroll to view →',
                dayMon: 'Mon', dayTue: 'Tue', dayWed: 'Wed', dayThu: 'Thu', dayFri: 'Fri', daySat: 'Sat', daySun: 'Sun'
            }
        };

        function applyProfileTranslations(lang) {
            const dict = PROFILE_TRANSLATIONS[lang] || PROFILE_TRANSLATIONS.ru;

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
        }

        tippy('[data-tippy-content]', {
            theme: 'dark',
            animation: 'fade',
            duration: [200, 50],
            offset: [0, 10],
            touch: ['hold', 500], // Улучшенная поддержка touch
        });

        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyProfileTranslations(initialLang);

        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyProfileTranslations(lang);
        });

        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyProfileTranslations(e.newValue);
            }
        });
    });
</script>
@endsection