
<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocSign — Система ЭДО</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/5968/5968517.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0.16/index.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/jetbrains-mono@5.0.16/index.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        doc: {
                            dark: '#020617',
                            deep: '#0a1628',
                            navy: '#0f1d3a',
                            blue: '#1e3a5f',
                            accent: '#3b82f6',
                            light: '#60a5fa',
                            glow: '#2563eb',
                            cyan: '#06b6d4',
                            purple: '#8b5cf6',
                        }
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-delay': 'float 6s ease-in-out 2s infinite',
                        'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                        'shimmer': 'shimmer 3s linear infinite',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'slide-down': 'slideDown 0.5s ease-out forwards',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'scale-in': 'scaleIn 0.6s ease-out forwards',
                        'orbit': 'orbit 20s linear infinite',
                        'spin-slow': 'spin 30s linear infinite',
                        'gradient-shift': 'gradientShift 8s ease infinite',
                        'typewriter': 'typewriter 2s steps(20) forwards',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                            '50%': { transform: 'translateY(-20px) rotate(2deg)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.3)' },
                            '50%': { boxShadow: '0 0 60px rgba(59, 130, 246, 0.6), 0 0 100px rgba(59, 130, 246, 0.3)' },
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% center' },
                            '100%': { backgroundPosition: '200% center' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(60px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.8)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        orbit: {
                            '0%': { transform: 'rotate(0deg) translateX(150px) rotate(0deg)' },
                            '100%': { transform: 'rotate(360deg) translateX(150px) rotate(-360deg)' },
                        },
                        gradientShift: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/@fontsource/inter@5.0.16/300,400,500,600,700,800,900.css');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #020617;
            color: #e2e8f0;
            overflow-x: hidden;
        }

        .gradient-text {
            background: linear-gradient(135deg, #60a5fa, #06b6d4, #8b5cf6);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
        }

        .gradient-text-warm {
            background: linear-gradient(135deg, #f59e0b, #ef4444, #ec4899);
            background-size: 200% 200%;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradientShift 4s ease infinite;
        }

        .glass {
            background: rgba(15, 29, 58, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        .glass-strong {
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 60px rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #3b82f6, #06b6d4);
            background-size: 200% 200%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid rgba(59, 130, 246, 0.5);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: #3b82f6;
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        }

        .line-glow {
            height: 1px;
            background: linear-gradient(90deg, transparent, #3b82f6, transparent);
        }

        .bg-grid {
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.03) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .scroll-reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .noise-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0.03;
            pointer-events: none;
            z-index: 9999;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
        }

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #3b82f6, #06b6d4);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link:hover {
            color: #60a5fa;
        }

        .counter {
            font-variant-numeric: tabular-nums;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            pointer-events: none;
        }

        .code-block {
            background: rgba(2, 6, 23, 0.8);
            border: 1px solid rgba(59, 130, 246, 0.15);
            font-family: 'JetBrains Mono', monospace;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e3a5f;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }

        .lang-btn.active {
            background: rgba(59, 130, 246, 0.2);
            color: #60a5fa;
            border-color: #3b82f6;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem !important;
            }
        }

        .feature-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(6, 182, 212, 0.1));
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .timeline-line {
            background: linear-gradient(180deg, #3b82f6, #06b6d4, #8b5cf6);
        }

        .shimmer-text {
            background: linear-gradient(90deg, #60a5fa, #ffffff, #60a5fa);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        .phone-input-wrapper {
            background: rgba(15, 29, 58, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.2);
            transition: all 0.3s ease;
        }
        .phone-input-wrapper:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
        }

        .stat-card {
            background: linear-gradient(135deg, rgba(15, 29, 58, 0.8), rgba(30, 58, 95, 0.4));
            border: 1px solid rgba(59, 130, 246, 0.15);
        }

        /* AI Section Styles */
        .section-analytics {
            background: #0f172a;
            padding: 20px;
            border-radius: 24px;
        }
        .acard {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .acard:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.7);
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
        }
        .slabel {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            font-weight: 700;
        }
        .bignum {
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -1.5px;
            color: #f8fafc;
            line-height: 1;
        }
        .progbg {
            height: 8px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            margin-top: 15px;
            overflow: hidden;
        }
        .progbg .fill {
            height: 100%;
            border-radius: 10px;
            transition: width 1.5s ease-in-out;
        }
        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .accent {
            width: 4px;
            height: 24px;
            border-radius: 4px;
            display: inline-block;
            margin-right: 12px;
            vertical-align: middle;
        }
        .apexcharts-tooltip {
            background: #1e293b !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
        }
    </style>
</head>
<body class="bg-grid">

<div class="noise-overlay"></div>

<!-- Navigation -->
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-blue-500/25 group-hover:shadow-blue-500/40 transition-all duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Doc<span class="gradient-text">Sign</span></span>
            </a>

            <div class="hidden lg:flex items-center gap-8">
                <a href="#features" class="nav-link text-sm font-medium text-slate-300" data-i18n="nav_features">Возможности</a>
                <a href="#technology" class="nav-link text-sm font-medium text-slate-300" data-i18n="nav_tech">Технологии</a>
                <a href="#security" class="nav-link text-sm font-medium text-slate-300" data-i18n="nav_security">Безопасность</a>
                <a href="#ai" class="nav-link text-sm font-medium text-slate-300" data-i18n="nav_ai">Анализ</a>
                <a href="#contact" class="nav-link text-sm font-medium text-slate-300" data-i18n="nav_contact">Контакты</a>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-1 glass rounded-lg p-1">
                    <select id="langSelect"
                            class="bg-transparent text-white text-xs font-medium px-2 py-1 rounded-md outline-none cursor-pointer transition-all duration-200"
                            onchange="localStorage.setItem('selectedLang', this.value); document.querySelector(`[data-lang='${this.value}']`).click()">
                        <option value="ru" class="bg-[#0f172a] text-white">RU</option>
                        <option value="en" class="bg-[#0f172a] text-white">EN</option>
                        <option value="tj" class="bg-[#0f172a] text-white">TJ</option>
                    </select>

                    <div class="hidden">
                        <button class="lang-btn active" data-lang="ru"></button>
                        <button class="lang-btn" data-lang="en"></button>
                        <button class="lang-btn" data-lang="tj"></button>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const savedLang = localStorage.getItem('selectedLang') || 'ru'; // Если ничего не сохранено, будет 'ru'
                            const select = document.getElementById('langSelect');

                            if (select) {
                                select.value = savedLang; // Ставим сохраненный язык в селект

                                // Ждем долю секунды, чтобы твой основной JS успел загрузиться, и кликаем по кнопке
                                setTimeout(() => {
                                    const btn = document.querySelector(`[data-lang='${savedLang}']`);
                                    if (btn) btn.click();
                                }, 50);
                            }
                        });
                    </script>
                </div>
                <a href="{{route('login')}}" class="btn-primary px-5 py-2 rounded-lg text-sm font-semibold text-white shadow-lg">
                    <span data-i18n="nav_start">Начать</span>
                </a>
                <button id="mobileMenuBtn" class="lg:hidden p-2 text-slate-300 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobileMenu" class="hidden lg:hidden glass-strong border-t border-slate-700/50">
        <div class="px-4 py-4 space-y-3">
            <a href="#features" class="block text-sm font-medium text-slate-300 hover:text-white py-2" data-i18n="nav_features">Возможности</a>
            <a href="#technology" class="block text-sm font-medium text-slate-300 hover:text-white py-2" data-i18n="nav_tech">Технологии</a>
            <a href="#security" class="block text-sm font-medium text-slate-300 hover:text-white py-2" data-i18n="nav_security">Безопасность</a>
            <a href="#ai" class="block text-sm font-medium text-slate-300 hover:text-white py-2" data-i18n="nav_ai">AI Анализ</a>
            <a href="#contact" class="block text-sm font-medium text-slate-300 hover:text-white py-2" data-i18n="nav_contact">Контакты</a>
            <div class="flex items-center gap-2 pt-3 border-t border-slate-700/50">
                <button class="lang-btn active px-3 py-1 rounded-md text-xs font-medium border border-transparent" data-lang="ru">RU</button>
                <button class="lang-btn px-3 py-1 rounded-md text-xs font-medium border border-transparent" data-lang="en">EN</button>
                <button class="lang-btn px-3 py-1 rounded-md text-xs font-medium border border-transparent" data-lang="tj">TJ</button>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
    <div class="orb w-[500px] h-[500px] bg-blue-600 top-1/4 -left-1/4 animate-float"></div>
    <div class="orb w-[400px] h-[400px] bg-cyan-500 bottom-1/4 -right-1/4 animate-float-delay"></div>
    <div class="orb w-[300px] h-[300px] bg-purple-600 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-20"></div>

    <div class="absolute bottom-0 left-0 right-0 h-96 bg-gradient-to-t from-blue-900/20 to-transparent"></div>
    <div class="absolute inset-0" style="background: radial-gradient(ellipse at 50% 0%, rgba(59, 130, 246, 0.15) 0%, transparent 60%);"></div>

    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="relative w-[600px] h-[600px] animate-spin-slow">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3 h-3 bg-blue-400 rounded-full shadow-lg shadow-blue-400/50"></div>
            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-cyan-400 rounded-full shadow-lg shadow-cyan-400/50"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-purple-400 rounded-full shadow-lg shadow-purple-400/50"></div>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 glass rounded-full px-5 py-2 mb-8 animate-slide-down">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <span class="text-xs font-medium text-slate-300" data-i18n="hero_badge">Система ЭДО нового поколения</span>
        </div>

        <h1 class="hero-title text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight mb-6 leading-tight">
            <span class="block text-white" data-i18n="hero_title_1">Электронный документооборот</span>
            <span class="block gradient-text" data-i18n="hero_title_2">в новом формате</span>
        </h1>

        <p class="max-w-2xl mx-auto text-lg sm:text-xl text-slate-400 mb-12 leading-relaxed animate-fade-in" style="animation-delay: 0.5s" data-i18n="hero_subtitle">
            DocSign — современная платформа для работы с документами. Подписывайте, анализируйте и храните файлы в один клик. Простое и надежное решение для каждого в Таджикистане.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 mb-16 animate-slide-up" style="animation-delay: 0.8s">
            <a href="https://t.me/share/url?url=https://your-site.tj&text=DocSign — Электронный документооборот нового поколения" target="_blank" class="btn-primary px-8 py-4 rounded-2xl text-base font-bold text-white shadow-2xl shadow-blue-500/25 flex items-center gap-3 w-full sm:w-auto justify-center bg-[#24A1DE]">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.944 0C5.346 0 0 5.346 0 11.944c0 6.597 5.346 11.944 11.944 11.944 6.598 0 11.944-5.347 11.944-11.944C23.888 5.346 18.542 0 11.944 0zM18.17 6.83l-2.113 9.968c-.15.66-.543.824-1.096.515l-3.218-2.373-1.553 1.493c-.17.172-.315.315-.646.315l.23-3.267 5.946-5.372c.258-.23-.056-.358-.401-.13l-7.35 4.628-3.166-1c-.687-.215-.702-.687.143-.1l12.355-4.76c.572-.215 1.07.127.91.892z"/>
                </svg>
                <span data-i18n="share_telegram">Поделиться</span>
            </a>

            <a href="https://api.whatsapp.com/send?text=Посмотри DocSign — Электронный документооборот: https://your-site.tj" target="_blank" class="px-8 py-4 rounded-2xl text-base font-bold text-white shadow-2xl shadow-green-500/40 flex items-center gap-3 w-full sm:w-auto justify-center transition-all hover:opacity-90 border-none" style="background-color: #25D366 !important;">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
                <span>WhatsApp</span>
            </a>

            <a href="https://instagram.com/ваш_логин" target="_blank" class="btn-outline px-8 py-4 rounded-2xl text-base font-bold text-slate-300 flex items-center gap-3 w-full sm:w-auto justify-center hover:text-white hover:bg-gradient-to-tr from-[#f9ce34] via-[#ee2a7b] to-[#6228d7] border-slate-700">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.805.249 2.227.412.558.217.957.477 1.377.896.42.419.68.818.896 1.377.163.422.358 1.057.412 2.227.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.249 1.805-.412 2.227-.217.558-.477.957-.896 1.377-.419.42-.818.68-1.377.896-.422.163-1.057.358-2.227.412-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.805-.249-2.227-.412-.558-.217-.957-.477-1.377-.896-.419-.42-.68-.818-.896-1.377-.163-.422-.358-1.057-.412-2.227-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.054-1.17.249-1.805.412-2.227.217-.558.477-.957.896-1.377.419-.42.818-.68 1.377-.896.422-.163 1.057-.358 2.227-.412 1.266-.058 1.646-.07 4.85-.07M12 0C8.741 0 8.333.014 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.014 8.333 0 8.741 0 12s.014 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126s1.355 1.078 2.126 1.384c.766.296 1.636.499 2.913.558C8.333 23.986 8.741 24 12 24s3.667-.014 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384s1.078-1.354 1.384-2.126c.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126s-1.354-1.078-2.126-1.384c-.765-.296-1.636-.499-2.913-.558C15.667.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                <span>Instagram</span>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto animate-fade-in" style="animation-delay: 1.2s">
            <div class="stat-card group rounded-2xl p-5 bg-white/5 border border-white/10 backdrop-blur-xl hover:bg-white/10 transition-all duration-300">
                <div class="text-3xl md:text-4xl font-black gradient-text"><span class="counter" data-target="60">0</span> <span data-i18n="stat_sec">сек</span></div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-blue-400 font-bold mb-1" data-i18n="stat_speed">Скорость</div>
                <div class="text-xs text-slate-300 leading-tight" data-i18n="stat_speed_desc">на оформление одного документа</div>
            </div>

            <div class="stat-card group rounded-2xl p-5 bg-white/5 border border-white/10 backdrop-blur-xl hover:bg-white/10 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">

                    <div class="p-1.5 bg-green-500/20 rounded-lg text-green-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-green-400 font-bold mb-1" data-i18n="stat_users">Пользователи</div>
                <div class="text-xs text-slate-300 leading-tight" data-i18n="stat_users_desc">активных пользователей по всей стране Таджикистан</div>
            </div>

            <div class="stat-card group rounded-2xl p-5 bg-white/5 border border-white/10 backdrop-blur-xl hover:bg-white/10 transition-all duration-300">
                <div class="flex justify-between items-start mb-2">
                    <div class="text-3xl md:text-4xl font-black gradient-text">24/7</div>
                    <div class="p-1.5 bg-purple-500/20 rounded-lg text-purple-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-purple-400 font-bold mb-1" data-i18n="stat_access">ДАСТРАСӢ</div>
                <div class="text-xs text-slate-300 leading-tight" data-i18n="stat_access_desc">имзогузорӣ дар тамоми ҳудуди Тоҷикистон</div>
            </div>

            <div class="stat-card group rounded-2xl p-5 bg-white/5 border border-white/10 backdrop-blur-xl hover:bg-white/10 transition-all duration-300">
                <div class="text-3xl md:text-4xl font-black gradient-text"><span class="counter" data-target="100">0</span>%</div>
                <div class="text-[10px] uppercase tracking-[0.2em] text-amber-400 font-bold mb-1" data-i18n="stat_protection">Защита</div>
                <div class="text-xs text-slate-300 leading-tight" data-i18n="stat_protection_desc">юридическая сила документов</div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce-gentle">
        <div class="w-6 h-10 rounded-full border-2 border-slate-500/50 flex justify-center pt-2">
            <div class="w-1 h-3 bg-blue-400 rounded-full animate-pulse"></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-950/20 to-transparent"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20 scroll-reveal">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-400 uppercase tracking-widest mb-4">
                <span class="w-8 h-px bg-blue-400"></span>
                <span data-i18n="features_label">Возможности</span>
                <span class="w-8 h-px bg-blue-400"></span>
            </span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-i18n="features_title">Всё. Что вам нужно.</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-400" data-i18n="features_subtitle">Каждая деталь спроектирована для максимальной эффективности вашего документооборота</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 bg-blue-500/10">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 3.364l.5 1m-3.044 1.133l.5 1M21 12h-1m-3.322.322l-1 .5m-1.133 3.044l-1 .5M12 20v-1m3.322-.133l-.5-1m3.044-1.133l-.5-1M3 12h1m1.133-3.322l1-.5m1.133-3.044l1-.5M9 9h6v6H9V9z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="qr_title">Электронная подпись</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="qr_desc">Система генерирует уникальный QR-код для каждого документа. Любой проверяющий может мгновенно подтвердить легитимность файла через камеру смартфона.</p>
                <div class="code-block rounded-xl p-4 text-xs font-mono bg-black/30 border border-white/5">
                    <span class="text-purple-400">$pdf</span><span class="text-slate-500">-></span><span class="text-blue-400">addQRCode</span><span class="text-slate-500">(</span><span class="text-green-400">'https://docsign.tj/verify/ID'</span><span class="text-slate-500">)</span>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group" style="transition-delay: 0.1s">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="f2_title">Управление ролями</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="f2_desc">Гибкая система аутентификации и авторизации с разделением ролей. Администраторы, менеджеры, пользователи — полный контроль доступа.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-500/10 text-red-400 border border-red-500/20">Admin</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-purple-500/10 text-purple-400 border border-purple-500/20">Director</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">Employee</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-500/10 text-slate-400 border border-slate-500/20">User</span>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group" style="transition-delay: 0.2s">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 bg-purple-500/10">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="f3_title">Архитектура БД</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="f3_desc">Оптимизированная MySQL структура с поддержкой целостности данных. Полная история действий и строгая привязка подписей к документам.</p>
                <div class="code-block rounded-xl p-4 text-[11px] font-mono space-y-2 bg-black/30 border border-white/5">
                    <div class="flex items-center gap-2"><span class="text-purple-400">Users</span><span class="text-slate-600">──(hasMany)──▶</span><span class="text-blue-400">Documents</span></div>
                    <div class="flex items-center gap-2"><span class="text-purple-400">Docs</span><span class="text-slate-600">──(hasMany)──▶</span><span class="text-blue-400">Audit_Logs</span></div>
                    <div class="flex items-center gap-2"><span class="text-purple-400">Sign</span><span class="text-slate-600">──(belongsTo)─▶</span><span class="text-blue-400">File_Meta</span></div>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="f4_title">Intelligent Analysis</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="f4_desc">Интеллектуальные алгоритмы анализируют данные, автоматизируют рутинные процессы и выводят информацию в удобном визуальном виде.</p>
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-2 bg-slate-700 rounded-full overflow-hidden"><div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full" style="width: 94%"></div></div>
                </div>
                <div class="text-xs text-slate-500 mt-1" data-i18n="f4_accuracy">Точность анализа</div>
            </div>

            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group" style="transition-delay: 0.1s">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="f5_title">Управление файлами</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="f5_desc">Логика загрузки, хранения и вывода документов. Поддержка облачного хранения с перспективой масштабирования.</p>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>PDF, DOCX</span>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-3xl p-8 card-hover group" style="transition-delay: 0.2s">
                <div class="feature-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-3" data-i18n="f6_title">Perfect Dark Mode</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-4" data-i18n="f6_desc">Глубокая настройка тёмной темы — таблицы, модальные окна и шрифты меняют цвета так, чтобы глаза не уставали.</p>
                <div class="flex flex-wrap items-center gap-3 p-4 bg-black/40 rounded-2xl border border-white/10 backdrop-blur-md">
                    <div class="w-9 h-9 rounded-xl bg-slate-950 border border-slate-800 shadow-xl"></div>
                    <div class="w-9 h-9 rounded-xl bg-blue-500 border border-blue-400/50 shadow-[0_0_15px_rgba(59,130,246,0.5)]"></div>
                    <div class="w-9 h-9 rounded-xl bg-cyan-400 border border-cyan-300/50 shadow-[0_0_15px_rgba(34,211,238,0.5)]"></div>
                    <div class="w-9 h-9 rounded-xl bg-red-500 border border-red-400/50 shadow-[0_0_15px_rgba(239,68,68,0.5)]"></div>
                    <div class="w-9 h-9 rounded-xl bg-orange-500 border border-orange-400/50 shadow-[0_0_15px_rgba(249,115,22,0.5)]"></div>
                    <div class="w-9 h-9 rounded-xl bg-yellow-400 border border-yellow-300/50 shadow-[0_0_15px_rgba(250,204,21,0.5)]"></div>
                </div>
                <div class="text-xs text-slate-500 mt-1" data-i18n="f6_palette">Цветовая палитра</div>
            </div>
        </div>
    </div>
