<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wide" data-i18n="updatePasswordTitle">
            Обновление пароля
        </h2>
        <p class="mt-1 text-sm text-slate-500" data-i18n="updatePasswordDesc">
            Используйте длинный случайный пароль, чтобы ваш аккаунт оставался в безопасности.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Текущий пароль --}}
        <div>
            <label for="update_password_current_password" class="block text-[11px] font-black uppercase tracking-widest text-slate-700 mb-2" data-i18n="currentPasswordLabel">
                Текущий пароль
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold text-black"
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs text-red-500 font-bold" />
        </div>

        {{-- Новый пароль --}}
        <div>
            <label for="update_password_password" class="block text-[11px] font-black uppercase tracking-widest text-slate-700 mb-2" data-i18n="newPasswordLabel">
                Новый пароль
            </label>
            <input id="update_password_password" name="password" type="password"
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold text-black"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs text-red-500 font-bold" />
        </div>

        {{-- Подтверждение пароля --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-[11px] font-black uppercase tracking-widest text-slate-700 mb-2" data-i18n="confirmPasswordLabel">
                Подтвердите пароль
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold text-black"
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs text-red-500 font-bold" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-3 bg-[#0f172a] hover:bg-amber-500 text-white font-black uppercase text-[11px] tracking-widest rounded-xl shadow-xl transition-all active:scale-95" data-i18n="btnSave">
                Сохранить изменения
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                     class="flex items-center gap-2 text-emerald-600 font-bold text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span data-i18n="statusUpdated">Обновлено</span>
                </div>
            @endif
        </div>
    </form>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ ФОРМЫ ОБНОВЛЕНИЯ ПАРОЛЯ
        // (дополняет глобальный TRANSLATIONS из layouts/admin.blade.php)
        // ============================================================
        const PASSWORD_TRANSLATIONS = {
            ru: {
                updatePasswordTitle: 'Обновление пароля',
                updatePasswordDesc: 'Используйте длинный случайный пароль, чтобы ваш аккаунт оставался в безопасности.',
                currentPasswordLabel: 'Текущий пароль',
                newPasswordLabel: 'Новый пароль',
                confirmPasswordLabel: 'Подтвердите пароль',
                btnSave: 'Сохранить изменения',
                statusUpdated: 'Обновлено'
            },
            tj: {
                updatePasswordTitle: 'Навсозии рамз',
                updatePasswordDesc: 'Барои бехатарии аккаунти худ рамзи дароз ва тасодуфиро истифода баред.',
                currentPasswordLabel: 'Рамзи ҷорӣ',
                newPasswordLabel: 'Рамзи нав',
                confirmPasswordLabel: 'Тасдиқи рамз',
                btnSave: 'Захира кардани тағйирот',
                statusUpdated: 'Навсозӣ шуд'
            },
            en: {
                updatePasswordTitle: 'Update Password',
                updatePasswordDesc: 'Ensure your account is using a long, random password to stay secure.',
                currentPasswordLabel: 'Current Password',
                newPasswordLabel: 'New Password',
                confirmPasswordLabel: 'Confirm Password',
                btnSave: 'Save Changes',
                statusUpdated: 'Saved'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applyPasswordTranslations(lang) {
            const dict = PASSWORD_TRANSLATIONS[lang] || PASSWORD_TRANSLATIONS.ru;

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
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyPasswordTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        //    (когда юзер кликает на 🇷🇺/🇹🇯/🇬🇧 в админке)
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyPasswordTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyPasswordTranslations(e.newValue);
            }
        });
    });
</script>
