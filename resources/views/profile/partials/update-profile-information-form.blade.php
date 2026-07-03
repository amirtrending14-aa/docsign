<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-800" data-i18n="profileDataTitle">
            Данные профиля
        </h2>
        <p class="mt-1 text-sm text-slate-500" data-i18n="profileDataDesc">
            Обновите информацию вашего аккаунта и адрес электронной почты.
        </p>
    </header>

    {{-- Форма для верификации (скрытая) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('patch')

        {{-- ===== БЛОК ЗАГРУЗКИ ФОТО ===== --}}
        <div class="relative overflow-hidden rounded-2xl border-2 border-slate-200 bg-gradient-to-br from-slate-50 to-white p-6">
            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- Аватар с превью --}}
                <div class="relative group flex-shrink-0">
                    <div id="avatarWrapper" class="w-28 h-28 rounded-2xl overflow-hidden border-4 border-white shadow-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center relative">
                        @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        <img id="avatarPreview" src="" alt="Preview" class="w-full h-full object-cover hidden absolute inset-0">
                        <span id="avatarLetter" class="text-white text-4xl font-black italic select-none hidden">
                                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @else
                        <img id="avatarPreview" src="" alt="Preview" class="w-full h-full object-cover hidden absolute inset-0">
                        <span id="avatarLetter" class="text-white text-4xl font-black italic select-none">
                                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- Оверлей с иконкой камеры --}}
                    <label for="avatarInput" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-all duration-300 rounded-2xl flex items-center justify-center cursor-pointer backdrop-blur-sm">
                        <div class="text-center transform scale-75 group-hover:scale-100 transition-transform">
                            <svg class="w-8 h-8 text-white mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-white text-[10px] font-black uppercase tracking-wider" data-i18n="changePhoto">Изменить</span>
                        </div>
                    </label>

                    {{-- Индикатор онлайн --}}
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 border-4 border-white rounded-full shadow-lg"></div>
                </div>

                {{-- Информация и кнопки --}}
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="text-lg font-bold text-slate-800 mb-1" data-i18n="profilePhotoTitle">Фотография профиля</h3>
                    <p class="text-xs text-slate-500 mb-3" data-i18n="profilePhotoDesc">JPG, PNG или WEBP. Максимум 2MB.</p>

                    <input type="file" id="avatarInput" name="avatar" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewAvatar(this)">

                    {{-- СКРЫТОЕ ПОЛЕ ДЛЯ УДАЛЕНИЯ ФОТО --}}
                    <input type="hidden" id="removeAvatarFlag" name="remove_avatar" value="0">

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <label for="avatarInput" id="uploadBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span data-i18n="btnUpload">Загрузить фото</span>
                        </label>

                        <button type="button" id="removeBtn" onclick="removeAvatar()" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold uppercase tracking-wider rounded-xl border border-red-200 transition-all active:scale-95 {{ !auth()->user()->avatar ? 'hidden' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span data-i18n="btnRemovePhoto">Удалить</span>
                        </button>
                    </div>

                    {{-- Имя файла --}}
                    <p id="fileNameDisplay" class="text-xs text-slate-500 mt-2 font-medium truncate max-w-xs"></p>
                </div>
            </div>

            @error('avatar')
            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-600 font-bold flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $message }}
                </p>
            </div>
            @enderror
        </div>

        {{-- ===== ИМЯ ===== --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1" data-i18n="labelName">Имя</label>
            <input id="name" name="name" type="text"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                   value="{{ old('name', auth()->user()->name) }}"
                   required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-xs text-red-500" :messages="$errors->get('name')" />
        </div>

        {{-- ===== КОМПАНИЯ ===== --}}
        <div>
            <label for="company" class="block text-sm font-semibold text-slate-700 mb-1" data-i18n="labelCompany">Название компании</label>
            <input id="company" name="company" type="text"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                   value="{{ old('company', auth()->user()->company ?? '') }}"
                   autocomplete="organization" />
            <x-input-error class="mt-2 text-xs text-red-500" :messages="$errors->get('company')" />
        </div>

        {{-- ===== EMAIL ===== --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1" data-i18n="labelEmail">Email</label>
            <input id="email" name="email" type="email"
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                   value="{{ old('email', auth()->user()->email) }}"
                   required autocomplete="username" />
            <x-input-error class="mt-2 text-xs text-red-500" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                <p class="text-sm text-amber-800">
                    <span data-i18n="emailUnverified">Ваш адрес электронной почты не подтвержден.</span>
                    <button form="send-verification" class="block mt-1 underline font-bold hover:text-amber-900 transition-colors" data-i18n="btnResendVerify">
                        Нажмите здесь, чтобы отправить письмо снова.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600 italic" data-i18n="verifyLinkSent">
                    Новая ссылка отправлена на ваш email.
                </p>
                @endif
            </div>
            @endif
        </div>

        {{-- ===== ТЕЛЕФОН ===== --}}
        <div class="mt-4">
            <label class="block text-sm font-semibold text-slate-700 mb-1" data-i18n="labelPhone">Телефон</label>
            <input name="phone" type="text" id="phone" required
                   class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none shadow-sm text-black font-bold"
                   value="{{ old('phone', auth()->user()->phone ?? '+992 ') }}"
                   placeholder="+992 00 000 0000">
            <x-input-error class="mt-2 text-xs text-red-500" :messages="$errors->get('phone')" />
        </div>

        {{-- ===== КНОПКА СОХРАНЕНИЯ ===== --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all active:scale-95" data-i18n="btnSaveProfile">
                Сохранить профиль
            </button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
               class="text-sm font-medium text-emerald-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span data-i18n="statusSaved">Сохранено</span>
            </p>
            @endif
        </div>
    </form>
</section>

<script>
    // ============================================================
    // ЛОКАЛЬНЫЙ СЛОВАРЬ ФОРМЫ ПРОФИЛЯ
    // ============================================================
    const PROFILE_INFO_TRANSLATIONS = {
        ru: {
            profileDataTitle: 'Данные профиля',
            profileDataDesc: 'Обновите информацию вашего аккаунта и адрес электронной почты.',
            profilePhotoTitle: 'Фотография профиля',
            profilePhotoDesc: 'JPG, PNG или WEBP. Максимум 2MB.',
            changePhoto: 'Изменить',
            btnUpload: 'Загрузить фото',
            btnRemovePhoto: 'Удалить',
            labelName: 'Имя',
            labelCompany: 'Название компании',
            labelEmail: 'Email',
            labelPhone: 'Телефон',
            emailUnverified: 'Ваш адрес электронной почты не подтвержден.',
            btnResendVerify: 'Нажмите здесь, чтобы отправить письмо снова.',
            verifyLinkSent: 'Новая ссылка отправлена на ваш email.',
            btnSaveProfile: 'Сохранить профиль',
            statusSaved: 'Сохранено',
            alertFileTooLarge: 'Файл слишком большой. Максимум 2MB',
            alertRemovePhoto: 'Удалить фотографию профиля?',
            alertPhoneInvalid: 'Пожалуйста, введите номер телефона полностью (9 цифр после +992)'
        },
        tj: {
            profileDataTitle: 'Маълумоти профил',
            profileDataDesc: 'Маълумоти аккаунт ва суроғаи почтаи электронии худро навсозӣ кунед.',
            profilePhotoTitle: 'Расми профил',
            profilePhotoDesc: 'JPG, PNG ё WEBP. Ҳадди аксар 2MB.',
            changePhoto: 'Тағйир додан',
            btnUpload: 'Боргузории расм',
            btnRemovePhoto: 'Нест кардан',
            labelName: 'Ном',
            labelCompany: 'Номи ширкат',
            labelEmail: 'Email',
            labelPhone: 'Телефон',
            emailUnverified: 'Суроғаи почтаи электронии шумо тасдиқ нашудааст.',
            btnResendVerify: 'Барои дубора фиристодани мактуб инҷоро пахш кунед.',
            verifyLinkSent: 'Пайванди нав ба почтаи электронии шумо фиристода шуд.',
            btnSaveProfile: 'Захираи профил',
            statusSaved: 'Захира шуд',
            alertFileTooLarge: 'Файл хеле калон аст. Ҳадди аксар 2MB',
            alertRemovePhoto: 'Расми профилро нест мекунед?',
            alertPhoneInvalid: 'Лутфан, рақами телефонро пурра ворид кунед (9 рақам пас аз +992)'
        },
        en: {
            profileDataTitle: 'Profile Information',
            profileDataDesc: "Update your account's profile information and email address.",
            profilePhotoTitle: 'Profile Photo',
            profilePhotoDesc: 'JPG, PNG or WEBP. Maximum 2MB.',
            changePhoto: 'Change',
            btnUpload: 'Upload Photo',
            btnRemovePhoto: 'Remove',
            labelName: 'Name',
            labelCompany: 'Company Name',
            labelEmail: 'Email',
            labelPhone: 'Phone',
            emailUnverified: 'Your email address is unverified.',
            btnResendVerify: 'Click here to re-send the verification email.',
            verifyLinkSent: 'A new verification link has been sent to your email address.',
            btnSaveProfile: 'Save Profile',
            statusSaved: 'Saved',
            alertFileTooLarge: 'File too large. Maximum 2MB',
            alertRemovePhoto: 'Remove profile photo?',
            alertPhoneInvalid: 'Please enter the full phone number (9 digits after +992)'
        }
    };

    // ============================================================
    // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
    // ============================================================
    function applyProfileInfoTranslations(lang) {
        const dict = PROFILE_INFO_TRANSLATIONS[lang] || PROFILE_INFO_TRANSLATIONS.ru;

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

        // 3) Переводим title
        document.querySelectorAll('[data-i18n-title]').forEach(el => {
            const key = el.getAttribute('data-i18n-title');
            if (dict[key] !== undefined) el.setAttribute('title', dict[key]);
        });
    }

    // ============================================================
    // ФУНКЦИЯ ПОЛУЧЕНИЯ АКТУАЛЬНОГО СЛОВАРЯ
    // (используется в alert/confirm, чтобы брать перевод в момент вызова)
    // ============================================================
    function getCurrentDict() {
        const lang = localStorage.getItem('docsign_lang') || 'ru';
        return PROFILE_INFO_TRANSLATIONS[lang] || PROFILE_INFO_TRANSLATIONS.ru;
    }

    // ============================================================
    // ПРЕВЬЮ АВТАРА
    // ============================================================
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Проверка размера (2MB) — берём АКТУАЛЬНЫЙ язык в момент вызова
            if (file.size > 2 * 1024 * 1024) {
                const dict = getCurrentDict();
                alert(dict.alertFileTooLarge);
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                const preview = document.getElementById('avatarPreview');
                const letter = document.getElementById('avatarLetter');
                const removeFlag = document.getElementById('removeAvatarFlag');
                const removeBtn = document.getElementById('removeBtn');
                const fileNameDisplay = document.getElementById('fileNameDisplay');

                // Показываем превью
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }

                // Скрываем букву
                if (letter) letter.classList.add('hidden');

                // Скрываем текущий аватар (img внутри avatarWrapper, кроме preview)
                const currentAvatar = document.querySelector('#avatarWrapper > img:not(#avatarPreview)');
                if (currentAvatar) currentAvatar.classList.add('hidden');

                // Сбрасываем флаг удаления
                removeFlag.value = '0';

                // Показываем кнопку удаления
                if (removeBtn) removeBtn.classList.remove('hidden');

                // Показать имя файла
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = '📎 ' + file.name;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // ============================================================
    // УДАЛЕНИЕ АВТАРА
    // ============================================================
    function removeAvatar() {
        // Берём АКТУАЛЬНЫЙ язык в момент вызова
        const dict = getCurrentDict();

        if (confirm(dict.alertRemovePhoto)) {
            const preview = document.getElementById('avatarPreview');
            const letter = document.getElementById('avatarLetter');
            const input = document.getElementById('avatarInput');
            const removeFlag = document.getElementById('removeAvatarFlag');
            const removeBtn = document.getElementById('removeBtn');
            const fileNameDisplay = document.getElementById('fileNameDisplay');

            // Скрываем превью
            if (preview) {
                preview.src = '';
                preview.classList.add('hidden');
            }

            // Показываем букву
            if (letter) letter.classList.remove('hidden');

            // Скрываем текущий аватар
            const currentAvatar = document.querySelector('#avatarWrapper > img:not(#avatarPreview)');
            if (currentAvatar) currentAvatar.classList.add('hidden');

            // Очищаем input file
            if (input) input.value = '';

            // Устанавливаем флаг удаления для сервера
            removeFlag.value = '1';

            // Скрываем кнопку удаления
            if (removeBtn) removeBtn.classList.add('hidden');

            // Очищаем имя файла
            if (fileNameDisplay) fileNameDisplay.textContent = '';
        }
    }

    // ============================================================
    // ОСНОВНОЙ СКРИПТ
    // ============================================================
    document.addEventListener('DOMContentLoaded', function () {
        const phoneInput = document.getElementById('phone');
        const form = document.getElementById('profileForm');
        const prefix = '+992 ';

        // ============================================================
        // 1. Применяем переводы сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyProfileInfoTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyProfileInfoTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyProfileInfoTranslations(e.newValue);
            }
        });

        // ============================================================
        // Форматирование телефона
        // ============================================================
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                if (!e.target.value.startsWith(prefix)) e.target.value = prefix;
                let digits = e.target.value.substring(prefix.length).replace(/\D/g, '').substring(0, 9);
                let formatted = '';
                if (digits.length > 0) formatted += digits.substring(0, 2);
                if (digits.length >= 3) formatted += ' ' + digits.substring(2, 5);
                if (digits.length >= 6) formatted += ' ' + digits.substring(5, 7);
                if (digits.length >= 8) formatted += ' ' + digits.substring(7, 9);
                e.target.value = prefix + formatted;
            });
        }

        // ============================================================
        // Валидация телефона при отправке
        // ============================================================
        if (form && phoneInput) {
            form.addEventListener('submit', function (e) {
                let digitsOnly = phoneInput.value.substring(prefix.length).replace(/\D/g, '');
                if (digitsOnly.length < 9) {
                    e.preventDefault();
                    phoneInput.style.border = '2px solid #ef4444';
                    phoneInput.focus();
                    // Берём АКТУАЛЬНЫЙ язык в момент отправки
                    const dict = getCurrentDict();
                    alert(dict.alertPhoneInvalid);
                }
            });
        }
    });
</script>