</section>

<!-- Technology Section -->
<section id="technology" class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-950/10 to-transparent"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20 scroll-reveal">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-cyan-400 uppercase tracking-widest mb-4">
                <span class="w-8 h-px bg-cyan-400"></span>
                <span data-i18n="tech_label">Технологии</span>
                <span class="w-8 h-px bg-cyan-400"></span>
            </span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-i18n="tech_title">Системы</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-400" data-i18n="tech_subtitle">Мощный стек технологий, обеспечивающий скорость, надёжность и масштабируемость</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="scroll-reveal">
                <div class="code-block rounded-2xl overflow-hidden shadow-2xl shadow-blue-500/10">
                    <div class="flex items-center gap-2 px-4 py-3 bg-slate-800/50 border-b border-slate-700/50">
                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <span class="ml-3 text-xs text-slate-500 font-mono">DocSign Architecture</span>
                    </div>
                    <div class="p-6 font-mono text-sm space-y-2 overflow-x-auto">
                        <div class="text-slate-500">// 🛠 Технологический стек</div>
                        <div><span class="text-purple-400">framework</span><span class="text-slate-500">:</span> <span class="text-green-400">'Laravel 13 (PHP)'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">database</span><span class="text-slate-500">:</span> <span class="text-green-400">'MySQL 8.4'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">frontend</span><span class="text-slate-500">:</span> <span class="text-green-400">'TailwindCSS + Bootstrap'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">pdf_engine</span><span class="text-slate-500">:</span> <span class="text-green-400">'FPDI / TCPDF'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">auth</span><span class="text-slate-500">:</span> <span class="text-green-400">'Laravel Breeze + Roles'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">ai_module</span><span class="text-slate-500">:</span> <span class="text-green-400">'Intelligent Analysis'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">region</span><span class="text-slate-500">:</span> <span class="text-green-400">'🇹🇯 Таджикистан'</span><span class="text-slate-500">,</span></div>
                        <div><span class="text-purple-400">languages</span><span class="text-slate-500">:</span> <span class="text-slate-500">[</span><span class="text-green-400">'RU'</span><span class="text-slate-500">,</span> <span class="text-green-400">'EN'</span><span class="text-slate-500">,</span> <span class="text-green-400">'TJ'</span><span class="text-slate-500">]</span></div>
                        <div class="pt-4 text-slate-600"><span class="text-green-500">✓</span> <span data-i18n="tech_status">Все системы работают штатно</span></div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="scroll-reveal glass rounded-2xl p-6 card-hover flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 border border-purple-500/30 flex items-center justify-center flex-shrink-0"><span class="text-2xl">🐘</span></div>
                    <div>
                        <h4 class="text-lg font-bold text-white mb-1">Laravel 13</h4>
                        <p class="text-sm text-slate-400" data-i18n="tech_laravel">Последняя версия PHP-фреймворка. Высокая скорость, безопасность и элегантный синтаксис.</p>
                    </div>
                </div>

                <div class="scroll-reveal glass rounded-2xl p-6 card-hover flex items-start gap-4" style="transition-delay: 0.1s">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 border border-blue-500/30 flex items-center justify-center flex-shrink-0"><span class="text-2xl">🗄️</span></div>
                    <div>
                        <h4 class="text-lg font-bold text-white mb-1">MySQL 8.4</h4>
                        <p class="text-sm text-slate-400" data-i18n="tech_mysql">Надёжная СУБД с foreign keys, индексами и оптимизированными запросами для больших данных.</p>
                    </div>
                </div>

                <div class="scroll-reveal glass rounded-2xl p-6 card-hover flex items-start gap-4" style="transition-delay: 0.2s">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/20 to-cyan-600/20 border border-cyan-500/30 flex items-center justify-center flex-shrink-0"><span class="text-2xl">🎨</span></div>
                    <div>
                        <h4 class="text-lg font-bold text-white mb-1">TailwindCSS + Bootstrap</h4>
                        <p class="text-sm text-slate-400" data-i18n="tech_ui">Гибридный дизайн: мощь Tailwind для верстки и компоненты Bootstrap для быстрой разработки.</p>
                    </div>
                </div>

                <div class="scroll-reveal glass group rounded-2xl p-6 border border-white/5 hover:border-orange-500/30 transition-all duration-500" style="transition-delay: 0.3s">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300">📑</div>
                        <div>
                            <h4 class="text-lg font-bold text-white group-hover:text-orange-400 transition-colors" data-i18n="tech_engine_title">Универсальный Engine</h4>
                            <p class="text-sm text-slate-400 leading-relaxed" data-i18n="tech_engine_desc">Полная поддержка PDF, Word и Excel. Система интеллектуально интегрирует подписи и печати в структуру любого офисного документа, сохраняя его исходное форматирование.</p>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4 ml-[76px]">
                        <span class="text-[10px] px-2 py-0.5 rounded bg-red-500/10 text-red-400 border border-red-500/20 font-bold">PDF</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 font-bold">DOCX</span>
                        <span class="text-[10px] px-2 py-0.5 rounded bg-green-500/10 text-green-400 border border-green-500/20 font-bold">XLSX</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Security Section -->
