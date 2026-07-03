<section class="space-y-6">
    <!-- 1. СКРЫТАЯ ТЕХНИЧЕСКАЯ ФОРМА (Для Laravel) -->
    <div style="display: none;">
        <form id="real-delete-form" method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <input type="password" name="password" id="hidden-password-input">
        </form>
    </div>

    <!-- 2. ОШИБКА -->
    @if($errors->userDeletion->has('password'))
        <div class="max-w-4xl mx-auto mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-2xl text-center font-black animate-bounce"
             data-i18n="errorPassword">
            ❌ Неверный пароль. Попробуйте еще раз.
        </div>
    @endif

    <!-- 3. КРАСИВЫЙ БЛОК DANGER ZONE -->
    <div class="relative group overflow-hidden max-w-4xl mx-auto my-10 font-sans" style="isolation: isolate;">
        <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-orange-600 rounded-[2rem] blur opacity-0 group-hover:opacity-10 transition duration-500"></div>

        <div class="relative bg-white rounded-2xl border border-red-200/50 shadow-xl overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 p-6">
                <div class="flex flex-col items-center md:items-start gap-2">
                    <div class="flex items-center gap-2.5">
                        <div class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-600"></span>
                        </div>
                        <h3 class="text-red-600 text-[11px] font-black uppercase tracking-[0.2em]">Danger Zone</h3>
                    </div>
                    <p class="text-black text-[13px] font-bold leading-tight m-0 opacity-80" data-i18n="deleteWarning">
                        Удаление аккаунта сотрет все данные безвозвратно.
                    </p>
                </div>

                <div class="flex-shrink-0">
                    <button
                        type="button"
                        onclick="openCustomDeleteModal()"
                        class="bg-red-50 text-red-600 border-2 border-red-600/20 font-black uppercase text-[9px] tracking-widest px-5 py-2 rounded-lg transition-all duration-300 hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-[0_0_15px_rgba(220,38,38,0.3)] active:scale-95"
                        data-i18n="btnDeleteAccount"
                    >
                        УДАЛИТЬ АККАУНТ
                    </button>
                </div>
            </div>
            <div class="h-1 w-full bg-gradient-to-r from-transparent via-red-500/20 to-transparent"></div>
        </div>
    </div>

    <!-- 4. ТВОЯ КАСТОМНАЯ МОДАЛКА -->
    <div id="customDeleteModal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-black/60 backdrop-blur-sm" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-red-100 overflow-hidden">
            <div class="p-6 text-center">
                <h2 class="text-xl font-black text-gray-900 uppercase mb-2" data-i18n="confirmPassTitle">Подтвердите пароль</h2>
                <p class="text-gray-500 text-sm mb-6" data-i18n="confirmPassDesc">Введите пароль для безвозвратного удаления аккаунта.</p>

                <form onsubmit="submitLaravelDeletion(event)">
                    <input
                        type="password"
                        id="customPasswordInput"
                        data-i18n-placeholder="placeholderPass"
                        placeholder="Ваш пароль"
                        required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-red-500 outline-none mb-4 text-center text-black"
                    >
                    <div class="flex gap-3">
                        <button type="button" onclick="closeCustomDeleteModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl uppercase text-[10px]" data-i18n="btnCancel">Отмена</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white font-bold rounded-xl uppercase text-[10px]" data-i18n="btnConfirmDelete">Удалить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ DANGER ZONE
        // (дополняет глобальный TRANSLATIONS из layouts/admin.blade.php)
        // ============================================================
        const DANGER_ZONE_TRANSLATIONS = {
            ru: {
                deleteWarning: 'Удаление аккаунта сотрет все данные безвозвратно.',
                btnDeleteAccount: 'УДАЛИТЬ АККАУНТ',
                confirmPassTitle: 'Подтвердите пароль',
                confirmPassDesc: 'Это действие необратимо. Введите пароль для удаления.',
                placeholderPass: 'Ваш пароль',
                btnCancel: 'Отмена',
                btnConfirmDelete: 'Удалить',
                errorPassword: '❌ Неверный пароль. Попробуйте еще раз.'
            },
            tj: {
                deleteWarning: 'Нест кардани аккаунт ҳамаи маълумотро ба таври ҳамешагӣ нест мекунад.',
                btnDeleteAccount: 'НЕСТ КАРДАНИ АККАУНТ',
                confirmPassTitle: 'Рамзро тасдиқ кунед',
                confirmPassDesc: 'Ин амал бозгашт надорад. Барои нест кардан рамзро ворид кунед.',
                placeholderPass: 'Рамзи шумо',
                btnCancel: 'Бекор кардан',
                btnConfirmDelete: 'Нест кардан',
                errorPassword: '❌ Рамз нодуруст аст. Дубора кӯшиш кунед.'
            },
            en: {
                deleteWarning: 'Deleting your account will erase all data permanently.',
                btnDeleteAccount: 'DELETE ACCOUNT',
                confirmPassTitle: 'Confirm Password',
                confirmPassDesc: 'This action is irreversible. Enter your password to delete.',
                placeholderPass: 'Your password',
                btnCancel: 'Cancel',
                btnConfirmDelete: 'Delete',
                errorPassword: '❌ Incorrect password. Please try again.'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applyDangerZoneTranslations(lang) {
            const dict = DANGER_ZONE_TRANSLATIONS[lang] || DANGER_ZONE_TRANSLATIONS.ru;

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
        applyDangerZoneTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        //    (когда юзер кликает на 🇷🇺/🇹🇯/🇬🇧 в админке)
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyDangerZoneTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyDangerZoneTranslations(e.newValue);
            }
        });
    });

    // ============================================================
    // МОДАЛЬНОЕ ОКНО УДАЛЕНИЯ АККАУНТА
    // ============================================================
    function openCustomDeleteModal() {
        const m = document.getElementById('customDeleteModal');
        m.style.display = 'flex';
        m.classList.remove('hidden');
        setTimeout(() => document.getElementById('customPasswordInput').focus(), 100);
    }

    function closeCustomDeleteModal() {
        const m = document.getElementById('customDeleteModal');
        m.style.display = 'none';
        m.classList.add('hidden');
        document.getElementById('customPasswordInput').value = '';
    }

    function submitLaravelDeletion(e) {
        e.preventDefault();
        const password = document.getElementById('customPasswordInput').value;
        const realForm = document.getElementById('real-delete-form');
        const hiddenInput = document.getElementById('hidden-password-input');

        if (realForm && hiddenInput) {
            hiddenInput.value = password;
            realForm.submit();
        }
    }
</script>
