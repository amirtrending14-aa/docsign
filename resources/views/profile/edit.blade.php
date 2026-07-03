@extends('layouts.admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">

<style>
    .font-inter { font-family: 'Inter', sans-serif; }

    /* === DOC SIGN EDIT STYLE === */
    .edit-page {
        min-height: 100vh;
        padding: 32px 24px;
        color: var(--text);
    }

    /* Кнопка назад */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: rgba(var(--glow), 1);
        text-decoration: none;
        transition: all 0.25s ease;
        margin-bottom: 24px;
    }

    .back-link:hover {
        color: rgba(var(--glow), 0.8);
        transform: translateX(-3px);
    }

    .back-link svg {
        width: 16px;
        height: 16px;
    }

    /* Заголовок секции */
    .section-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--line);
        background: rgba(255,255,255,0.02);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        box-shadow: 0 0 10px currentColor;
    }

    .section-dot.profile { background: rgba(var(--glow), 1); color: rgba(var(--glow), 1); }
    .section-dot.security { background: #a78bfa; color: #a78bfa; }

    .section-title {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: var(--muted);
    }

    /* Карточка */
    .edit-card {
        position: relative;
        background: linear-gradient(180deg, rgba(255,255,255,0.035), rgba(255,255,255,0.01));
        border: 1px solid var(--line);
        border-radius: var(--radius);
        overflow: hidden;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .edit-card::before {
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

    .edit-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -20px rgba(var(--glow), 0.3);
    }

    .edit-card-body {
        padding: 24px;
    }

    /* Поля формы */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--muted);
        margin-bottom: 8px;
        display: block;
    }

    .form-label .required {
        color: #ff7a7a;
        margin-left: 2px;
    }

    .form-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 12px 16px;
        color: var(--text);
        font-size: 14px;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s ease;
        outline: none;
    }

    .form-input::placeholder {
        color: rgba(255,255,255,0.3);
    }

    .form-input:focus {
        border-color: rgba(var(--glow), 0.6);
        box-shadow: 0 0 0 3px rgba(var(--glow), 0.15), 0 0 12px rgba(var(--glow), 0.1);
        background: rgba(255,255,255,0.05);
    }

    .form-input[readonly] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .form-input[readonly]:focus {
        border-color: var(--line);
        box-shadow: none;
    }

    .form-error {
        font-size: 11px;
        font-weight: 600;
        color: #ff7a7a;
        margin-top: 6px;
    }

    /* Аватар */
    .avatar-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        object-fit: cover;
        border: 2px solid rgba(var(--glow), 0.3);
        box-shadow: 0 8px 24px rgba(var(--glow), 0.2);
    }

    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(var(--glow), 0.6), rgba(var(--glow), 0.2));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 900;
        color: #fff;
        border: 2px solid rgba(var(--glow), 0.3);
        box-shadow: 0 8px 24px rgba(var(--glow), 0.2);
    }

    .avatar-upload {
        flex: 1;
    }

    .avatar-upload input[type="file"] {
        width: 100%;
        padding: 10px;
        background: rgba(255,255,255,0.03);
        border: 1px dashed var(--line);
        border-radius: 10px;
        color: var(--muted);
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .avatar-upload input[type="file"]:hover {
        border-color: rgba(var(--glow), 0.4);
        background: rgba(var(--glow), 0.05);
    }

    .avatar-remove {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
        font-size: 12px;
        font-weight: 600;
        color: #ff7a7a;
        cursor: pointer;
    }

    .avatar-remove input[type="checkbox"] {
        accent-color: #ff7a7a;
    }

    /* Кнопка сохранения */
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(180deg, rgba(var(--glow), 0.95), rgba(var(--glow), 0.65));
        color: #0a0d14;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        padding: 12px 24px;
        border-radius: 10px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 8px 24px rgba(var(--glow), 0.35), inset 0 1px 0 rgba(255,255,255,0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(var(--glow), 0.5);
        filter: brightness(1.08);
    }

    .btn-save:active {
        transform: scale(0.97);
    }

    /* Инфо-бокс компании */
    .company-info-box {
        background: rgba(var(--glow), 0.08);
        border: 1px solid rgba(var(--glow), 0.2);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 12px;
    }

    .company-info-box p {
        color: rgba(var(--glow), 1);
        font-size: 12px;
        font-weight: 600;
        margin: 0;
    }

    .company-hint {
        font-size: 11px;
        color: var(--muted);
        margin-top: 6px;
    }

    /* Danger Zone */
    .danger-zone {
        position: relative;
        background: linear-gradient(180deg, rgba(255, 99, 99, 0.05), rgba(255, 99, 99, 0.02));
        border: 1px solid rgba(255, 99, 99, 0.2);
        border-radius: var(--radius);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .danger-zone::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: var(--radius);
        padding: 1px;
        background: linear-gradient(135deg, rgba(255, 99, 99, 0.4), transparent 40%, transparent 60%, rgba(255, 99, 99, 0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0.6;
        pointer-events: none;
    }

    .danger-zone:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px -20px rgba(255, 99, 99, 0.3);
    }

    .danger-content {
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }

    .danger-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .danger-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ff6363;
        box-shadow: 0 0 0 3px rgba(255, 99, 99, 0.2), 0 0 10px rgba(255, 99, 99, 0.6);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .danger-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #ff7a7a;
        margin-bottom: 4px;
    }

    .danger-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        opacity: 0.8;
        margin: 0;
    }

    .btn-danger {
        background: rgba(255, 99, 99, 0.1);
        color: #ff7a7a;
        border: 1px solid rgba(255, 99, 99, 0.3);
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-danger:hover {
        background: rgba(255, 99, 99, 0.2);
        border-color: rgba(255, 99, 99, 0.5);
        box-shadow: 0 0 16px rgba(255, 99, 99, 0.3);
        transform: translateY(-2px);
    }

    /* Модальное окно */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-content {
        background: linear-gradient(180deg, rgba(22, 26, 38, 0.98), rgba(16, 19, 28, 0.98));
        border: 1px solid rgba(255, 99, 99, 0.3);
        border-radius: 20px;
        padding: 32px;
        max-width: 450px;
        width: 100%;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(255, 99, 99, 0.15);
    }

    .modal-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text);
        margin: 0 0 8px;
        text-align: center;
    }

    .modal-description {
        font-size: 13px;
        color: var(--muted);
        margin: 0 0 24px;
        text-align: center;
    }

    .modal-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 12px 16px;
        color: var(--text);
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        margin-bottom: 20px;
        outline: none;
        transition: all 0.2s ease;
    }

    .modal-input:focus {
        border-color: rgba(255, 99, 99, 0.6);
        box-shadow: 0 0 0 3px rgba(255, 99, 99, 0.15);
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn-modal {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 1px solid transparent;
    }

    .btn-modal.cancel {
        background: rgba(255,255,255,0.05);
        color: var(--muted);
        border-color: var(--line);
    }

    .btn-modal.cancel:hover {
        background: rgba(255,255,255,0.08);
        color: var(--text);
    }

    .btn-modal.confirm {
        background: linear-gradient(180deg, #ff6363, #ff4444);
        color: #fff;
        box-shadow: 0 8px 24px rgba(255, 99, 99, 0.35);
    }

    .btn-modal.confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(255, 99, 99, 0.5);
    }

    /* Уведомления */
    .alert {
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        border: 1px solid;
    }

    .alert.success {
        background: rgba(76, 217, 130, 0.1);
        border-color: rgba(76, 217, 130, 0.3);
        color: #4cd982;
    }

    .alert.error {
        background: rgba(255, 99, 99, 0.1);
        border-color: rgba(255, 99, 99, 0.3);
        color: #ff7a7a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .edit-page { padding: 20px 16px; }
        .edit-card-body { padding: 20px; }
        .avatar-section { flex-direction: column; align-items: flex-start; }
        .danger-content { flex-direction: column; align-items: flex-start; }
    }
</style>

<div class="edit-page font-inter">

    {{-- Уведомления --}}
    @if(session('status') === 'profile-updated')
    <div class="alert success">
        ✅ <span data-i18n="profileUpdated">Профиль успешно обновлён!</span>
    </div>
    @endif

    @if(session('status') === 'password-updated')
    <div class="alert success">
        ✅ <span data-i18n="passwordUpdated">Пароль успешно изменён!</span>
    </div>
    @endif

    @if($errors->userDeletion->has('password'))
    <div class="alert error">
        ❌ <span data-i18n="errorPassword">Неверный пароль. Попробуйте еще раз.</span>
    </div>
    @endif

    {{-- Кнопка назад --}}
    <a href="{{ route('profile.show') }}" class="back-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path d="M15 19l-7-7 7-7"/>
        </svg>
        <span data-i18n="btnBack">Назад</span>
    </a>

    <div style="max-width: 800px; margin: 0 auto;">

        {{-- ПРОФИЛЬ --}}
        <div class="edit-card">
            <div class="section-header">
                <div class="section-dot profile"></div>
                <span class="section-title" data-i18n="tabProfile">Профиль</span>
            </div>

            <div class="edit-card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Аватар --}}
                    <div class="form-group">
                        <label class="form-label">Аватар</label>
                        <div class="avatar-section">
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="avatar-preview">
                            @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                            <div class="avatar-upload">
                                <input type="file" name="avatar" accept="image/*">
                                @if($user->avatar)
                                <label class="avatar-remove">
                                    <input type="checkbox" name="remove_avatar" value="1">
                                    <span data-i18n="removeAvatar">Удалить аватар</span>
                                </label>
                                @endif
                            </div>
                        </div>
                        @error('avatar')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Имя --}}
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <span data-i18n="labelName">Имя</span> <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                        @error('name')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <span data-i18n="labelEmail">Email</span> <span class="required">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                        @error('email')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Телефон --}}
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            <span data-i18n="labelPhone">Телефон</span>
                        </label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input" placeholder="+992 XX XXX XX XX">
                        @error('phone')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Компания --}}
                    @if(!$user->isSuperAdmin())
                    <div class="form-group">
                        <label class="form-label" for="company">
                            <span data-i18n="labelCompany">Компания</span>
                        </label>

                        @if($user->isAdmin())
                        <div class="company-info-box">
                            <p>🏢 <span data-i18n="adminCompanyWarning">Вы администратор компании. Изменение названия обновит его для всех сотрудников.</span></p>
                        </div>
                        <input type="text" id="company" name="company" value="{{ old('company', $user->company) }}" class="form-input" placeholder="Название вашей компании">
                        @else
                        <input type="text" id="company" name="company" value="{{ old('company', $user->company) }}" class="form-input" readonly>
                        <p class="company-hint">🔒 <span data-i18n="companyLocked">Компания назначается администратором</span></p>
                        @endif

                        @error('company')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <button type="submit" class="btn-save">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>
                        </svg>
                        <span data-i18n="btnSave">Сохранить изменения</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- ПАРОЛЬ --}}
        <div class="edit-card">
            <div class="section-header">
                <div class="section-dot security"></div>
                <span class="section-title" data-i18n="tabSecurity">Безопасность</span>
            </div>

            <div class="edit-card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label" for="current_password">
                            <span data-i18n="labelCurrentPassword">Текущий пароль</span> <span class="required">*</span>
                        </label>
                        <input type="password" id="current_password" name="current_password" class="form-input" required>
                        @error('current_password', 'updatePassword')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">
                            <span data-i18n="labelNewPassword">Новый пароль</span> <span class="required">*</span>
                        </label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        @error('password', 'updatePassword')
                        <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            <span data-i18n="labelConfirmPassword">Подтверждение пароля</span> <span class="required">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>

                    <button type="submit" class="btn-save">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <span data-i18n="btnChangePassword">Изменить пароль</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- DANGER ZONE --}}
        <div class="danger-zone">
            <div class="danger-content">
                <div class="danger-info">
                    <div class="danger-dot"></div>
                    <div>
                        <div class="danger-title" data-i18n="dangerZoneTitle">Danger Zone</div>
                        <p class="danger-text" data-i18n="deleteWarning">Удаление аккаунта сотрет все данные безвозвратно.</p>
                    </div>
                </div>
                <button type="button" onclick="openCustomDeleteModal()" class="btn-danger" data-i18n="btnDeleteAccount">
                    Удалить аккаунт
                </button>
            </div>
        </div>

    </div>