<section id="security" class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-purple-950/10 to-transparent"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20 scroll-reveal">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-green-400 uppercase tracking-widest mb-4">
                <span class="w-8 h-px bg-green-400"></span>
                <span data-i18n="security_label">Безопасность</span>
                <span class="w-8 h-px bg-green-400"></span>
            </span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-i18n="security_title">Абсолютная защита</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-400" data-i18n="security_subtitle">Многоуровневая система безопасности для защиты ваших документов и данных</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="scroll-reveal glass rounded-2xl p-6 card-hover text-center border border-white/5">
                <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 border border-blue-500/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <span class="absolute -top-1 -right-1 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                </div>
                <h4 class="text-lg font-bold text-white mb-1" data-i18n="ver_title">Версия 2.5.0-stable</h4>
                <div class="inline-block px-3 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-[10px] text-blue-400 font-bold uppercase tracking-wider mb-3">Latest Release</div>
                <p class="text-sm text-slate-400 leading-relaxed" data-i18n="ver_desc">Регулярные обновления безопасности и поддержка новых форматов документов.</p>
            </div>

            <div class="scroll-reveal glass rounded-2xl p-6 card-hover text-center border border-white/5" style="transition-delay: 0.1s">
                <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500/20 to-indigo-500/20 border border-blue-500/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute top-4 right-4 flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border border-slate-900"></span>
                    </span>
                </div>
                <h4 class="text-lg font-bold text-white mb-2" data-i18n="sec2_title">Smart Notify</h4>
                <p class="text-sm text-slate-400 leading-relaxed" data-i18n="sec2_desc">Мгновенные оповещения о статусе документов. Контролируйте процесс в реальном времени.</p>
                <div class="flex justify-center gap-3 mt-4">
                    <span class="text-[10px] font-bold text-blue-400 opacity-60">EMAIL</span>
                    <span class="text-[10px] font-bold text-slate-600">•</span>
                    <span class="text-[10px] font-bold text-blue-400 opacity-60">SYSTEM</span>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-2xl p-6 card-hover text-center border border-white/5" style="transition-delay: 0.2s">
                <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-white mb-2" data-i18n="sec3_title">Audit Logs</h4>
                <p class="text-sm text-slate-400 leading-relaxed" data-i18n="sec3_desc">Детальная история всех операций. Каждый шаг под строгим учетом.</p>
                <div class="mt-4 space-y-1.5 text-left bg-black/20 p-3 rounded-xl border border-white/5">
                    <div class="flex items-center gap-2 text-[9px] font-mono text-green-400/80"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span><span>SIGNED: Admin</span></div>
                    <div class="flex items-center gap-2 text-[9px] font-mono text-blue-400/80"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span><span>VIEWED: Director</span></div>
                </div>
            </div>

            <div class="scroll-reveal glass rounded-2xl p-6 card-hover text-center border border-white/5" style="transition-delay: 0.3s">
                <div class="relative w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-500/20 border border-amber-500/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h4 class="text-lg font-bold text-white mb-2" data-i18n="sec4_title">Drafts</h4>
                <p class="text-sm text-slate-400 leading-relaxed" data-i18n="sec4_desc">Сохраняйте прогресс в один клик. Система запомнит все внесенные данные.</p>
                <div class="mt-4 flex items-center justify-center gap-2 py-2 px-3 bg-amber-500/10 rounded-xl border border-amber-500/20">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] font-bold text-amber-400 uppercase tracking-widest">Saved</span>
                </div>
            </div>
        </div>

        <div class="scroll-reveal mt-16 glass-strong rounded-3xl p-8 md:p-12">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-4" data-i18n="security_detail_title">Защита на каждом уровне</h3>
                    <p class="text-slate-400 mb-6" data-i18n="security_detail_desc">Защищённая система аутентификации и авторизации с разделением ролей. Каждый документ проходит многоуровневую проверку.</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                            <span class="text-sm text-slate-300" data-i18n="sec_check1">CSRF защита на всех формах</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                            <span class="text-sm text-slate-300" data-i18n="sec_check2">XSS и SQL Injection защита</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                            <span class="text-sm text-slate-300" data-i18n="sec_check3">Rate limiting и брутфорс защита</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0"><svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg></div>
                            <span class="text-sm text-slate-300" data-i18n="sec_check4">Резервное копирование данных</span>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="code-block rounded-2xl p-6 text-sm font-mono">
                        <div class="text-slate-500 mb-2">// 🔐 Security Middleware</div>
                        <div><span class="text-purple-400">class</span> <span class="text-cyan-400">SecurityMiddleware</span></div>
                        <div><span class="text-slate-500">{</span></div>
                        <div class="pl-4"><span class="text-purple-400">public function</span> <span class="text-blue-400">handle</span><span class="text-slate-500">(</span><span class="text-orange-400">$request</span><span class="text-slate-500">,</span> <span class="text-orange-400">$next</span><span class="text-slate-500">)</span></div>
                        <div class="pl-4"><span class="text-slate-500">{</span></div>
                        <div class="pl-8"><span class="text-green-400">// Verify CSRF token</span></div>
                        <div class="pl-8"><span class="text-green-400">// Check rate limits</span></div>
                        <div class="pl-8"><span class="text-green-400">// Validate roles</span></div>
                        <div class="pl-8"><span class="text-purple-400">return</span> <span class="text-orange-400">$next</span><span class="text-slate-500">(</span><span class="text-orange-400">$request</span><span class="text-slate-500">);</span></div>
                        <div class="pl-4"><span class="text-slate-500">}</span></div>
                        <div><span class="text-slate-500">}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- AI Section -->
