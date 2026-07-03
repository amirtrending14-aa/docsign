@extends('layouts.admin')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .no-company-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 24px;
        color: var(--text);
        font-family: 'Inter', sans-serif;
        position: relative;
        overflow: hidden;
    }

    /* Фоновые blob-ы */
    .nc-blob {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        filter: blur(110px);
        opacity: 0.4;
    }

    .nc-blob-1 {
        top: -150px;
        left: -150px;
        width: 550px;
        height: 550px;
        background: radial-gradient(circle, rgba(var(--glow), 0.35) 0%, transparent 70%);
        animation: blobFloat 20s ease-in-out infinite;
    }

    .nc-blob-2 {
        bottom: -150px;
        right: -150px;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.3) 0%, transparent 70%);
        animation: blobFloat 25s ease-in-out infinite reverse;
    }

    .nc-blob-3 {
        top: 50%;
        left: 50%;
        width: 450px;
        height: 450px;
        transform: translate(-50%, -50%);
        background: radial-gradient(circle, rgba(236, 72, 153, 0.25) 0%, transparent 70%);
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

    /* === CENTER CARD === */
    .nc-card {
        position: relative;
        max-width: 520px;
        width: 100%;
        text-align: center;
        padding: 56px 44px;
        background: linear-gradient(180deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 1px solid var(--line);
        border-radius: 24px;
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        z-index: 1;
        animation: cardAppear 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes cardAppear {
        0% { opacity: 0; transform: translateY(20px) scale(0.97); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    .nc-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: 24px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.6), transparent 40%, transparent 60%, rgba(168, 85, 247, 0.4));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.8;
        pointer-events: none;
    }

    /* Иконка здания */
    .nc-icon-wrap {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 28px;
        display: grid;
        place-items: center;
    }

    .nc-icon-bg {
        position: absolute;
        inset: 0;
        border-radius: 28px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.35), rgba(168, 85, 247, 0.25));
        box-shadow:
            0 20px 50px -10px rgba(var(--glow), 0.5),
            inset 0 0 30px rgba(255,255,255,0.1);
        animation: iconFloat 4s ease-in-out infinite;
    }

    .nc-icon-bg::before {
        content: "";
        position: absolute;
        inset: -2px;
        border-radius: 30px;
        padding: 2px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.8), rgba(168, 85, 247, 0.6));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.7;
    }

    @keyframes iconFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    .nc-icon-glow {
        position: absolute;
        inset: -20px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(var(--glow), 0.4) 0%, transparent 60%);
        filter: blur(20px);
        animation: glowPulse 3s ease-in-out infinite;
    }

    @keyframes glowPulse {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.1); }
    }

    .nc-icon-svg {
        position: relative;
        width: 56px;
        height: 56px;
        color: #ffffff;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
        z-index: 1;
    }

    /* Заголовок */
    .nc-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text);
        letter-spacing: -0.5px;
        margin: 0 0 12px;
        line-height: 1.2;
    }

    .nc-title .accent {
        background: linear-gradient(135deg, rgba(var(--glow), 1), rgba(168, 85, 247, 1));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .nc-desc {
        font-size: 14px;
        color: var(--muted);
        font-weight: 500;
        line-height: 1.6;
        margin: 0 0 32px;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Разделитель */
    .nc-divider {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 auto 28px;
        max-width: 280px;
    }

    .nc-divider-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--line), transparent);
    }

    .nc-divider-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(var(--glow), 0.8);
        box-shadow: 0 0 12px rgba(var(--glow), 0.8);
    }

    /* Инфо-блок */
    .nc-info-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--line);
        border-radius: 14px;
        margin-bottom: 28px;
        text-align: left;
        position: relative;
        overflow: hidden;
    }

    .nc-info-box::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, rgba(var(--glow), 1), rgba(var(--glow), 0.3));
        box-shadow: 0 0 12px rgba(var(--glow), 0.6);
    }

    .nc-info-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(var(--glow), 0.15);
        border: 1px solid rgba(var(--glow), 0.3);
        display: grid;
        place-items: center;
        flex-shrink: 0;
        box-shadow: inset 0 0 10px rgba(var(--glow), 0.2);
    }

    .nc-info-icon svg {
        width: 18px;
        height: 18px;
        color: rgba(var(--glow), 1);
    }

    .nc-info-text {
        flex: 1;
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
        line-height: 1.5;
    }

    .nc-info-text strong {
        color: var(--text);
        font-weight: 700;
    }

    /* Кнопка */
    .nc-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 32px;
        background: linear-gradient(180deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.65));
        color: #0a0d14;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow:
            0 10px 30px rgba(var(--glow), 0.4),
            inset 0 1px 0 rgba(255,255,255,0.3);
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .nc-btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }

    .nc-btn:hover::before {
        left: 100%;
    }

    .nc-btn:hover {
        transform: translateY(-3px);
        box-shadow:
            0 16px 40px rgba(var(--glow), 0.6),
            inset 0 1px 0 rgba(255,255,255,0.4);
        filter: brightness(1.08);
    }

    .nc-btn svg {
        width: 16px;
        height: 16px;
    }

    /* Декоративные частицы */
    .nc-particles {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
        z-index: 0;
    }

    .nc-particle {
        position: absolute;
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: rgba(var(--glow), 0.6);
        box-shadow: 0 0 8px rgba(var(--glow), 0.8);
        animation: particleFloat 8s linear infinite;
    }

    .nc-particle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; animation-duration: 9s; }
    .nc-particle:nth-child(2) { top: 60%; left: 85%; animation-delay: 2s; animation-duration: 11s; }
    .nc-particle:nth-child(3) { top: 80%; left: 20%; animation-delay: 4s; animation-duration: 10s; }
    .nc-particle:nth-child(4) { top: 30%; left: 75%; animation-delay: 1s; animation-duration: 12s; }
    .nc-particle:nth-child(5) { top: 70%; left: 50%; animation-delay: 3s; animation-duration: 8s; }
    .nc-particle:nth-child(6) { top: 15%; left: 60%; animation-delay: 5s; animation-duration: 13s; }

    @keyframes particleFloat {
        0% { transform: translateY(0) translateX(0); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(-100px) translateX(30px); opacity: 0; }
    }

    /* Responsive */
    @media (max-width: 640px) {
        .nc-card { padding: 40px 24px; }
        .nc-title { font-size: 24px; }
        .nc-icon-wrap { width: 100px; height: 100px; margin-bottom: 24px; }
        .nc-icon-svg { width: 46px; height: 46px; }
        .nc-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="no-company-page">

    {{-- Фоновые blob-ы --}}
    <div class="nc-blob nc-blob-1"></div>
    <div class="nc-blob nc-blob-2"></div>
    <div class="nc-blob nc-blob-3"></div>

    {{-- Частицы --}}
    <div class="nc-particles">
        <div class="nc-particle"></div>
        <div class="nc-particle"></div>
        <div class="nc-particle"></div>
        <div class="nc-particle"></div>
        <div class="nc-particle"></div>
        <div class="nc-particle"></div>
    </div>

    {{-- Карточка --}}
    <div class="nc-card">

        {{-- Иконка --}}
        <div class="nc-icon-wrap">
            <div class="nc-icon-glow"></div>
            <div class="nc-icon-bg"></div>
            <svg class="nc-icon-svg" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>

        {{-- Заголовок --}}
        <h1 class="nc-title">
            <span data-i18n="title">Нет </span><span class="accent" data-i18n="titleAccent">компании</span>
        </h1>

        {{-- Описание --}}
        <p class="nc-desc" data-i18n="desc">
            Вы не привязаны ни к одной компании. Обратитесь к администратору для добавления в команду.
        </p>

        {{-- Разделитель --}}
        <div class="nc-divider">
            <div class="nc-divider-line"></div>
            <div class="nc-divider-dot"></div>
            <div class="nc-divider-line"></div>
        </div>

        {{-- Инфо-блок --}}
        <div class="nc-info-box">
            <div class="nc-info-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="nc-info-text">
                <strong data-i18n="infoTitle">Что делать?</strong>
                <span data-i18n="infoDesc"> Свяжитесь с вашим администратором системы, чтобы он добавил вас в команду компании.</span>
            </div>
        </div>

        {{-- Кнопка --}}
        <a href="{{ url('/') }}" class="nc-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span data-i18n="homeBtn">На главную</span>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ "НЕТ КОМПАНИИ"
        // ============================================================
        const NO_COMPANY_TRANSLATIONS = {
            ru: {
                title: 'Нет ',
                titleAccent: 'компании',
                desc: 'Вы не привязаны ни к одной компании. Обратитесь к администратору для добавления в команду.',
                infoTitle: 'Что делать?',
                infoDesc: ' Свяжитесь с вашим администратором системы, чтобы он добавил вас в команду компании.',
                homeBtn: 'На главную'
            },
            tj: {
                title: 'Ширкат ',
                titleAccent: 'нест',
                desc: 'Шумо ба ягон ширкат пайваст нашудаед. Барои илова шудан ба даста бо администратор тамос гиред.',
                infoTitle: 'Чӣ бояд кард?',
                infoDesc: ' Бо администратори системаи худ тамос гиред, то шуморо ба дастаи ширкат илова кунад.',
                homeBtn: 'Ба саҳифаи асосӣ'
            },
            en: {
                title: 'No ',
                titleAccent: 'company',
                desc: 'You are not attached to any company. Contact your administrator to be added to the team.',
                infoTitle: 'What to do?',
                infoDesc: ' Contact your system administrator to be added to the company team.',
                homeBtn: 'Go Home'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applyNoCompanyTranslations(lang) {
            const dict = NO_COMPANY_TRANSLATIONS[lang] || NO_COMPANY_TRANSLATIONS.ru;

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
        // ПАРАЛЛАКС ДЛЯ ФОНОВЫХ ПЯТЕН
        // ============================================================
        const blobs = document.querySelectorAll('.nc-blob');
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
        applyNoCompanyTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyNoCompanyTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyNoCompanyTranslations(e.newValue);
            }
        });
    });
</script>

@endsection