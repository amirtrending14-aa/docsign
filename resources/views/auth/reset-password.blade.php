<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DocSign — Новый пароль</title>
    <link href="https://fonts.bunny.net/css?family=figtree:500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--p:#4f46e5;--a:#06b6d4;--bg:#0f172a;--card:rgba(30,41,59,.9);--txt:#f1f5f9;--muted:#94a3b8;--brd:rgba(148,163,184,.2)}
        *{margin:0;padding:0;box-sizing:border-box}
        body{
            font-family:'Figtree',sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background:var(--bg);
            color:var(--txt);
            padding:20px;
            position:relative;
            overflow-x:hidden;
        }

        /* Фоновые эффекты */
        .bg{
            position:fixed;
            inset:0;
            z-index:0;
            background:radial-gradient(ellipse at top,rgba(79,70,229,.15),transparent 50%),
                       radial-gradient(ellipse at bottom,rgba(6,182,212,.1),transparent 50%);
        }

        .lang{
            position:fixed;
            top:20px;
            right:20px;
            display:flex;
            gap:4px;
            background:rgba(15,23,42,.9);
            border:1px solid var(--brd);
            border-radius:10px;
            padding:4px;
            z-index:10;
            backdrop-filter:blur(10px);
        }
        .lang button{
            padding:6px 12px;
            border:none;
            background:transparent;
            color:var(--muted);
            font:600 12px Figtree,sans-serif;
            border-radius:8px;
            cursor:pointer;
            transition:.2s;
        }
        .lang button.active,.lang button:hover{
            background:var(--p);
            color:#fff;
        }

        /* Основная карточка */
        .card{
            width:100%;
            max-width:440px;
            background:var(--card);
            backdrop-filter:blur(20px);
            border:1px solid var(--brd);
            border-radius:24px;
            padding:40px 32px;
            box-shadow:0 25px 80px rgba(0,0,0,.5);
            position:relative;
            z-index:1;
            margin:auto;
        }

        .card::before{
            content:'';
            position:absolute;
            top:0;
            left:0;
            right:0;
            height:3px;
            background:linear-gradient(90deg,#f59e0b,var(--p),var(--a));
            border-radius:24px 24px 0 0;
        }

        /* Логотип */
        .logo{
            text-align:center;
            margin-bottom:24px;
        }
        .logo img{
            width:64px;
            height:64px;
            border-radius:16px;
            margin:0 auto 12px;
            display:block;
            box-shadow:0 8px 32px rgba(79,70,229,.4);
        }
        .logo h1{
            font:800 26px Figtree,sans-serif;
            letter-spacing:-.5px;
            margin-bottom:4px;
        }
        .logo h1 span{
            background:linear-gradient(135deg,var(--p),var(--a));
            -webkit-background-clip:text;
            -webkit-text-fill-color:transparent;
        }
        .logo p{
            font:600 11px Figtree,sans-serif;
            color:var(--muted);
            text-transform:uppercase;
            letter-spacing:3px;
        }

        /* Поля формы */
        .field{
            margin-bottom:20px;
        }
        .field label{
            display:block;
            font:600 12px Figtree,sans-serif;
            color:var(--muted);
            margin-bottom:8px;
            text-transform:uppercase;
            letter-spacing:1px;
        }
        .input{
            position:relative;
        }
        .input input{
            width:100%;
            padding:14px 16px 14px 44px;
            background:rgba(15,23,42,.6);
            border:1px solid var(--brd);
            border-radius:12px;
            color:var(--txt);
            font:500 14px Figtree,sans-serif;
            transition:.3s;
            outline:none;
        }
        .input input:focus{
            border-color:var(--a);
            box-shadow:0 0 0 4px rgba(6,182,212,.15);
            background:rgba(15,23,42,.8);
        }
        .input svg.icn{
            position:absolute;
            left:14px;
            top:50%;
            transform:translateY(-50%);
            width:18px;
            height:18px;
            color:var(--muted);
            pointer-events:none;
            transition:.2s;
        }
        .input input:focus + svg.icn{
            color:var(--a);
        }
        .eye{
            position:absolute;
            right:12px;
            top:50%;
            transform:translateY(-50%);
            background:none;
            border:none;
            color:var(--muted);
            cursor:pointer;
            padding:4px;
            transition:.2s;
        }
        .eye:hover{
            color:var(--txt);
        }

        /* Индикатор силы пароля */
        .str{
            height:3px;
            border-radius:3px;
            background:var(--brd);
            margin-top:8px;
            overflow:hidden;
        }
        .str span{
            height:100%;
            display:block;
            width:0;
            transition:width .3s ease;
        }
        .str .w{width:33%;background:#ef4444;}
        .str .m{width:66%;background:#f59e0b;}
        .str .s{width:100%;background:#10b981;}

        /* Кнопка */
        .btn{
            width:100%;
            padding:14px;
            background:linear-gradient(135deg,#f59e0b,var(--p));
            border:none;
            border-radius:12px;
            color:#fff;
            font:700 15px Figtree,sans-serif;
            cursor:pointer;
            transition:.3s;
            display:flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            margin-top:8px;
            box-shadow:0 4px 20px rgba(245,158,11,.3);
        }
        .btn:hover{
            transform:translateY(-2px);
            box-shadow:0 8px 30px rgba(245,158,11,.4);
        }
        .btn:active{
            transform:translateY(0);
        }
        .btn:disabled{
            opacity:.6;
            cursor:not-allowed;
            transform:none;
        }

        /* Ссылка назад */
        .back{
            text-align:center;
            margin-top:24px;
            padding-top:20px;
            border-top:1px solid var(--brd);
        }
        .back a{
            font:600 13px Figtree,sans-serif;
            color:var(--muted);
            text-decoration:none;
            display:inline-flex;
            align-items:center;
            gap:6px;
            transition:.2s;
        }
        .back a:hover{
            color:var(--a);
        }

        /* Ошибки и успех */
        .err{
            font:500 12px Figtree,sans-serif;
            color:#ef4444;
            margin-top:6px;
        }
        .input input.error{
            border-color:#ef4444;
            box-shadow:0 0 0 4px rgba(239,68,68,.15);
        }
        .success-msg{
            padding:14px;
            background:rgba(16,185,129,.15);
            border:1px solid rgba(16,185,129,.3);
            color:#34d399;
            border-radius:12px;
            margin-bottom:20px;
            font-size:14px;
            text-align:center;
            font-weight:600;
        }

        /* Бейджи */
        .badges{
            display:flex;
            justify-content:center;
            gap:20px;
            margin-top:24px;
            font:600 11px Figtree,sans-serif;
            color:var(--muted);
        }
        .badges div{
            display:flex;
            align-items:center;
            gap:6px;
        }
        .badges svg{
            width:14px;
            height:14px;
            color:#f59e0b;
        }

        /* Копирайт */
        .copy{
            text-align:center;
            margin-top:20px;
            font:500 12px Figtree,sans-serif;
            color:rgba(148,163,184,.6);
        }

        /* Адаптивность */
        @media(max-width:480px){
            .card{
                padding:32px 20px;
                border-radius:20px;
            }
            .logo h1{
                font-size:22px;
            }
            .logo img{
                width:56px;
                height:56px;
            }
        }
    </style>
</head>
<body>
<div class="bg"></div>

<div class="lang">
    <select id="langSelect" onchange="setLang(this.value)">
        <option value="ru" selected>🇷🇺 Русский</option>
        <option value="tj">🇹🇯 Тоҷикӣ</option>
        <option value="en">🇬 English</option>
    </select>
</div>
<style>
    .lang {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10;
}

.lang select {
    padding: 8px 32px 8px 12px;
    background: rgba(15, 23, 42, 0.9);
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 10px;
    color: #f1f5f9;
    font: 600 13px Figtree, sans-serif;
    cursor: pointer;
    outline: none;
    backdrop-filter: blur(10px);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    transition: all 0.2s;
}

.lang select:hover {
    border-color: #4f46e5;
}

.lang select:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
}

.lang select option {
    background: #1e293b;
    color: #f1f5f9;
    padding: 8px;
}
</style>
<script>
    function setLang(lang) {
    currentLang = lang;
    localStorage.setItem('docSign_lang', lang);

    // Обновляем select
    document.getElementById('langSelect').value = lang;

    // Обновляем тексты
    document.querySelectorAll('[data-i18n]').forEach(el => {
        if (translations[lang][el.dataset.i18n]) {
            el.textContent = translations[lang][el.dataset.i18n];
        }
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    const savedLang = localStorage.getItem('docSign_lang') || 'ru';
    document.getElementById('langSelect').value = savedLang;
    setLang(savedLang);
});
</script>
<div class="card">
    <div class="logo">
        <img src="https://image.qwenlm.ai/public_source/5fabf35d-788a-476d-8837-6431dd4fb2c8/1bb634345-5339-4471-924b-764b665ee39d.png" alt="DocSign">
        <h1>Doc<span>Sign</span></h1>
        <p data-i18n="sub">Сброс пароля</p>
    </div>

    @if(session('status'))
    <div class="success-msg">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" id="resetForm" onsubmit="return handleSubmit(event)">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="field">
            <label data-i18n="l1" for="email">Email</label>
            <div class="input">
                <input type="email" name="email" id="email" placeholder="name@company.com"
                       value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                <svg class="icn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                </svg>
            </div>
            @error('email')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label data-i18n="l2" for="password">Новый пароль</label>
            <div class="input">
                <input type="password" name="password" id="password" placeholder="••••••••"
                       required autocomplete="new-password" oninput="checkStrength(this.value)">
                <svg class="icn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <button type="button" class="eye" onclick="togglePassword('password','eye1')" aria-label="Toggle password">
                    <svg id="eye1" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            <div class="str"><span id="strengthBar"></span></div>
            @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="field">
            <label data-i18n="l3" for="password_confirmation">Подтвердите пароль</label>
            <div class="input">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="••••••••" required autocomplete="new-password">
                <svg class="icn" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                <button type="button" class="eye" onclick="togglePassword('password_confirmation','eye2')" aria-label="Toggle password">
                    <svg id="eye2" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')<div class="err">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn" id="submitBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
            </svg>
            <span data-i18n="btn">Сбросить пароль</span>
        </button>
    </form>

    <div class="back">
        <a href="{{ route('login') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5"/>
                <path d="m12 19-7-7 7-7"/>
            </svg>
            <span data-i18n="bk">Вернуться ко входу</span>
        </a>
    </div>
</div>




<script>
    const translations = {
        ru: {
            sub: 'Сброс пароля', l1: 'Email', l2: 'Новый пароль', l3: 'Подтвердите пароль',
            btn: 'Сбросить пароль', bk: 'Вернуться ко входу', sec: 'Защита', sig: 'ЭЦП',
            cp: 'Все права защищены.', errorMatch: 'Пароли не совпадают'
        },
        tj: {
            sub: 'Барқарорсозии рамз', l1: 'Email', l2: 'Рамзи нав', l3: 'Тасдиқ кунед',
            btn: 'Барқарор кардан', bk: 'Бозгашт ба воридшавӣ', sec: 'Ҳифз', sig: 'ЭИИ',
            cp: 'Ҳуқуқҳо ҳифз шудаанд.', errorMatch: 'Рамзҳо мувофиқ нестанд'
        },
        en: {
            sub: 'Reset Password', l1: 'Email', l2: 'New Password', l3: 'Confirm Password',
            btn: 'Reset Password', bk: 'Back to Login', sec: 'Security', sig: 'EDS',
            cp: 'All rights reserved.', errorMatch: 'Passwords do not match'
        }
    };

    let currentLang = 'ru';

    function setLang(lang) {
        currentLang = lang;
        localStorage.setItem('docSign_lang', lang);
        document.querySelectorAll('.lang button').forEach(b =>
            b.classList.toggle('active', b.dataset.lang === lang)
        );
        document.querySelectorAll('[data-i18n]').forEach(el => {
            if (translations[lang][el.dataset.i18n]) {
                el.textContent = translations[lang][el.dataset.i18n];
            }
        });
    }

    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    function checkStrength(value) {
        const bar = document.getElementById('strengthBar');
        if (!value) {
            bar.className = '';
        } else if (value.length < 6) {
            bar.className = 'w';
        } else if (value.length < 10) {
            bar.className = 'm';
        } else {
            bar.className = 's';
        }
    }

    function handleSubmit(event) {
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        password.classList.remove('error');
        confirm.classList.remove('error');

        if (password.value !== confirm.value) {
            confirm.classList.add('error');
            alert(translations[currentLang].errorMatch);
            event.preventDefault();
            return false;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><span>Обработка...</span>';
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedLang = localStorage.getItem('docSign_lang') || 'ru';
        setLang(savedLang);
    });
</script>
</body>
</html>