<section id="ai" class="relative py-16 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-amber-950/10 to-transparent"></div>
    <div class="orb w-[400px] h-[400px] bg-amber-600/20 top-0 right-0 blur-[120px]"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-10 scroll-reveal">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 uppercase tracking-widest mb-4">
                <span class="w-8 h-px bg-amber-400"></span>
                <span data-i18n="ai_label">Анализ</span>
                <span class="w-8 h-px bg-amber-400"></span>
            </span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-i18n="ai_title">Intelligent Analysis</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-400" data-i18n="ai_subtitle">AI помогает анализировать данные, автоматизировать процессы и выводить информацию в удобном виде</p>
        </div>

        <div class="acard">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="slabel" data-i18n="baseActivity">Активность базы</div>
                    <h6 class="mb-0 fw-bold" style="color:var(--text)" data-i18n="audienceDynamics">Динамика прироста аудитории</h6>
                </div>
                <div class="d-flex gap-3">
                    <small class="d-flex align-items-center gap-1" style="color:#3b82f6">
                        <span class="legend-dot" style="background:#3b82f6"></span>
                        <span data-i18n="registrations">Регистрации</span>
                    </small>
                    <small class="d-flex align-items-center gap-1" style="color:#ef4444">
                        <span class="legend-dot" style="background:#ef4444"></span>
                        <span data-i18n="deletions">Удаления</span>
                    </small>
                </div>
            </div>
            <div id="userChart"></div>
        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-4">
            <div class="flex-1">
                <div class="acard h-100">
                    <div class="slabel" data-i18n="usersCount">Пользователи</div>
                    <div class="bignum mt-2">29</div>
                    <small class="text-slate-400" data-i18n="activeProfiles">активных профилей</small>
                </div>
            </div>

            <div class="flex-1">
                <div class="acard h-100">
                    <div class="slabel" data-i18n="new30Days">Новые (30 дней)</div>
                    <div class="bignum mt-2 text-emerald-400">29</div>
                    <div class="progbg" style="background: rgba(255,255,255,0.05); height: 4px; border-radius: 2px; margin-top: 8px; overflow: hidden;">
                        <div class="fill" style="width:100%; background:#10b981; height: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="flex-1">
                <div class="acard h-100" style="border-top:3px solid #ef4444">
                    <div class="slabel text-red-400" data-i18n="churnRate">Churn Rate</div>
                    <div class="bignum mt-2 text-red-400">27.6%</div>
                    <small class="text-slate-400" data-i18n="churnDesc">коэффициент оттока</small>
                </div>
            </div>
        </div>

    </div>

