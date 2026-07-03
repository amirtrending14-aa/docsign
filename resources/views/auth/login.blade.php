<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DocSign — Вход в систему ЭДО</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
            --primary-light: #818cf8;
            --accent: #06b6d4;
            --bg-dark: #0f172a;
            --bg-card: rgba(15, 23, 42, 0.6);
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border: rgba(148, 163, 184, 0.15);
            --glow: rgba(79, 70, 229, 0.4);
        }

        body {
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-dark);
            overflow: hidden;
            position: relative;
        }

        .bg-animation {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(79, 70, 229, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(6, 182, 212, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
            animation: bgShift 15s ease-in-out infinite alternate;
        }

        @keyframes bgShift {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-5%, -5%) rotate(3deg); }
        }

        .particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(129, 140, 248, 0.5);
            border-radius: 50%;
            animation: float linear infinite;
        }

        @keyframes float {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        .grid-overlay {
            position: fixed;
            inset: 0;
            z-index: 0;
            background-image:
                linear-gradient(rgba(148, 163, 184, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(148, 163, 184, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            animation: gridMove 20s linear infinite;
        }

        @keyframes gridMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(60px, 60px); }
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .lang-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 4px;
            gap: 2px;
        }

        .lang-btn {
            padding: 8px 14px;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-family: 'Figtree', sans-serif;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        .lang-btn:hover {
            color: var(--text-primary);
            background: rgba(79, 70, 229, 0.15);
        }

        .lang-btn.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 10px var(--glow);
        }

        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(40px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
            animation: cardAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
            box-shadow:
                0 0 0 1px rgba(148, 163, 184, 0.05),
                0 25px 80px rgba(0, 0, 0, 0.4),
                0 0 120px rgba(79, 70, 229, 0.08);
        }

        @keyframes cardAppear {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.96);
                filter: blur(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent), var(--primary-light));
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: -1px; left: -1px; right: -1px; bottom: -1px;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), transparent 40%, transparent 60%, rgba(6, 182, 212, 0.1));
            z-index: -1;
            pointer-events: none;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 36px;
            animation: logoAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        @keyframes logoAppear {
            0% { opacity: 0; transform: translateY(15px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .logo-img {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            margin: 0 auto 16px;
            display: block;
            box-shadow: 0 8px 30px rgba(79, 70, 229, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.05) rotate(-2deg);
        }

        .logo-title {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .logo-title span {
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-subtitle {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Laravel form styles */
        .form-group {
            margin-bottom: 20px;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .form-group:nth-child(1) { animation-delay: 0.3s; }
        .form-group:nth-child(2) { animation-delay: 0.4s; }

        @keyframes formAppear {
            0% { opacity: 0; transform: translateX(-15px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: var(--text-secondary);
            transition: color 0.3s ease;
            pointer-events: none;
            z-index: 2;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid var(--border);
            border-radius: 14px;
            color: var(--text-primary);
            font-family: 'Figtree', sans-serif;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input::placeholder {
            color: rgba(148, 163, 184, 0.4);
        }

        .form-input:focus {
            border-color: var(--primary);
            background: rgba(30, 41, 59, 0.8);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15), 0 0 20px rgba(79, 70, 229, 0.1);
        }

        .form-input:focus ~ .input-icon,
        .form-input:focus + .input-icon {
            color: var(--primary-light);
        }

        /* Override Laravel default input styles */
        .form-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px rgba(30, 41, 59, 0.9) inset !important;
            -webkit-text-fill-color: var(--text-primary) !important;
            caret-color: var(--text-primary);
        }

        .form-input.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
        }

        .error-message {
            font-size: 12px;
            color: #ef4444;
            margin-top: 6px;
            font-weight: 500;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .toggle-password:hover {
            color: var(--text-primary);
            background: rgba(148, 163, 184, 0.1);
        }

        .toggle-password svg {
            width: 18px;
            height: 18px;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.5s both;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            user-select: none;
        }

        .custom-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid var(--border);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .custom-checkbox svg {
            width: 12px;
            height: 12px;
            opacity: 0;
            transform: scale(0.5);
            transition: all 0.2s ease;
            color: white;
        }

        .remember-input {
            display: none;
        }

        .remember-input:checked + .custom-checkbox {
            background: var(--primary);
            border-color: var(--primary);
            box-shadow: 0 2px 8px var(--glow);
        }

        .remember-input:checked + .custom-checkbox svg {
            opacity: 1;
            transform: scale(1);
        }

        .forgot-link {
            font-size: 13px;
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--primary-light);
            transition: width 0.3s ease;
        }

        .forgot-link:hover::after {
            width: 100%;
        }

        .forgot-link:hover {
            color: var(--accent);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 14px;
            color: white;
            font-family: 'Figtree', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            letter-spacing: 0.3px;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.6s both;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--primary-light), var(--accent));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px var(--glow), 0 0 40px rgba(6, 182, 212, 0.15);
        }

        .submit-btn:hover::before {
            opacity: 1;
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .submit-btn .btn-text {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn .btn-arrow {
            transition: transform 0.3s ease;
        }

        .submit-btn:hover .btn-arrow {
            transform: translateX(4px);
        }

        .submit-btn.loading .btn-text {
            opacity: 0;
        }

        .submit-btn.loading::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            top: 50%;
            left: 50%;
            margin: -12px 0 0 -12px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
            gap: 16px;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.7s both;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .divider-text {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
            white-space: nowrap;
        }

        .register-section {
            text-align: center;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.8s both;
        }

        .register-text {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .register-link {
            color: var(--primary-light);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .register-link:hover {
            color: var(--accent);
            text-shadow: 0 0 20px rgba(6, 182, 212, 0.3);
        }

        .footer-badges {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 32px;
            animation: footerAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.9s both;
        }

        @keyframes footerAppear {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-secondary);
            opacity: 0.7;
        }

        .badge svg {
            width: 14px;
            height: 14px;
            color: var(--primary-light);
        }

        .copyright {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: rgba(148, 163, 184, 0.4);
            font-weight: 500;
            animation: footerAppear 0.8s cubic-bezier(0.16, 1, 0.3, 1) 1s both;
        }

        /* Session status banner */
        .session-status {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            animation: formAppear 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        .session-status.success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #34d399;
        }

        /* Notification toast */
        .notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            z-index: 1000;
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(20px);
        }

        .notification.show {
            transform: translateX(-50%) translateY(0);
        }

        .notification.success {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .notification.error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        @media (max-width: 520px) {
            .container { padding: 16px; }
            .login-card { padding: 36px 24px; border-radius: 20px; }
            .logo-title { font-size: 24px; }
            .lang-switcher { top: 12px; right: 12px; }
            .lang-btn { padding: 6px 10px; font-size: 12px; }
            .form-options { flex-direction: column; gap: 12px; align-items: flex-start; }
        }
    </style>
</head>
<body>
<!-- Background effects -->
<div class="bg-animation"></div>
<div class="grid-overlay"></div>
<div class="particles" id="particles"></div>

<!-- Notification toast -->
<div class="notification" id="notification"></div>

<!-- Language switcher -->
<div class="lang-switcher">
    <select class="lang-select" onchange="switchLang(this.value)">
        <option value="ru" selected>🇷🇺 RU</option>
        <option value="tj">🇹🇯 TJ</option>
        <option value="en">🇬🇧 EN</option>
    </select>
</div>


<style>
    .lang-switcher {
        display: inline-block;
        margin: 10px;
        font-family: 'Segoe UI', Roboto, sans-serif;
    }

    .lang-select {
        /* Основной стиль: темно-синий градиент */
        background: linear-gradient(145deg, #1a2a6c, #101a44);
        color: #ffffff;
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        outline: none;
        appearance: none; /* Убираем стандартную стрелку браузера */
        -webkit-appearance: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Эффект при наведении */
    .lang-select:hover {
        background: #1e3a8a;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        transform: translateY(-1px);
    }

    /* Фокус (когда нажали) */
    .lang-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4);
    }

    /* Стили для выпадающего списка (в некоторых браузерах) */
    .lang-select option {
        background-color: #101a44;
        color: white;
        padding: 10px;
    }
</style>

<div class="container">
    <div class="login-card">
        <!-- Logo -->
        <div class="logo-section">
            <img
                src="https://image.qwenlm.ai/public_source/5fabf35d-788a-476d-8837-6431dd4fb2c8/1bb634345-5339-4471-924b-764b665ee39d.png"
                alt="DocSign Logo"
                class="logo-img"
            >
            <div class="logo-title">Doc<span>Sign</span></div>
            <div class="logo-subtitle" data-i18n="subtitle">Система электронного документооборота</div>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="session-status success">{{ session('status') }}</div>
        @endif

        <!-- Laravel Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="return handleLogin(event)">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label class="form-label" for="email" data-i18n="emailLabel">Электронная почта</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input @error('email') error @enderror"
                        value="{{ old('email') }}"
                        data-i18n-placeholder="emailPlaceholder"
                        placeholder="name@company.com"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password" data-i18n="passwordLabel">Пароль</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input @error('password') error @enderror"
                        data-i18n-placeholder="passwordPlaceholder"
                        placeholder="••••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember" id="remember_me" class="remember-input">
                    <div class="custom-checkbox">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span data-i18n="remember">Запомнить меня</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}" data-i18n="forgot">Забыли пароль?</a>
                @endif
            </div>

            <!-- Submit -->
            <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">
                        <span data-i18n="loginBtn">Войти в систему</span>
                        <svg class="btn-arrow" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </span>
            </button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text" data-i18n="divider">или</span>
            <div class="divider-line"></div>
        </div>

        <div class="register-section">
            <p class="register-text">
                <span data-i18n="noAccount">Нет аккаунта?</span>
                <a href="{{ route('register') }}" class="register-link" data-i18n="register">Зарегистрироваться</a>
            </p>
        </div>
    </div>

    <!-- Footer badges -->

</div>

<script>
    // Generate particles
    function createParticles() {
        const container = document.getElementById('particles');
        const count = 30;
        for (let i = 0; i < count; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDuration = (Math.random() * 10 + 8) + 's';
            particle.style.animationDelay = (Math.random() * 10) + 's';
            particle.style.width = (Math.random() * 3 + 1) + 'px';
            particle.style.height = particle.style.width;
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            container.appendChild(particle);
        }
    }
    createParticles();

    // Translations
    const translations = {
        ru: {
            subtitle: 'Система электронного документооборота',
            emailLabel: 'Электронная почта',
            emailPlaceholder: 'name@company.com',
            passwordLabel: 'Пароль',
            passwordPlaceholder: '••••••••••',
            remember: 'Запомнить меня',
            forgot: 'Забыли пароль?',
            loginBtn: 'Войти в систему',
            divider: 'или',
            noAccount: 'Нет аккаунта?',
            register: 'Зарегистрироваться',
            badgeSecurity: 'Защита',
            badgeSign: 'ЭЦП',
            rights: 'Все права защищены.',
            invalidEmail: 'Неверный формат email',
            emptyFields: 'Заполните все поля'
        },
        tj: {
            subtitle: 'Системаи ҳуҷҷатгардонии электронӣ',
            emailLabel: 'Почтаи электронӣ',
            emailPlaceholder: 'name@company.com',
            passwordLabel: 'Рамз',
            passwordPlaceholder: '••••••••••',
            remember: 'Маро дар ёд дор',
            forgot: 'Рамзро фаромӯш кардед?',
            loginBtn: 'Ворид шудан',
            divider: 'ё',
            noAccount: 'исоб надоред?',
            register: 'Бақайдгирӣ',
            badgeSecurity: 'Ҳифз',
            badgeSign: 'ЭИИ',
            rights: 'Ҳамаи ҳуқуқҳо ҳифз шудаанд.',
            invalidEmail: 'Формати email нодуруст',
            emptyFields: 'Ҳамаи майдонҳоро пур кунед'
        },
        en: {
            subtitle: 'Electronic Document Management System',
            emailLabel: 'Email Address',
            emailPlaceholder: 'name@company.com',
            passwordLabel: 'Password',
            passwordPlaceholder: '••••••••••',
            remember: 'Remember me',
            forgot: 'Forgot password?',
            loginBtn: 'Sign In',
            divider: 'or',
            noAccount: "Don't have an account?",
            register: 'Sign Up',
            badgeSecurity: 'Security',
            badgeSign: 'EDS',
            rights: 'All rights reserved.',
            invalidEmail: 'Invalid email format',
            emptyFields: 'Please fill in all fields'
        }
    };

    let currentLang = 'ru';

    function switchLang(lang) {
        currentLang = lang;
        document.querySelectorAll('.lang-btn').forEach(function(btn) {
            btn.classList.toggle('active', btn.dataset.lang === lang);
        });
        document.documentElement.lang = lang;

        var t = translations[lang];

        document.querySelectorAll('[data-i18n]').forEach(function(el) {
            var key = el.getAttribute('data-i18n');
            if (t[key]) el.textContent = t[key];
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(function(el) {
            var key = el.getAttribute('data-i18n-placeholder');
            if (t[key]) el.placeholder = t[key];
        });
    }

    // Toggle password visibility
    var passwordVisible = false;
    function togglePassword() {
        passwordVisible = !passwordVisible;
        var input = document.getElementById('password');
        var icon = document.getElementById('eyeIcon');

        if (passwordVisible) {
            input.type = 'text';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    // Show notification
    function showNotification(message, type) {
        var notif = document.getElementById('notification');
        notif.textContent = message;
        notif.className = 'notification ' + type + ' show';
        setTimeout(function() {
            notif.classList.remove('show');
        }, 3000);
    }

    // Handle login with loading animation
    function handleLogin(e) {
        var email = document.getElementById('email');
        var password = document.getElementById('password');
        var t = translations[currentLang];
        var hasError = false;

        email.classList.remove('error');
        password.classList.remove('error');

        if (!email.value.trim()) {
            email.classList.add('error');
            showNotification(t.emptyFields, 'error');
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            email.classList.add('error');
            showNotification(t.invalidEmail, 'error');
            hasError = true;
        }

        if (!password.value.trim()) {
            password.classList.add('error');
            hasError = true;
        }

        if (hasError) return false;

        var btn = document.getElementById('submitBtn');
        btn.classList.add('loading');
        btn.disabled = true;
        return true;
    }

    // Clear error on input
    var emailInput = document.getElementById('email');
    var passInput = document.getElementById('password');
    if (emailInput) emailInput.addEventListener('input', function() { this.classList.remove('error'); });
    if (passInput) passInput.addEventListener('input', function() { this.classList.remove('error'); });
</script>
</body>
</html>