</div>

{{-- Скрытая форма удаления --}}
<div style="display: none;">
    <form id="delete-user-form" method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <input type="password" name="password">
    </form>
</div>

{{-- Модальное окно --}}
<div id="customDeleteModal" class="modal-overlay">
    <div class="modal-content">
        <h2 class="modal-title" data-i18n="confirmPassTitle">Подтвердите пароль</h2>
        <p class="modal-description" data-i18n="confirmPassDesc">Это действие необратимо. Введите пароль для удаления.</p>
        <form onsubmit="submitLaravelDeletion(event)">
            <input type="password" id="customPasswordInput" data-i18n-placeholder="placeholderPass" placeholder="Ваш пароль" class="modal-input" required>
            <div class="modal-actions">
                <button type="button" onclick="closeCustomDeleteModal()" class="btn-modal cancel" data-i18n="btnCancel">
                    Отмена
                </button>
                <button type="submit" class="btn-modal confirm" data-i18n="btnConfirmDelete">
                    Удалить
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ РЕДАКТИРОВАНИЯ ПРОФИЛЯ
        // (дополняет глобальный TRANSLATIONS из layouts/admin.blade.php)
        // ============================================================
        const PROFILE_TRANSLATIONS = {
            ru: {
                btnBack: 'Назад',
                tabProfile: 'Профиль',
                tabSecurity: 'Безопасность',
                dangerZoneTitle: 'Danger Zone',
                deleteWarning: 'Удаление аккаунта сотрет все данные безвозвратно.',
                btnDeleteAccount: 'Удалить аккаунт',
                confirmPassTitle: 'Подтвердите пароль',
                confirmPassDesc: 'Это действие необратимо. Введите пароль для удаления.',
                placeholderPass: 'Ваш пароль',
                btnCancel: 'Отмена',
                btnConfirmDelete: 'Удалить',
                errorPassword: 'Неверный пароль. Попробуйте еще раз.',
                profileUpdated: 'Профиль успешно обновлён!',
                passwordUpdated: 'Пароль успешно изменён!',
                labelName: 'Имя',
                labelEmail: 'Email',
                labelPhone: 'Телефон',
                labelCompany: 'Компания',
                labelCurrentPassword: 'Текущий пароль',
                labelNewPassword: 'Новый пароль',
                labelConfirmPassword: 'Подтверждение пароля',
                btnSave: 'Сохранить изменения',
                btnChangePassword: 'Изменить пароль',
                removeAvatar: 'Удалить аватар',
                adminCompanyWarning: 'Вы администратор компании. Изменение названия обновит его для всех сотрудников.',
                companyLocked: 'Компания назначается администратором'
            },
            tj: {
                btnBack: 'Бозгашт',
                tabProfile: 'Профил',
                tabSecurity: 'Амният',
                dangerZoneTitle: 'Минтақаи хавфнок',
                deleteWarning: 'Нест кардани аккаунт ҳамаи маълумотро ба таври ҳамешагӣ нест мекунад.',
                btnDeleteAccount: 'Нест кардан',
                confirmPassTitle: 'Рамзро тасдиқ кунед',
                confirmPassDesc: 'Ин амал бозгашт надорад. Барои нест кардан рамзро ворид кунед.',
                placeholderPass: 'Рамзи шумо',
                btnCancel: 'Бекор кардан',
                btnConfirmDelete: 'Нест кардан',
                errorPassword: 'Рамз нодуруст аст. Дубора кӯшиш кунед.',
                profileUpdated: 'Профил бо муваффақият нав карда шуд!',
                passwordUpdated: 'Рамз бо муваффақият иваз карда шуд!',
                labelName: 'Ном',
                labelEmail: 'Email',
                labelPhone: 'Телефон',
                labelCompany: 'Ширкат',
                labelCurrentPassword: 'Рамзи ҷорӣ',
                labelNewPassword: 'Рамзи нав',
                labelConfirmPassword: 'Тасдиқи рамз',
                btnSave: 'Нигоҳ доштан',
                btnChangePassword: 'Иваз кардани рамз',
                removeAvatar: 'Нест кардани аватар',
                adminCompanyWarning: 'Шумо администратори ширкат ҳастед. Тағйири ном барои ҳамаи кормандон нав карда мешавад.',
                companyLocked: 'Ширкат аз ҷониби администратор таъин карда мешавад'
            },
            en: {
                btnBack: 'Back',
                tabProfile: 'Profile',
                tabSecurity: 'Security',
                dangerZoneTitle: 'Danger Zone',
                deleteWarning: 'Deleting your account will erase all data permanently.',
                btnDeleteAccount: 'Delete Account',
                confirmPassTitle: 'Confirm Password',
                confirmPassDesc: 'This action is irreversible. Enter your password to delete.',
                placeholderPass: 'Your password',
                btnCancel: 'Cancel',
                btnConfirmDelete: 'Delete',
                errorPassword: 'Incorrect password. Please try again.',
                profileUpdated: 'Profile successfully updated!',
                passwordUpdated: 'Password successfully changed!',
                labelName: 'Name',
                labelEmail: 'Email',
                labelPhone: 'Phone',
                labelCompany: 'Company',
                labelCurrentPassword: 'Current Password',
                labelNewPassword: 'New Password',
                labelConfirmPassword: 'Confirm Password',
                btnSave: 'Save Changes',
                btnChangePassword: 'Change Password',
                removeAvatar: 'Remove Avatar',
                adminCompanyWarning: 'You are a company administrator. Changing the name will update it for all employees.',
                companyLocked: 'Company is assigned by administrator'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ НА ЭТОЙ СТРАНИЦЕ
        // ============================================================
        function applyProfileTranslations(lang) {
            const dict = PROFILE_TRANSLATIONS[lang] || PROFILE_TRANSLATIONS.ru;

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
        applyProfileTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        //    (когда юзер кликает на 🇷🇺/🇹🇯/🇬🇧 в админке)
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyProfileTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyProfileTranslations(e.newValue);
            }
        });
    });

    // ============================================================
    // МОДАЛЬНОЕ ОКНО УДАЛЕНИЯ АККАУНТА
    // ============================================================
    function openCustomDeleteModal() {
        const m = document.getElementById('customDeleteModal');
        m.classList.add('active');
        setTimeout(() => {
            document.getElementById('customPasswordInput').focus();
        }, 100);
    }

    function closeCustomDeleteModal() {
        document.getElementById('customDeleteModal').classList.remove('active');
        document.getElementById('customPasswordInput').value = '';
    }

    function submitLaravelDeletion(e) {
        e.preventDefault();
        const password = document.getElementById('customPasswordInput').value;
        const realForm = document.getElementById('delete-user-form');
        if (realForm) {
            realForm.querySelector('input[name="password"]').value = password;
            realForm.submit();
        }
    }

    document.getElementById('customDeleteModal').addEventListener('click', function (event) {
        if (event.target === this) closeCustomDeleteModal();
    });
</script>
@endsection