</section>

<!-- Contact / CTA Section -->
<section id="contact" class="relative py-32 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-950/20 to-transparent"></div>
    <div class="orb w-[500px] h-[500px] bg-blue-600/20 bottom-0 left-1/2 -translate-x-1/2 blur-[120px]"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 scroll-reveal">
            <span class="inline-flex items-center gap-2 text-xs font-semibold text-blue-400 uppercase tracking-widest mb-4">
                <span class="w-8 h-px bg-blue-400"></span>
                <span data-i18n="contact_label">Начать</span>
                <span class="w-8 h-px bg-blue-400"></span>
            </span>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-6" data-i18n="contact_title">Готовы начать?</h2>
            <p class="max-w-2xl mx-auto text-lg text-slate-400" data-i18n="contact_subtitle">Присоединяйтесь к тысячам компаний, которые уже используют DocSign</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="relative border-t border-slate-800/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-2">
                <a href="#" class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white">Doc<span class="gradient-text">Sign</span></span>
                </a>
                <p class="text-sm text-slate-400 max-w-sm" data-i18n="footer_desc">Интеллектуальная платформа электронного документооборота для бизнеса Таджикистана.</p>
                <div class="flex items-center gap-3 mt-4">
                    <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 2.614.102.781.209 1.494.328 2.037.328.544 0 1.256-.117 2.037-.328 1.606-.424 2.614-.102 2.614-.102.652 1.652.241 2.873.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-xl glass flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white mb-4" data-i18n="footer_product">Продукт</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#features" class="hover:text-blue-400 transition-colors" data-i18n="footer_features">Возможности</a></li>
                    <li><a href="#security" class="hover:text-blue-400 transition-colors" data-i18n="footer_security">Безопасность</a></li>
                    <li><a href="#ai" class="hover:text-blue-400 transition-colors" data-i18n="footer_ai">AI Анализ</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors" data-i18n="footer_pricing">Цены</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-white mb-4" data-i18n="footer_company">Компания</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-blue-400 transition-colors" data-i18n="footer_about">О нас</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors" data-i18n="footer_careers">Карьера</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition-colors" data-i18n="footer_blog">Блог</a></li>
                    <li><a href="#contact" class="hover:text-blue-400 transition-colors" data-i18n="footer_contact">Контакты</a></li>
                </ul>
            </div>
        </div>
        <div class="line-glow mb-8"></div>
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <span>© 2026 DocSign. <span data-i18n="footer_rights">Все права защищены.</span></span>
            <span class="flex items-center gap-2">
                <span class="text-base">🇹🇯</span>
                <span data-i18n="footer_made">Сделано в Таджикистане</span>
            </span>
        </div>
    </div>
</footer>

