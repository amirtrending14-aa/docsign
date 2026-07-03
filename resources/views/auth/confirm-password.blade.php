<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DocSign — Подтверждение</title>
    <link href="https://fonts.bunny.net/css?family=figtree:500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--p:#4f46e5;--a:#06b6d4;--bg:#0f172a;--card:rgba(30,41,59,.8);--txt:#f1f5f9;--muted:#94a3b8;--brd:rgba(148,163,184,.15)}
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Figtree',sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg);color:var(--txt);padding:20px}
        .lang{position:fixed;top:16px;right:16px;display:flex;gap:4px;background:rgba(15,23,42,.9);border:1px solid var(--brd);border-radius:10px;padding:4px}
        .lang button{padding:6px 12px;border:none;background:transparent;color:var(--muted);font:600 12px Figtree,sans-serif;border-radius:8px;cursor:pointer;transition:.2s}
        .lang button.active,.lang button:hover{background:var(--p);color:#fff}
        .card{width:100%;max-width:440px;background:var(--card);backdrop-filter:blur(20px);border:1px solid var(--brd);border-radius:20px;padding:36px 32px;box-shadow:0 20px 60px rgba(0,0,0,.4)}
        .card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#f59e0b,var(--p),var(--a));border-radius:20px 20px 0 0}
        .logo{text-align:center;margin-bottom:24px}
        .logo img{width:64px;height:64px;border-radius:16px;margin:0 auto 12px;display:block;box-shadow:0 8px 24px rgba(79,70,229,.3)}
        .logo h1{font:800 24px Figtree,sans-serif;letter-spacing:-.5px}
        .logo h1 span{background:linear-gradient(135deg,var(--p),var(--a));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .logo p{font:500 11px Figtree,sans-serif;color:var(--muted);text-transform:uppercase;letter-spacing:2px;margin-top:4px}
        .info{display:flex;gap:10px;padding:12px 14px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:10px;margin-bottom:20px;font:500 13px Figtree,sans-serif;color:var(--muted)}
        .info svg{width:18px;height:18px;color:#f59e0b;flex-shrink:0}
        .field{margin-bottom:18px}
        .field label{display:block;font:600 13px Figtree,sans-serif;color:var(--muted);margin-bottom:6px}
        .input{position:relative}
        .input input{width:100%;padding:12px 14px 12px 42px;background:rgba(15,23,42,.6);border:1px solid var(--brd);border-radius:12px;color:var(--txt);font:500 14px Figtree,sans-serif;transition:.2s;outline:none}
        .input input:focus{border-color:var(--a);box-shadow:0 0 0 3px rgba(6,182,212,.15)}
        .input svg{position:absolute;left:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--muted);pointer-events:none}
        .toggle{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;padding:4px}
        .toggle:hover{color:var(--txt)}
        .btn{width:100%;padding:14px;background:linear-gradient(135deg,#f59e0b,var(--p));border:none;border-radius:12px;color:#fff;font:700 15px Figtree,sans-serif;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(245,158,11,.3)}
        .btn:active{transform:none}
        .back{text-align:center;margin-top:20px}
        .back a{font:500 14px Figtree,sans-serif;color:var(--muted);text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:.2s}
        .back a:hover{color:var(--a)}
        .badges{display:flex;justify-content:center;gap:20px;margin-top:24px;font:600 11px Figtree,sans-serif;color:var(--muted);opacity:.8}
        .badges svg{width:14px;height:14px;color:#f59e0b}
        .copy{text-align:center;margin-top:16px;font:500 12px Figtree,sans-serif;color:rgba(148,163,184,.5)}
        .err{font:500 12px Figtree,sans-serif;color:#ef4444;margin-top:6px}
        .input input.error{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.15)}
        @media(max-width:480px){.card{padding:28px 20px}.logo h1{font-size:20px}}
    </style>
</head>
<body>
<div class="lang">
    <button class="active" data-lang="ru" onclick="setLang('ru')">🇺 РУ</button>
    <button data-lang="tj" onclick="setLang('tj')">🇹🇯 TJ</button>
    <button data-lang="en" onclick="setLang('en')">🇬🇧 EN</button>
</div>

<div class="card">
    <div class="logo">
        <img src="https://image.qwenlm.ai/public_source/5fabf35d-788a-476d-8837-6431dd4fb2c8/1bb634345-5339-4471-924b-764b665ee39d.png" alt="DocSign">
        <h1>Doc<span>Sign</span></h1>
        <p data-i18n="sub">Подтверждение</p>
    </div>

    <div class="info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="8" r="1"/><path d="M12 12v4"/></svg>
        <span data-i18n="info">Подтвердите пароль для продолжения</span>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" onsubmit="return submitForm(event)">
        @csrf
        <div class="field">
            <label data-i18n="lbl">Пароль</label>
            <div class="input">
                <input type="password" name="password" id="pwd" placeholder="••••••••" required autocomplete="current-password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <button type="button" class="toggle" onclick="togglePwd()">
                    <svg id="eye" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password')<div class="err">{{ $message }}</div>@enderror
        </div>
        <button class="btn" id="btn"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg><span data-i18n="btn">Подтвердить</span></button>
    </form>

    <div class="back">
        <a href="{{ url()->previous() }}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg><span data-i18n="back">Назад</span></a>
    </div>
</div>

<div class="badges">
    <div><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg> SSL</div>
    <div><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2z"/></svg> <span data-i18n="sec">Защита</span></div>
    <div><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg> <span data-i18n="sig">ЭЦП</span></div>
</div>
<p class="copy">© {{ date('Y') }} DocSign. <span data-i18n="copy">Все права защищены.</span></p>

<script>
    const t={ru:{sub:'Подтверждение',info:'Подтвердите пароль для продолжения',lbl:'Пароль',btn:'Подтвердить',back:'Назад',sec:'Защита',sig:'ЭЦП',copy:'Все права защищены.',err:'Введите пароль'},tj:{sub:'Тасдиқ',info:'Рамзро барои идома тасдиқ кунед',lbl:'Рамз',btn:'Тасдиқ',back:'Бозгашт',sec:'Ҳифз',sig:'ЭИИ',copy:'Ҳуқуқҳо ҳифз шудаанд.',err:'Рамзро ворид кунед'},en:{sub:'Confirm',info:'Confirm your password to continue',lbl:'Password',btn:'Confirm',back:'Back',sec:'Security',sig:'EDS',copy:'All rights reserved.',err:'Please enter password'}};
    let lang='ru';
    function setLang(l){lang=l;document.querySelectorAll('.lang button').forEach(b=>b.classList.toggle('active',b.dataset.lang===l));document.querySelectorAll('[data-i18n]').forEach(el=>{const k=el.dataset.i18n;if(t[lang][k])el.textContent=t[lang][k]});document.querySelectorAll('[data-i18n-ph]').forEach(el=>{const k=el.dataset.i18nPh;if(t[lang][k])el.placeholder=t[lang][k]})}
    function togglePwd(){const i=document.getElementById('pwd'),e=document.getElementById('eye');i.type=i.type==='password'?'text':'password';e.innerHTML=i.type==='text'?'<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/><line x1="1" y1="1" x2="23" y2="23"/>':'<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'}
    function submitForm(e){const p=document.getElementById('pwd'),b=document.getElementById('btn');p.classList.remove('error');if(!p.value.trim()){p.classList.add('error');alert(t[lang].err);return false}b.disabled=true;b.style.opacity='.7';return true}
    document.getElementById('pwd')?.addEventListener('input',function(){this.classList.remove('error')});
</script>
</body>
</html>
