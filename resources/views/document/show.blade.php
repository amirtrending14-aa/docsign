@extends('layouts.admin')

@section('content')
<style>
    /* === СТРАНИЦА ПРОСМОТРА ДОКУМЕНТА === */
    .doc-show-page {
        color: #e7ecf3;
        padding: 24px 16px;
        min-height: calc(100vh - 64px);
    }

    /* Уведомление об ошибке */
    .error-toast {
        position: fixed;
        top: 80px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 9999;
        width: 100%;
        max-width: 400px;
        padding: 0 16px;
    }
    .error-toast-inner {
        background: rgba(22, 26, 38, 0.98);
        border: 1px solid rgba(255, 99, 99, 0.4);
        border-left: 3px solid #ff6b6b;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4), 0 0 20px rgba(255, 99, 99, 0.15);
    }
    .error-toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 99, 99, 0.15);
        border: 1px solid rgba(255, 99, 99, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ff6b6b;
        font-size: 14px;
        flex-shrink: 0;
    }
    .error-toast-title {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #ff6b6b;
        margin-bottom: 2px;
    }
    .error-toast-text {
        font-size: 11px;
        font-weight: 600;
        color: #fff;
    }
    .error-toast-close {
        background: none;
        border: none;
        color: #8892a6;
        cursor: pointer;
        font-size: 12px;
        padding: 4px;
        transition: all 0.2s;
    }
    .error-toast-close:hover {
        color: #fff;
    }

    /* Карточка */
    .doc-card {
        background: linear-gradient(180deg, rgba(22, 26, 38, 0.95), rgba(16, 19, 28, 0.95));
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    }
    .doc-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: 16px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(79,140,255,0.5), transparent 40%, transparent 60%, rgba(79,140,255,0.3));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0.7;
    }

    /* Заголовок страницы */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Кнопка назад */
    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #8892a6;
        text-decoration: none;
        font-size: 13px;
        transition: all 0.25s ease;
    }
    .back-btn:hover {
        color: #fff;
        border-color: rgba(79,140,255, 0.5);
        background: rgba(79,140,255, 0.08);
        box-shadow: 0 0 12px rgba(79,140,255, 0.2);
    }
    .back-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8892a6;
    }

    /* Кнопки действий */
    .action-btns {
        display: flex;
        gap: 8px;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid;
    }
    .action-btn-edit {
        background: rgba(79,140,255, 0.15);
        border-color: rgba(79,140,255, 0.4);
        color: #4f8cff;
    }
    .action-btn-edit:hover {
        background: rgba(79,140,255, 0.25);
        border-color: rgba(79,140,255, 0.7);
        box-shadow: 0 0 12px rgba(79,140,255, 0.3);
        color: #fff;
    }
    .action-btn-delete {
        background: rgba(255, 99, 99, 0.1);
        border-color: rgba(255, 99, 99, 0.3);
        color: #ff6b6b;
    }
    .action-btn-delete:hover {
        background: rgba(255, 99, 99, 0.2);
        border-color: rgba(255, 99, 99, 0.6);
        box-shadow: 0 0 12px rgba(255, 99, 99, 0.3);
        color: #fff;
    }

    /* Кнопка ОТКАЗА */
    .action-btn-reject {
        background: rgba(255, 107, 107, 0.1);
        border-color: rgba(255, 107, 107, 0.3);
        color: #ff6b6b;
    }
    .action-btn-reject:hover {
        background: rgba(255, 107, 107, 0.2);
        border-color: rgba(255, 107, 107, 0.6);
        box-shadow: 0 0 12px rgba(255, 107, 107, 0.3);
        color: #fff;
    }

    /* Модалка удаления */
    .modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(8px);
    }
    .modal-box {
        background: linear-gradient(180deg, rgba(22, 26, 38, 0.98), rgba(16, 19, 28, 0.98));
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 14px;
        padding: 24px;
        max-width: 380px;
        width: 100%;
        box-shadow: 0 20px 50px rgba(0,0,0,0.6), 0 0 30px rgba(255, 99, 99, 0.1);
    }
    .modal-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-title i {
        color: #ff6b6b;
        font-size: 16px;
    }
    .modal-desc {
        font-size: 12px;
        color: #a8b2c1;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    .modal-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .modal-btn-cancel {
        background: rgba(255,255,255,0.04);
        border-color: rgba(255,255,255,0.15);
        color: #8892a6;
    }
    .modal-btn-cancel:hover {
        background: rgba(255,255,255,0.08);
        color: #fff;
    }
    .modal-btn-delete {
        background: rgba(255, 99, 99, 0.15);
        border-color: rgba(255, 99, 99, 0.5);
        color: #ff6b6b;
    }
    .modal-btn-delete:hover {
        background: rgba(255, 99, 99, 0.25);
        border-color: rgba(255, 99, 99, 0.8);
        box-shadow: 0 0 12px rgba(255, 99, 99, 0.3);
        color: #fff;
    }

    /* Модалка отказа */
    .modal-reject {
        max-width: 450px;
    }
    .modal-reject .modal-title i {
        color: #ff6b6b;
    }
    .reject-textarea {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 12px 14px;
        color: #fff;
        font: 500 12px 'Inter', sans-serif;
        outline: none;
        resize: vertical;
        min-height: 100px;
        transition: all 0.2s ease;
        margin-bottom: 16px;
    }
    .reject-textarea::placeholder {
        color: rgba(255,255,255,0.3);
    }
    .reject-textarea:focus {
        border-color: rgba(255, 107, 107, 0.6);
        box-shadow: 0 0 0 2px rgba(255, 107, 107, 0.15), 0 0 12px rgba(255, 107, 107, 0.1);
        background: rgba(255,255,255,0.05);
    }
    .reject-hint {
        font-size: 10px;
        color: #8892a6;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .reject-hint i {
        color: #f59e0b;
        font-size: 12px;
    }
    .modal-btn-reject {
        background: rgba(255, 107, 107, 0.15);
        border-color: rgba(255, 107, 107, 0.5);
        color: #ff6b6b;
    }
    .modal-btn-reject:hover {
        background: rgba(255, 107, 107, 0.25);
        border-color: rgba(255, 107, 107, 0.8);
        box-shadow: 0 0 12px rgba(255, 107, 107, 0.3);
        color: #fff;
    }

    /* Сетка контента */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 280px;
        gap: 20px;
    }
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Бейджи документа */
    .doc-badges {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .badge-type {
        background: rgba(79,140,255, 0.2);
        border: 1px solid rgba(79,140,255, 0.4);
        color: #4f8cff;
    }
    .badge-id {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.15);
        color: #fff;
    }
    .badge-number {
        background: rgba(79,140,255, 0.08);
        border: 1px solid rgba(79,140,255, 0.25);
        color: #4f8cff;
    }

    /* Заголовок документа */
    .doc-title {
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 20px;
        line-height: 1.3;
        letter-spacing: -0.3px;
    }

    /* Контент документа */
    .doc-content {
        font-size: 13px;
        color: #c5cdd9;
        line-height: 1.7;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,0.06);
        white-space: pre-line;
        word-break: break-word;
    }

    /* Файл */
    .file-card {
        background: linear-gradient(180deg, rgba(22, 26, 38, 0.95), rgba(16, 19, 28, 0.95));
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        transition: all 0.25s ease;
    }
    .file-card:hover {
        border-color: rgba(79,140,255, 0.4);
        box-shadow: 0 0 20px rgba(79,140,255, 0.1);
    }
    .file-info {
        display: flex;
        align-items: center;
        gap: 12px;
        overflow: hidden;
    }
    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        transition: all 0.25s ease;
    }
    .file-icon-pdf {
        background: rgba(255, 99, 99, 0.15);
        border: 1px solid rgba(255, 99, 99, 0.3);
        color: #ff6b6b;
    }
    .file-icon-word {
        background: rgba(79,140,255, 0.15);
        border: 1px solid rgba(79,140,255, 0.3);
        color: #4f8cff;
    }
    .file-icon-excel {
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    .file-icon-rtf {
        background: rgba(168, 85, 247, 0.15);
        border: 1px solid rgba(168, 85, 247, 0.3);
        color: #a855f7;
    }
    .file-card:hover .file-icon {
        transform: scale(1.05);
    }
    .file-name {
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
    .file-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 4px;
    }
    .file-meta span {
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        color: #8892a6;
    }
    .file-meta .dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #8892a6;
    }
    .file-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }
    .file-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid;
    }
    .file-btn-view {
        background: rgba(255,255,255,0.04);
        border-color: rgba(255,255,255,0.15);
        color: #8892a6;
    }
    .file-btn-view:hover {
        background: rgba(79,140,255, 0.1);
        border-color: rgba(79,140,255, 0.4);
        color: #4f8cff;
    }
    .file-btn-download {
        background: rgba(79,140,255, 0.15);
        border-color: rgba(79,140,255, 0.4);
        color: #4f8cff;
    }
    .file-btn-download:hover {
        background: rgba(79,140,255, 0.25);
        border-color: rgba(79,140,255, 0.7);
        box-shadow: 0 0 12px rgba(79,140,255, 0.3);
        color: #fff;
    }

    /* Комментарии */
    .comments-section {
        margin-top: 20px;
    }
    .section-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8892a6;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-title i {
        color: #f59e0b;
        font-size: 12px;
    }
    .comment-form {
        position: relative;
        margin-bottom: 16px;
    }
    .comment-input {
        width: 100%;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 12px 44px 12px 14px;
        color: #fff;
        font: 500 12px 'Inter', sans-serif;
        outline: none;
        resize: none;
        min-height: 60px;
        transition: all 0.2s ease;
    }
    .comment-input::placeholder {
        color: rgba(255,255,255,0.3);
    }
    .comment-input:focus {
        border-color: rgba(245, 158, 11, 0.6);
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.15), 0 0 12px rgba(245, 158, 11, 0.1);
        background: rgba(255,255,255,0.05);
    }
    .comment-submit {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: none;
        border: none;
        color: #f59e0b;
        cursor: pointer;
        font-size: 16px;
        padding: 4px;
        transition: all 0.2s;
    }
    .comment-submit:hover {
        color: #fbbf24;
        transform: scale(1.1);
    }

    /* Список комментариев */
    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .comment-item {
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(245, 158, 11, 0.06));
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: 12px;
        padding: 14px;
    }
    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .comment-author {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #f59e0b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-right: 8px;
    }
    .comment-time {
        font-size: 9px;
        font-weight: 700;
        color: #8892a6;
        background: rgba(255,255,255,0.04);
        padding: 2px 8px;
        border-radius: 10px;
        flex-shrink: 0;
    }
    .comment-text {
        font-size: 12px;
        color: #c5cdd9;
        line-height: 1.5;
        word-break: break-word;
    }
    .comment-delete {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
        padding-top: 8px;
    }
    .comment-delete-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: none;
        border: none;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: rgba(255,255,255,0.5);
        cursor: pointer;
        transition: all 0.2s;
    }
    .comment-delete-btn:hover {
        color: #ff6b6b;
    }

    /* Пустые комментарии */
    .comments-empty {
        text-align: center;
        padding: 24px;
        border: 1px dashed rgba(255,255,255,0.1);
        border-radius: 12px;
        background: rgba(255,255,255,0.02);
    }
    .comments-empty i {
        font-size: 20px;
        color: #8892a6;
        opacity: 0.5;
        margin-bottom: 8px;
        display: block;
    }
    .comments-empty p {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #8892a6;
    }

    /* Боковая панель */
    .sidebar-card {
        background: linear-gradient(180deg, rgba(22, 26, 38, 0.95), rgba(16, 19, 28, 0.95));
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }
    .sidebar-card::before {
        content: "";
        position: absolute;
        inset: -1px;
        border-radius: 14px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(79,140,255,0.4), transparent 40%, transparent 60%, rgba(79,140,255,0.2));
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
        opacity: 0.6;
    }
    .sidebar-title {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8892a6;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }
    .sidebar-row + .sidebar-row {
        border-top: 1px solid rgba(255,255,255,0.04);
    }
    .sidebar-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #8892a6;
    }
    .sidebar-value {
        font-size: 11px;
        font-weight: 600;
        color: #fff;
    }
    .sidebar-value-deadline {
        color: #ff6b6b;
        font-style: italic;
    }

    /* === БЛОК УЧАСТНИКОВ === */
    .participants-section {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .participants-title {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8892a6;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .participants-title i {
        color: #4f8cff;
        font-size: 11px;
    }
    .participant-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }
    .participant-item:hover {
        background: rgba(79,140,255,0.04);
        border-color: rgba(79,140,255,0.2);
    }
    .participant-item:last-child {
        margin-bottom: 0;
    }
    .participant-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    .participant-avatar-owner {
        background: linear-gradient(135deg, rgba(79,140,255,0.3), rgba(79,140,255,0.15));
        border: 1px solid rgba(79,140,255,0.4);
    }
    .participant-avatar-receiver {
        background: linear-gradient(135deg, rgba(34,197,94,0.3), rgba(34,197,94,0.15));
        border: 1px solid rgba(34,197,94,0.4);
    }
    .participant-info {
        flex: 1;
        min-width: 0;
    }
    .participant-role {
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .participant-role-owner {
        color: #4f8cff;
    }
    .participant-role-receiver {
        color: #22c55e;
    }
    .participant-name {
        font-size: 11px;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .participant-name-empty {
        color: #8892a6;
        font-style: italic;
    }

    /* Статус подписи */
    .signature-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .signature-signed {
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    .signature-processing {
        background: rgba(245, 158, 11, 0.12);
        border: 1px solid rgba(245, 158, 11, 0.3);
        color: #f59e0b;
    }
    .signature-not-signed {
        background: rgba(255, 99, 99, 0.1);
        border: 1px solid rgba(255, 99, 99, 0.25);
        color: #ff6b6b;
    }

    /* Статус документа */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .status-draft {
        background: rgba(148, 163, 184, 0.12);
        border: 1px solid rgba(148, 163, 184, 0.3);
        color: #94a3b8;
    }
    .status-active {
        background: rgba(79,140,255, 0.12);
        border: 1px solid rgba(79,140,255, 0.3);
        color: #4f8cff;
    }
    .status-completed, .status-approved {
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }
    .status-rejected {
        background: rgba(255, 107, 107, 0.12);
        border: 1px solid rgba(255, 107, 107, 0.3);
        color: #ff6b6b;
    }

    /* Live Document индикатор */
    .live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: rgba(34, 197, 94, 0.08);
        border: 1px solid rgba(34, 197, 94, 0.2);
        border-radius: 10px;
        margin-top: 12px;
    }
    .live-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 8px rgba(34, 197, 94, 0.8);
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(0.9); }
    }
    .live-text {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #22c55e;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .doc-card {
            padding: 16px;
        }
        .file-card {
            flex-direction: column;
            align-items: stretch;
        }
        .file-actions {
            justify-content: stretch;
        }
        .file-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

@if(session('error') || $errors->any())
<div class="error-toast" x-data="{ show: true }"
     x-init="setTimeout(() => show = false, 4000)"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform -translate-y-2"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-end="opacity-0 transform -translate-y-2">

    <div class="error-toast-inner">
        <div class="error-toast-icon">
            <i class="bi bi-exclamation-octagon-fill"></i>
        </div>
        <div style="flex:1;">
            <div class="error-toast-title" data-i18n="errorTitle">Ошибка доступа</div>
            <p class="error-toast-text" id="session-error-text" data-error-raw="{{ session('error') ?? $errors->first() }}">
                {{ session('error') ?? $errors->first() }}
            </p>
        </div>
        <button @click="show = false" class="error-toast-close">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
</div>
@endif

<div class="doc-show-page">
    <div class="max-w-6xl mx-auto">

        {{-- Заголовок страницы --}}
        <div class="page-header">
            <div class="page-header-left">
                <a href="{{ route('documents.index') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <span class="back-label" data-i18n="back">Назад</span>
            </div>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
            <style>
                /* Контейнер кнопок */
.action-btns {
    display: flex;
    gap: 8px;
    align-items: center;
    justify-content: flex-end;
}

/* Базовые стили кнопок */
.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn i {
    font-size: 16px;
    color: #fff;
}

/* Кнопка редактирования */
.action-btn-edit {
    background: rgba(79, 140, 255, 0.15);
    border-color: rgba(79, 140, 255, 0.3);
}

.action-btn-edit:hover {
    background: rgba(79, 140, 255, 0.3);
    border-color: #4f8cff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(79, 140, 255, 0.3);
}

.action-btn-edit i {
    color: #4f8cff;
}

/* Кнопка удаления */
.action-btn-delete {
    background: rgba(255, 107, 107, 0.15);
    border-color: rgba(255, 107, 107, 0.3);
}

.action-btn-delete:hover {
    background: rgba(255, 107, 107, 0.3);
    border-color: #ff6b6b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
}

.action-btn-delete i {
    color: #ff6b6b;
}

/* Кнопка отказа */
.action-btn-reject {
    background: rgba(255, 107, 107, 0.15);
    border-color: rgba(255, 107, 107, 0.3);
}

.action-btn-reject:hover {
    background: rgba(255, 107, 107, 0.3);
    border-color: #ff6b6b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
}

.action-btn-reject i {
    color: #ff6b6b;
}

/* Модальное окно */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-box {
    background: #1a1f2e;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    padding: 24px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 18px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 12px 0;
}

.modal-title i {
    color: #ff6b6b;
    font-size: 24px;
}

.modal-desc {
    font-size: 14px;
    color: #8892a6;
    margin: 0 0 24px 0;
    line-height: 1.5;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.modal-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-btn-cancel {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
}

.modal-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.2);
}