<!-- Back to Top -->
<button id="backToTop" class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-xl glass flex items-center justify-center text-slate-400 hover:text-white hover:border-blue-500/30 transition-all opacity-0 translate-y-4 pointer-events-none" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
</button>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Translations
    const translations = {
        ru: {
            nav_features: "Возможности",
            nav_tech: "Технологии",
            nav_security: "Безопасность",
            nav_ai: "Анализ",
            nav_contact: "Контакты",
            nav_start: "Начать",
            hero_badge: "Система ЭДО нового поколения",
            hero_title_1: "Электронный документооборот",
            hero_title_2: "в новом формате",
            hero_subtitle: "DocSign — современная платформа для работы с документами. Подписывайте, анализируйте и храните файлы в один клик. Простое и надежное решение для каждого в Таджикистане.",
            share_telegram: "Поделиться",
            stat_sec: "сек",
            stat_speed: "Скорость",
            stat_speed_desc: "на оформление одного документа",
            stat_users: "Пользователи",
            stat_users_desc: "активных пользователей по всему Таджикистану",
            stat_access: "ДАСТРАСӢ",
            stat_access_desc: "имзогузорӣ дар тамоми ҳудуди Тоҷикистон",
            stat_protection: "Защита",
            stat_protection_desc: "юридическая сила документов",
            features_label: "Возможности",
            features_title: "Всё. Что вам нужно.",
            features_subtitle: "Каждая деталь спроектирована для максимальной эффективности вашего документооборота",
            qr_title: "Электронная подпись",
            qr_desc: "Система генерирует уникальный QR-код для каждого документа. Любой проверяющий может мгновенно подтвердить легитимность файла через камеру смартфона.",
            f2_title: "Управление ролями",
            f2_desc: "Гибкая система аутентификации и авторизации с разделением ролей. Администраторы, менеджеры, пользователи — полный контроль доступа.",
            f3_title: "Архитектура БД",
            f3_desc: "Оптимизированная MySQL структура с поддержкой целостности данных. Полная история действий и строгая привязка подписей к документам.",
            f4_title: "Intelligent Analysis",
            f4_desc: "Интеллектуальные алгоритмы анализируют данные, автоматизируют рутинные процессы и выводят информацию в удобном визуальном виде.",
            f4_accuracy: "Точность анализа",
            f5_title: "Управление файлами",
            f5_desc: "Логика загрузки, хранения и вывода документов. Поддержка облачного хранения с перспективой масштабирования.",
            f6_title: "Perfect Dark Mode",
            f6_desc: "Глубокая настройка тёмной темы — таблицы, модальные окна и шрифты меняют цвета так, чтобы глаза не уставали.",
            f6_palette: "Цветовая палитра",
            tech_label: "Технологии",
            tech_title: "Системы",
            tech_subtitle: "Мощный стек технологий, обеспечивающий скорость, надёжность и масштабируемость",
            tech_status: "Все системы работают штатно",
            tech_laravel: "Последняя версия PHP-фреймворка. Высокая скорость, безопасность и элегантный синтаксис.",
            tech_mysql: "Надёжная СУБД с foreign keys, индексами и оптимизированными запросами для больших данных.",
            tech_ui: "Гибридный дизайн: мощь Tailwind для верстки и компоненты Bootstrap для быстрой разработки.",
            tech_engine_title: "Универсальный Engine",
            tech_engine_desc: "Полная поддержка PDF, Word и Excel. Система интеллектуально интегрирует подписи и печати в структуру любого офисного документа, сохраняя его исходное форматирование.",
            security_label: "Безопасность",
            security_title: "Абсолютная защита",
            security_subtitle: "Многоуровневая система безопасности для защиты ваших документов и данных",
            ver_title: "Версия 2.5.0-stable",
            ver_desc: "Регулярные обновления безопасности и поддержка новых форматов документов.",
            sec2_title: "Smart Notify",
            sec2_desc: "Мгновенные оповещения о статусе документов. Контролируйте процесс в реальном времени.",
            sec3_title: "Audit Logs",
            sec3_desc: "Детальная история всех операций. Каждый шаг под строгим учетом.",
            sec4_title: "Drafts",
            sec4_desc: "Сохраняйте прогресс в один клик. Система запомнит все внесенные данные.",
            security_detail_title: "Защита на каждом уровне",
            security_detail_desc: "Защищённая система аутентификации и авторизации с разделением ролей. Каждый документ проходит многоуровневую проверку.",
            sec_check1: "CSRF защита на всех формах",
            sec_check2: "XSS и SQL Injection защита",
            sec_check3: "Rate limiting и брутфорс защита",
            sec_check4: "Резервное копирование данных",
            ai_label: "Анализ",
            ai_title: "Intelligent Analysis",
            ai_subtitle: "Помогает анализировать данные, автоматизировать процессы и выводить информацию в удобном виде",
            userAnalytics: "Аналитика пользователей",
            baseActivity: "Активность базы",
            audienceDynamics: "Динамика прироста аудитории",
            registrations: "Регистрации",
            deletions: "Удаления",
            usersCount: "Пользователи",
            activeProfiles: "активных профилей",
            new30Days: "Новые (30 дней)",
            churnRate: "Churn Rate",
            churnDesc: "коэффициент оттока",
            contact_label: "Начать",
            contact_title: "Готовы начать?",
            contact_subtitle: "Присоединяйтесь к тысячам компаний, которые уже используют DocSign",
            footer_desc: "Интеллектуальная платформа электронного документооборота для бизнеса Таджикистана.",
            footer_product: "Продукт",
            footer_features: "Возможности",
            footer_security: "Безопасность",
            footer_ai: "Анализ",
            footer_pricing: "Цены",
            footer_company: "Компания",
            footer_about: "О нас",
            footer_careers: "Карьера",
            footer_blog: "Блог",
            footer_contact: "Контакты",
            footer_rights: "Все права защищены.",
            footer_made: "Сделано в Таджикистане"
        },
        en: {
            nav_features: "Features",
            nav_tech: "Technology",
            nav_security: "Security",
            nav_ai: "Analysis",
            nav_contact: "Contact",
            nav_start: "Get Started",
            hero_badge: "Next-generation EDMS",
            hero_title_1: "Electronic Document Management",
            hero_title_2: "in a new format",
            hero_subtitle: "DocSign — a modern platform for working with documents. Sign, analyze and store files in one click. A simple and reliable solution for everyone in Tajikistan.",
            share_telegram: "Share",
            stat_sec: "sec",
            stat_speed: "Speed",
            stat_speed_desc: "to process one document",
            stat_users: "Users",
            stat_users_desc: "active users throughout Tajikistan",
            stat_access: "ACCESS",
            stat_access_desc: "signing across the entire territory of Tajikistan",
            stat_protection: "Protection",
            stat_protection_desc: "legal validity of documents",
            features_label: "Features",
            features_title: "Everything. You need.",
            features_subtitle: "Every detail is designed for maximum efficiency of your document workflow",
            qr_title: "Digital Signature",
            qr_desc: "The system generates a unique QR code for each document. Any verifier can instantly confirm the legitimacy of the file through a smartphone camera.",
            f2_title: "Role Management",
            f2_desc: "Flexible authentication and authorization system with role separation. Administrators, managers, users — full access control.",
            f3_title: "Database Architecture",
            f3_desc: "Optimized MySQL structure with data integrity support. Complete action history and strict binding of signatures to documents.",
            f4_title: "Intelligent Analysis",
            f4_desc: "Intelligent algorithms analyze data, automate routine processes and display information in a convenient visual format.",
            f4_accuracy: "Analysis accuracy",
            f5_title: "File Management",
            f5_desc: "Document upload, storage and display logic. Cloud storage support with scalability prospects.",
            f6_title: "Perfect Dark Mode",
            f6_desc: "Deep dark theme configuration — tables, modals and fonts change colors so your eyes don't get tired.",
            f6_palette: "Color palette",
            tech_label: "Technology",
            tech_title: "Systems",
            tech_subtitle: "A powerful technology stack ensuring speed, reliability and scalability",
            tech_status: "All systems operating normally",
            tech_laravel: "Latest PHP framework version. High speed, security and elegant syntax.",
            tech_mysql: "Reliable DBMS with foreign keys, indexes and optimized queries for big data.",
            tech_ui: "Hybrid design: Tailwind power for layout and Bootstrap components for rapid development.",
            tech_engine_title: "Universal Engine",
            tech_engine_desc: "Full support for PDF, Word and Excel. The system intelligently integrates signatures and stamps into the structure of any office document, preserving its original formatting.",
            security_label: "Security",
            security_title: "Absolute Protection",
            security_subtitle: "Multi-level security system to protect your documents and data",
            ver_title: "Version 2.5.0-stable",
            ver_desc: "Regular security updates and support for new document formats.",
            sec2_title: "Smart Notify",
            sec2_desc: "Instant notifications about document status. Monitor the process in real time.",
            sec3_title: "Audit Logs",
            sec3_desc: "Detailed history of all operations. Every step under strict accounting.",
            sec4_title: "Drafts",
            sec4_desc: "Save progress in one click. The system will remember all entered data.",
            security_detail_title: "Protection at Every Level",
            security_detail_desc: "Protected authentication and authorization system with role separation. Every document passes multi-level verification.",
            sec_check1: "CSRF protection on all forms",
            sec_check2: "XSS and SQL Injection protection",
            sec_check3: "Rate limiting and brute force protection",
            sec_check4: "Data backup",
            ai_label: "Analysis",
            ai_title: "Intelligent Analysis",
            ai_subtitle: "Helps analyze data, automate processes and display information conveniently",
            userAnalytics: "User Analytics",
            baseActivity: "Base Activity",
            audienceDynamics: "Audience Growth Dynamics",
            registrations: "Registrations",
            deletions: "Deletions",
            usersCount: "Users",
            activeProfiles: "active profiles",
            new30Days: "New (30 days)",
            churnRate: "Churn Rate",
            churnDesc: "attrition rate",
            contact_label: "Get Started",
            contact_title: "Ready to Start?",
            contact_subtitle: "Join thousands of companies already using DocSign",
            footer_desc: "Intelligent electronic document management platform for Tajikistan businesses.",
            footer_product: "Product",
            footer_features: "Features",
            footer_security: "Security",
            footer_ai: "Analysis",
            footer_pricing: "Pricing",
            footer_company: "Company",
            footer_about: "About Us",
            footer_careers: "Careers",
            footer_blog: "Blog",
            footer_contact: "Contact",
            footer_rights: "All rights reserved.",
            footer_made: "Made in Tajikistan"
        },
        tj: {
            nav_features: "Имкониятҳо",
            nav_tech: "Технологияҳо",
            nav_security: "Амният",
            nav_ai: "Таҳлили",
            nav_contact: "Тамос",
            nav_start: "Оғоз",
            hero_badge: "Системаи ЭДО-и насли нав",
            hero_title_1: "Идоракунии электронии ҳуҷҷатҳо",
            hero_title_2: "дар формати нав",
            hero_subtitle: "DocSign — платформаи муосир барои кор бо ҳуҷҷатҳо. Имзо кунед, таҳлил кунед ва файлҳоро дар як клик нигоҳ доред. Ҳалли содда ва боэътимод барои ҳар як шахс дар Тоҷикистон.",
            share_telegram: "Мубодила",
            stat_sec: "сония",
            stat_speed: "Суръат",
            stat_speed_desc: "барои расмиёти як ҳуҷҷат",
            stat_users: "Корбарон",
            stat_users_desc: "корбарони фаъол дар тамоми Тоҷикистон",
            stat_access: "ДАСТРАСӢ",
            stat_access_desc: "имзогузорӣ дар тамоми ҳудуди Тоҷикистон",
            stat_protection: "Ҳимоя",
            stat_protection_desc: "қувваи ҳуқуқии ҳуҷҷатҳо",
            features_label: "Имкониятҳо",
            features_title: "Ҳама. Чӣ ки лозим.",
            features_subtitle: "Ҳар як тафсилот барои самаранокии максималии ҳуҷҷатгузории шумо тарҳрезӣ шудааст",
            qr_title: "Имзои электронӣ",
            qr_desc: "Система барои ҳар як ҳуҷҷат коди беназири QR истеҳсол мекунад. Ҳар як санҷанда метавонад қонунияти файлро фавран тавассути камераи смартфон тасдиқ кунад.",
            f2_title: "Идоракунии нақшҳо",
            f2_desc: "Системаи чандири аутентификатсия ва авторизатсия бо ҷудокунии нақшҳо. Администраторҳо, менеджерҳо, корбарон — назорати пурраи дастрасӣ.",
            f3_title: "Архитектураи БД",
            f3_desc: "Сохтори оптимизатсияшудаи MySQL бо дастгирии якпорчагии маълумот. Таърихи пурраи амалҳо ва вобастагии қатъии имзоҳо ба ҳуҷҷатҳо.",
            f4_title: "Таҳлили Intelligent",
            f4_desc: "Алгоритмҳои интеллектуалӣ маълумотро таҳлил мекунанд, равандҳои рутиниро автоматизатсия мекунанд ва маълумотро дар шакли визуалии қулай нишон медиҳанд.",
            f4_accuracy: "Дақиқии таҳлил",
            f5_title: "Идоракунии файлҳо",
            f5_desc: "Мантиқи боркунӣ, нигоҳдорӣ ва намоиши ҳуҷҷатҳо. Дастгирии нигоҳдории абрӣ бо имконияти миқёсгузорӣ.",
            f6_title: "Ҳолати торик",
            f6_desc: "Танзими амиқи мавзӯи торик — ҷадвалҳо, тирезаҳои модалӣ ва шрифтҳо рангҳоро иваз мекунанд, то чашмон хаста нашаванд.",
            f6_palette: "Палитраи рангҳо",
            tech_label: "Технологияҳо",
            tech_title: "Системаҳо",
            tech_subtitle: "Стеки пурқуввати технологияҳо, ки суръат, эътимоднокӣ ва миқёсгузориро таъмин мекунад",
            tech_status: "Ҳамаи системаҳо ба таври оддӣ кор мекунанд",
            tech_laravel: "Охирин версияи чаҳорчӯбаи PHP. Суръати баланд, амният ва синтаксиси элегантӣ.",
            tech_mysql: "СУБД-и боэътимод бо foreign keys, индексҳо ва дархостҳои оптимизатсияшуда барои маълумоти калон.",
            tech_ui: "Тарҳи гибридӣ: қудрати Tailwind барои верстка ва компонентҳои Bootstrap барои рушди тез.",
            tech_engine_title: "Муҳаррики универсалӣ",
            tech_engine_desc: "Дастгирии пурраи PDF, Word ва Excel. Система имзоҳо ва муҳрҳоро ба сохтори ҳар як ҳуҷҷати офисӣ ҳушмандона ворид мекунад, формати аслии онро нигоҳ медорад.",
            security_label: "Амният",
            security_title: "Ҳимояи мутлақ",
            security_subtitle: "Системаи амнияти бисёрсатҳа барои ҳифзи ҳуҷҷатҳо ва маълумоти шумо",
            ver_title: "Версия 2.5.0-stable",
            ver_desc: "Навсозиҳои мунтазами амният ва дастгирии форматҳои нави ҳуҷҷатҳо.",
            sec2_title: "Smart Notify",
            sec2_desc: "Огоҳиҳои фаврӣ дар бораи вазъияти ҳуҷҷатҳо. Равандро дар вақти воқеӣ назорат кунед.",
            sec3_title: "Audit Logs",
            sec3_desc: "Таърихи муфассали ҳамаи амалиётҳо. Ҳар як қадам зери назорати қатъӣ.",
            sec4_title: "Пешнависҳо",
            sec4_desc: "Пешрафтро дар як клик захира кунед. Система ҳамаи маълумоти воридшударо ба ёд меорад.",
            security_detail_title: "Ҳимоя дар ҳар сатҳ",
            security_detail_desc: "Системаи ҳифзшудаи аутентификатсия ва авторизатсия бо ҷудокунии нақшҳо. Ҳар як ҳуҷҷат аз санҷиши бисёрсатҳа мегузарад.",
            sec_check1: "Ҳимояи CSRF дар ҳамаи формаҳо",
            sec_check2: "Ҳимояи XSS ва SQL Injection",
            sec_check3: "Rate limiting ва ҳимояи brute force",
            sec_check4: "Нусхабардории маълумотҳо",
            ai_label: "Таҳлил",
            ai_title: "Intelligent Analysis",
            ai_subtitle: "Кӯмак мекунад, ки маълумотҳоро таҳлил кунед, равандҳоро автоматизатсия кунед ва маълумотро дар шакли қулай нишон диҳед",
            userAnalytics: "Таҳлили корбарон",
            baseActivity: "Фаъолияти пойгоҳ",
            audienceDynamics: "Динамикаи афзоиши шунавандагон",
            registrations: "Бақайдгириҳо",
            deletions: "Несткуниҳо",
            usersCount: "Корбарон",
            activeProfiles: "профилҳои фаъол",
            new30Days: "Нав (30 рӯз)",
            churnRate: "Churn Rate",
            churnDesc: "коэффитсиенти ҷудошавӣ",
            contact_label: "Оғоз",
            contact_title: "Омодаед оғоз кунед?",
            contact_subtitle: "Ба ҳазорҳо ширкатҳо ҳамроҳ шавед, ки аллакай DocSign-ро истифода мебаранд",
            footer_desc: "Платформаи интеллектуалии идоракунии электронии ҳуҷҷатҳо барои бизнеси Тоҷикистон.",
            footer_product: "Маҳсулот",
            footer_features: "Имкониятҳо",
            footer_security: "Амният",
            footer_ai: "Таҳлили AI",
            footer_pricing: "Нархҳо",
            footer_company: "Ширкат",
            footer_about: "Дар бораи мо",
            footer_careers: "Касб",
            footer_blog: "Блог",
            footer_contact: "Тамос",
            footer_rights: "Ҳамаи ҳуқуқҳо ҳифз шудаанд.",
            footer_made: "Дар Тоҷикистон сохта шудааст"
        }
    };

    // Language Switcher
    let currentLang = 'ru';

    function setLanguage(lang) {
        currentLang = lang;
        const t = translations[lang];

        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (t[key]) {
                el.textContent = t[key];
            }
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (t[key]) {
                el.placeholder = t[key];
            }
        });

        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.lang === lang);
        });

        document.documentElement.lang = lang;
    }

    document.querySelectorAll('.lang-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            setLanguage(btn.dataset.lang);
        });
    });

    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('glass-strong', 'shadow-lg', 'shadow-black/20');
        } else {
            navbar.classList.remove('glass-strong', 'shadow-lg', 'shadow-black/20');
        }
    });

    // Mobile menu
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // Scroll Reveal
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal').forEach(el => {
        observer.observe(el);
    });

    // Counter Animation
    function animateCounter(el, target, duration = 2000) {
        const start = 0;
        const startTime = performance.now();
        const isDecimal = String(target).includes('.');

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = start + (target - start) * easeOut;

            if (isDecimal) {
                el.textContent = current.toFixed(1);
            } else {
                el.textContent = Math.floor(current);
            }

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseFloat(entry.target.dataset.target);
                if (!isNaN(target)) {
                    animateCounter(entry.target, target);
                }
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter').forEach(el => {
        counterObserver.observe(el);
    });

    // Back to Top Button
    const backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            backToTop.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            backToTop.classList.add('opacity-100', 'translate-y-0');
        } else {
            backToTop.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            backToTop.classList.remove('opacity-100', 'translate-y-0');
        }
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Parallax
    const heroSection = document.getElementById('hero');
    if (heroSection) {
        heroSection.addEventListener('mousemove', (e) => {
            const orbs = heroSection.querySelectorAll('.orb');
            const x = (e.clientX / window.innerWidth - 0.5) * 2;
            const y = (e.clientY / window.innerHeight - 0.5) * 2;

            orbs.forEach((orb, i) => {
                const speed = (i + 1) * 10;
                orb.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });
    }

    // ApexCharts
    // ApexCharts с кастомным тултипом и обновленными данными (53 пользователя, 8 удалений)
    document.addEventListener('DOMContentLoaded', () => {
        setLanguage('ru');

        // Данные по дням мая. В сумме тут ровно 53 регистрации и 8 удалений.
        const demoData = [
            { date: '01.05', reg: 0, del: 0 }, { date: '02.05', reg: 2, del: 0 },
            { date: '03.05', reg: 0, del: 0 }, { date: '04.05', reg: 4, del: 1 },
            { date: '05.05', reg: 0, del: 0 }, { date: '06.05', reg: 3, del: 0 },
            { date: '07.05', reg: 1, del: 0 }, { date: '08.05', reg: 0, del: 0 },
            { date: '09.05', reg: 5, del: 2 }, { date: '10.05', reg: 8, del: 0 }, // Пик регистраций
            { date: '11.05', reg: 0, del: 0 }, { date: '12.05', reg: 3, del: 1 },
            { date: '13.05', reg: 2, del: 0 }, { date: '14.05', reg: 12, del: 0 }, // Большой пик
            { date: '15.05', reg: 1, del: 0 }, { date: '16.05', reg: 0, del: 0 },
            { date: '17.05', reg: 10, del: 4 }, // Пик удалений и регистраций
            { date: '18.05', reg: 2, del: 0 }, { date: '19.05', reg: 0, del: 0 },
            { date: '20.05', reg: 0, del: 0 }, { date: '21.05', reg: 0, del: 0 },
            { date: '22.05', reg: 0, del: 0 }, { date: '23.05', reg: 0, del: 0 },
            { date: '24.05', reg: 0, del: 0 }, { date: '25.05', reg: 0, del: 0 },
            { date: '26.05', reg: 0, del: 0 }, { date: '27.05', reg: 0, del: 0 },
            { date: '28.05', reg: 0, del: 0 }, { date: '29.05', reg: 0, del: 0 },
            { date: '30.05', reg: 0, del: 0 }, { date: '31.05', reg: 0, del: 0 }
        ];

        const chartOptions = {
            series: [{
                name: 'Регистрации',
                data: demoData.map(item => item.reg)
            }, {
                name: 'Удаления',
                data: demoData.map(item => item.del)
            }],
            chart: {
                type: 'line', // Чистая линия, без старой заливки area
                height: 350,
                toolbar: { show: false },
                zoom: { enabled: false },
                background: 'transparent',
                foreColor: '#64748b',
                fontFamily: 'Inter, sans-serif'
            },
            colors: ['#3b82f6', '#ef4444'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 4,
                strokeColors: '#111827',
                strokeWidth: 2,
                hover: { size: 6 }
            },
            dataLabels: { enabled: false },
            grid: {
                borderColor: 'rgba(255, 255, 255, 0.05)',
                strokeDashArray: 4,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: demoData.map(item => item.date),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af' } }
            },
            yaxis: {
                min: 0,
                tickAmount: 4,
                labels: { style: { colors: '#9ca3af' } }
            },
            legend: { show: false },

            // Красивое всплывающее окошко при ведении мышкой
            tooltip: {
                shared: true,
                intersect: false,
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const date = w.globals.categoryLabels[dataPointIndex];
                    const registrations = series[0][dataPointIndex];
                    const deletions = series[1][dataPointIndex];

                    return `
                    <div style="background: #151d30; border: 1px solid #24324f; border-radius: 8px; padding: 12px 16px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5); min-width: 160px; font-family: sans-serif;">
                        <div style="font-weight: bold; font-size: 13px; color: #ffffff; margin-bottom: 8px; border-bottom: 1px dashed #24324f; padding-bottom: 4px;">${date}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin-top: 4px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #9ca3af;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6;"></span>
                                Регистрации:
                            </div>
                            <div style="font-weight: bold; color: #ffffff;">${registrations}</div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin-top: 4px;">
                            <div style="display: flex; align-items: center; gap: 8px; color: #9ca3af;">
                                <span style="width: 8px; height: 8px; border-radius: 50%; background: #ef4444;"></span>
                                Удаления:
                            </div>
                            <div style="font-weight: bold; color: #ffffff;">${deletions}</div>
                        </div>
                    </div>
                `;
                }
            }
        };

        const chartEl = document.querySelector("#userChart");
        if (chartEl) {
            const chart = new ApexCharts(chartEl, chartOptions);
            chart.render();
        }
    });
    document.querySelector('.lang-select').addEventListener('change', (event) => {
        const selectedLang = event.target.value;

        // Здесь вызывается ваша функция смены языка.
        // Например, если у вас было что-то вроде changeLanguage(selectedLang):
        changeLanguage(selectedLang);

        console.log('Язык изменен на:', selectedLang); // Для теста
    });
</script>

</body>
</html>