.modal-btn-delete {
    background: #ff6b6b;
    color: #fff;
}

.modal-btn-delete:hover {
    background: #ff5252;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 107, 107, 0.4);
}

/* Alpine.js x-cloak */
[x-cloak] {
    display: none !important;
}
            </style>
            <div class="action-btns">
                {{-- Кнопка ОТКАЗА (видна только получателю, если документ не отклонён и не завершён) --}}
                @php
                $canReject = (
                ((int)auth()->id() === (int)$document->receiver_id || auth()->user()->isAdmin())
                && !in_array(strtolower($document->status), ['rejected', 'completed', 'approved'])
                );
                @endphp

                @if($canReject)
                <div x-data="{ openReject: false }" style="position:relative;display:inline-block;">
                    <button @click="openReject = true" type="button" class="action-btn action-btn-reject" title="Отказ">
                        <i class="bi bi-x-circle"></i>
                    </button>

                    <div x-show="openReject"
                         class="modal-overlay"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-end="opacity-0"
                         x-cloak>

                        <div @click.away="openReject = false"
                             class="modal-box modal-reject"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-end="opacity-0 scale-95">

                            <h3 class="modal-title">
                                <i class="bi bi-x-circle-fill"></i>
                                <span data-i18n="reject_confirm_title">Отклонить документ?</span>
                            </h3>

                            <p class="modal-desc" data-i18n="reject_confirm_desc">
                                Укажите причину отказа. Это действие нельзя будет отменить.
                            </p>

                            <form action="{{ route('documents.reject', $document->id) }}" method="POST" id="reject-form">
                                @csrf

                                <div class="reject-hint">
                                    <i class="bi bi-info-circle-fill"></i>
                                    <span data-i18n="reject_hint">Обязательно укажите причину отказа</span>
                                </div>

                                <textarea
                                        name="reject_reason"
                                        class="reject-textarea"
                                        required
                                        minlength="5"
                                        maxlength="1000"
                                        data-i18n-placeholder="reject_reason_placeholder"
                                        placeholder="Опишите причину отказа..."
                                ></textarea>

                                <div class="modal-actions">
                                    <button @click="openReject = false" type="button" class="modal-btn modal-btn-cancel" data-i18n="cancel">
                                        Отмена
                                    </button>
                                    <button type="submit" class="modal-btn modal-btn-reject" data-i18n="reject_btn">
                                        Отклонить
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Кнопка удаления --}}
                <div x-data="{ open: false }" style="position:relative;display:inline-block;">
                    <button @click="open = true" type="button" class="action-btn action-btn-delete" title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>

                    <div x-show="open"
                         class="modal-overlay"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-end="opacity-0"
                         x-cloak>

                        <div @click.away="open = false"
                             class="modal-box"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:leave="transition ease-in duration-200 transform"
                             x-transition:leave-end="opacity-0 scale-95">

                            <h3 class="modal-title">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span data-i18n="delete_confirm_title">Удалить?</span>
                            </h3>

                            <p class="modal-desc" data-i18n="delete_confirm_desc">
                                Вы уверены, что хотите удалить этот документ? Это действие невозможно будет отменить.
                            </p>

                            <div class="modal-actions">
                                <button @click="open = false" type="button" class="modal-btn modal-btn-cancel" data-i18n="cancel">
                                    Отмена
                                </button>

                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="modal-btn modal-btn-delete" data-i18n="delete_btn">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Кнопка редактирования --}}
                <a href="{{ route('documents.edit', $document->id) }}" class="action-btn action-btn-edit" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </a>
            </div>
        </div>

        {{-- Основной контент --}}
        <div class="content-grid">

            {{-- Левая колонка --}}
            <div style="display:flex;flex-direction:column;gap:16px;">

                {{-- Карточка документа --}}
                <div class="doc-card">
                    <div class="doc-badges">
                        @if($document->type)
                        <span class="badge badge-type">{{ $document->type }}</span>
                        @else
                        <span class="badge badge-type" data-i18n="general">Общий</span>
                        @endif
                        <span class="badge badge-id">#{{ $document->id }}</span>
                        @if($document->number)
                        <span class="badge badge-number">{{ $document->number }}</span>
                        @else
                        <span class="badge badge-number" data-i18n="noNumber">Б/Н</span>
                        @endif
                    </div>

                    <h1 class="doc-title">{{ $document->title }}</h1>

                    <div class="doc-content">
                        {{ $document->content ?? 'No detailed description available.' }}
                    </div>
                </div>

                {{-- Файл --}}
                @if($document->file_path)
                @php
                $extension = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                if ($extension === 'docx' || $extension === 'doc') {
                $iconClass = 'file-icon-word';
                $biIcon = 'bi-file-earmark-word-fill';
                } elseif ($extension === 'xlsx' || $extension === 'xls') {
                $iconClass = 'file-icon-excel';
                $biIcon = 'bi-file-earmark-excel-fill';
                } elseif ($extension === 'rtf') {
                $iconClass = 'file-icon-rtf';
                $biIcon = 'bi-file-earmark-richtext-fill';
                } else {
                $iconClass = 'file-icon-pdf';
                $biIcon = 'bi-file-earmark-pdf-fill';
                }
                $isPdf = $extension === 'pdf';
                @endphp

                <div class="file-card">
                    <div class="file-info">
                        <div class="file-icon {{ $iconClass }}">
                            <i class="bi {{ $biIcon }}"></i>
                        </div>
                        <div style="overflow:hidden;">
                            <p class="file-name">{{ basename($document->file_path) }}</p>
                            <div class="file-meta">
                                <span>{{ strtoupper($extension) }}</span>
                                <span class="dot"></span>
                                <span data-i18n="readyView">Готов к просмотру</span>
                            </div>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="{{ asset('storage/' . $document->file_path) }}" @if($isPdf) target="_blank" @endif
                           class="file-btn file-btn-view">
                            <i class="bi bi-eye-fill"></i>
                            <span data-i18n="viewBtn">Смотреть</span>
                        </a>
                        <a href="{{ asset('storage/' . $document->file_path) }}"
                           download="{{ $document->title }}.{{ $extension }}"
                           class="file-btn file-btn-download">
                            <i class="bi bi-download"></i>
                            <span data-i18n="downloadBtn">Скачать</span>
                        </a>
                    </div>
                </div>
                @endif

                {{-- Комментарии --}}
                <div class="comments-section">
                    <div class="section-title">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span data-i18n="systemNotes">Системные заметки</span>
                    </div>

                    <form action="{{ route('comments.store') }}" method="POST" class="comment-form">
                        @csrf
                        <input type="hidden" name="document_id" value="{{ $document->id }}">
                        <textarea name="comment" rows="2" class="comment-input"
                                  data-i18n-placeholder="commentPlaceholder"
                                  placeholder="Оставьте комментарий..."></textarea>
                        <button type="submit" class="comment-submit">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>

                    <div class="comments-list">
                        @forelse($comments ?? [] as $comment)
                        <div class="comment-item">
                            <div class="comment-header">
                                <span class="comment-author">{{ $comment->user?->name ?? 'System' }}</span>
                                <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->comment }}</p>

                            @if(auth()->id() === $document->user_id)
                            <div class="comment-delete">
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="comment-delete-btn">
                                        <i class="bi bi-trash3"></i>
                                        <span data-i18n="delete">Удалить</span>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                        @empty
                        <div class="comments-empty">
                            <i class="bi bi-chat-dots"></i>
                            <p data-i18n="noNotes">Заметок пока нет</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Правая колонка (сайдбар) --}}
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div class="sidebar-card">
                    <div class="sidebar-title" data-i18n="details">Детали</div>

                    <div class="sidebar-row">
                        <span class="sidebar-label" data-i18n="signature">Подпись</span>
                        @php
                        $status = strtolower($document->status);
                        $isFullySigned = in_array($status, ['completed', 'approved']);
                        $hasAnySignature = $document->signatures->count() > 0;
                        @endphp

                        @if($isFullySigned)
                        <span class="signature-badge signature-signed">
                                <i class="bi bi-check-all"></i>
                                <span data-i18n="signed">Подписан</span>
                            </span>
                        @elseif($hasAnySignature)
                        <span class="signature-badge signature-processing">
                                <i class="bi bi-pen-fill"></i>
                                <span data-i18n="processing">В обработке</span>
                            </span>
                        @else
                        <span class="signature-badge signature-not-signed">
                                <i class="bi bi-clock"></i>
                                <span data-i18n="notSigned">Не подписан</span>
                            </span>
                        @endif
                    </div>

                    <div class="sidebar-row">
                        <span class="sidebar-label" data-i18n="status">Статус</span>
                        @php
                        $statusClass = match(strtolower($document->status)) {
                        'draft' => 'status-draft',
                        'active' => 'status-active',
                        'completed', 'approved' => 'status-completed',
                        'rejected' => 'status-rejected',
                        default => 'status-draft',
                        };
                        @endphp
                        <span class="status-badge {{ $statusClass }}" data-status="{{ strtolower($document->status) }}">
                            {{ $document->status ?? 'Draft' }}
                        </span>
                    </div>

                    <div class="sidebar-row">
                        <span class="sidebar-label" data-i18n="deadline">Срок</span>
                        <span class="sidebar-value sidebar-value-deadline">
                            {{ $document->deadline ? \Carbon\Carbon::parse($document->deadline)->format('d.m.Y') : '—' }}
                        </span>
                    </div>

                    <div style="padding-top:12px;margin-top:12px;border-top:1px solid rgba(255,255,255,0.06);display:flex;flex-direction:column;gap:8px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="font-size:9px;color:#8892a6;text-transform:uppercase;letter-spacing:0.5px;" data-i18n="createdAt">Создан</span>
                            <span style="font-size:10px;color:#c5cdd9;">{{ $document->created_at?->format('d M Y') ?? '—' }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="font-size:9px;color:#8892a6;text-transform:uppercase;letter-spacing:0.5px;" data-i18n="lastUpdate">Обновлено</span>
                            <span style="font-size:10px;color:#c5cdd9;">{{ $document->updated_at?->diffForHumans() ?? '—' }}</span>
                        </div>
                    </div>

                    {{-- БЛОК УЧАСТНИКОВ --}}
                    <div class="participants-section">
                        <div class="participants-title">
                            <i class="bi bi-people-fill"></i>
                            <span data-i18n="participants">Участники</span>
                        </div>

                        {{-- Владелец (отправитель) --}}
                        <div class="participant-item">
                            <div class="participant-avatar participant-avatar-owner">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="participant-info">
                                <div class="participant-role participant-role-owner" data-i18n="owner">Владелец</div>
                                <div class="participant-name">
                                    {{ $document->user?->name ?? '—' }}
                                </div>
                            </div>
                        </div>

                        {{-- Получатель --}}
                        <div class="participant-item">
                            <div class="participant-avatar participant-avatar-receiver">
                                <i class="bi bi-person-check-fill"></i>
                            </div>
                            <div class="participant-info">
                                <div class="participant-role participant-role-receiver" data-i18n="receiver">Получатель</div>
                                <div class="participant-name">
                                    @if($document->receiver)
                                    {{ $document->receiver->name }}
                                    @else
                                    <span class="participant-name-empty" data-i18n="notAssigned">Не назначен</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="live-indicator">
                    <div class="live-dot"></div>
                    <span class="live-text" data-i18n="liveDoc">Живой документ</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ ПРОСМОТРА ДОКУМЕНТА
        // (дополняет глобальный TRANSLATIONS из layouts/admin.blade.php)
        // ============================================================
        const DOC_SHOW_TRANSLATIONS = {
            ru: {
                errorTitle: 'Ошибка доступа',
                back: 'Назад',
                edit: 'Редактировать',
                delete: 'Удалить',
                readyView: 'Готов к просмотру',
                viewBtn: 'Смотреть',
                downloadBtn: 'Скачать',
                systemNotes: 'Системные заметки',
                commentPlaceholder: 'Оставьте комментарий...',
                noNotes: 'Заметок пока нет',
                details: 'Детали',
                signature: 'Подпись',
                signed: 'Подписан',
                notSigned: 'Не подписан',
                processing: 'В обработке',
                status: 'Статус',
                owner: 'Владелец',
                receiver: 'Получатель',
                participants: 'Участники',
                notAssigned: 'Не назначен',
                deadline: 'Срок',
                createdAt: 'Создан',
                lastUpdate: 'Обновлено',
                liveDoc: 'Живой документ',
                draft: 'Черновик',
                active: 'Активен',
                approved: 'Утвержден',
                completed: 'Завершен',
                rejected: 'Отклонён',
                delete_confirm_title: 'Удалить?',
                delete_confirm_desc: 'Вы уверены, что хотите удалить этот документ? Это действие невозможно будет отменить.',
                cancel: 'Отмена',
                delete_btn: 'Удалить',
                general: 'Общий',
                noNumber: 'Б/Н',
                reject_confirm_title: 'Отклонить документ?',
                reject_confirm_desc: 'Укажите причину отказа. Это действие нельзя будет отменить.',
                reject_hint: 'Обязательно укажите причину отказа',
                reject_reason_placeholder: 'Опишите причину отказа...',
                reject_btn: 'Отклонить'
            },
            tj: {
                errorTitle: 'Хатои дастрасӣ',
                back: 'Қафо',
                edit: 'Вироиш',
                delete: 'Ҳазф кардан',
                readyView: 'Барои тамошо омода',
                viewBtn: 'Дидан',
                downloadBtn: 'Боргирӣ',
                systemNotes: 'Қайдҳои система',
                commentPlaceholder: 'Фикр гузоред...',
                noNotes: 'Қайдҳо нестанд',
                details: 'Тафсилот',
                signature: 'Имзо',
                signed: 'Имзошуда',
                notSigned: 'Имзо нашудааст',
                processing: 'Дар баррасӣ',
                status: 'Ҳолат',
                owner: 'Соҳиб',
                receiver: 'Гиранда',
                participants: 'Иштирокчиён',
                notAssigned: 'Таъин нашудааст',
                deadline: 'Мӯҳлат',
                createdAt: 'Санаи эҷод',
                lastUpdate: 'Навсозӣ',
                liveDoc: 'Ҳуҷҷати фаъол',
                draft: 'Пешнавис',
                active: 'Фаъол',
                approved: 'Тасдиқшуда',
                completed: 'Иҷрошуда',
                rejected: 'Рад шуд',
                delete_confirm_title: 'Нест кардан?',
                delete_confirm_desc: 'Шумо мутмаин ҳастед, ки ин ҳуҷҷатро нест кардан мехоҳед? Ин амалро бекор кардан ғайриимкон аст.',
                cancel: 'Лағв',
                delete_btn: 'Нест кардан',
                general: 'Умумӣ',
                noNumber: 'Б/Р',
                reject_confirm_title: 'Ҳуҷҷатро рад кардан?',
                reject_confirm_desc: 'Сабаби радшавиро нишон диҳед. Ин амалро бекор кардан ғайриимкон аст.',
                reject_hint: 'Ҳатман сабаби радшавиро нишон диҳед',
                reject_reason_placeholder: 'Сабаби радшавиро тавсиф диҳед...',
                reject_btn: 'Рад кардан'
            },
            en: {
                errorTitle: 'Access Error',
                back: 'Back',
                edit: 'Edit',
                delete: 'Delete',
                readyView: 'Ready to View',
                viewBtn: 'View',
                downloadBtn: 'Download',
                systemNotes: 'System Notes',
                commentPlaceholder: 'Leave a comment...',
                noNotes: 'No notes yet',
                details: 'Details',
                signature: 'Signature',
                signed: 'Signed',
                notSigned: 'Not Signed',
                processing: 'Processing',
                status: 'Status',
                owner: 'Owner',
                receiver: 'Recipient',
                participants: 'Participants',
                notAssigned: 'Not Assigned',
                deadline: 'Deadline',
                createdAt: 'Created At',
                lastUpdate: 'Last Update',
                liveDoc: 'Live Document',
                draft: 'Draft',
                active: 'Active',
                approved: 'Approved',
                completed: 'Completed',
                rejected: 'Rejected',
                delete_confirm_title: 'Delete?',
                delete_confirm_desc: 'Are you sure you want to delete this document? This action cannot be undone.',
                cancel: 'Cancel',
                delete_btn: 'Delete',
                general: 'General',
                noNumber: 'W/N',
                reject_confirm_title: 'Reject document?',
                reject_confirm_desc: 'Specify the reason for rejection. This action cannot be undone.',
                reject_hint: 'You must specify a reason for rejection',
                reject_reason_placeholder: 'Describe the reason for rejection...',
                reject_btn: 'Reject'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ НА ЭТОЙ СТРАНИЦЕ
        // ============================================================
        function applyDocShowTranslations(lang) {
            const dict = DOC_SHOW_TRANSLATIONS[lang] || DOC_SHOW_TRANSLATIONS.ru;

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

            // 4) Переводим СТАТУС документа через data-status
            document.querySelectorAll('.status-badge[data-status]').forEach(el => {
                const statusKey = el.getAttribute('data-status');
                if (dict[statusKey]) el.textContent = dict[statusKey];
            });

            // 5) Переводим текст ошибки сессии (если есть ключ в словаре)
            const errorTextEl = document.getElementById('session-error-text');
            if (errorTextEl) {
                const rawError = errorTextEl.getAttribute('data-error-raw');
                if (rawError) {
                    // Сначала ищем точное совпадение ключа
                    if (dict[rawError]) {
                        errorTextEl.textContent = dict[rawError];
                    } else {
                        // Иначе показываем оригинальный текст
                        errorTextEl.textContent = rawError;
                    }
                }
            }
        }

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applyDocShowTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        //    (когда юзер кликает на 🇷🇺/🇹🇯/🇬🇧 в админке)
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applyDocShowTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applyDocShowTranslations(e.newValue);
            }
        });
    });
</script>
@endsection