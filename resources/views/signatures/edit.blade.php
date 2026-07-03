@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 min-h-screen">
    @php
    $extension = strtolower(pathinfo($signature->document->file_path, PATHINFO_EXTENSION));

    // Расширенная проверка форматов
    $isWord = in_array($extension, ['doc', 'docx']);
    $isExcel = in_array($extension, ['xls', 'xlsx']);
    $isRtf = $extension === 'rtf';
    $isPdf = $extension === 'pdf';

    // Привязка уникального цвета темы под каждый формат
    if ($isWord) {
    $themeColor = '#2b579a'; // Синий Word
    $badgeClass = 'bg-blue-600';
    } elseif ($isExcel) {
    $themeColor = '#107c41'; // Зеленый Excel
    $badgeClass = 'bg-emerald-600';
    } elseif ($isRtf) {
    $themeColor = '#7c3aed'; // Фиолетовый RTF
    $badgeClass = 'bg-purple-600';
    } else {
    $themeColor = '#6366f1'; // Индиго по умолчанию для PDF
    $badgeClass = 'bg-red-600';
    }

    // Динамическая локализация содержимого самого QR-кода на стороне бэкенда
    $doc = $signature->document;
    $senderName = $doc->sender->name ?? 'Система';
    $signerName = auth()->user()->name ?? 'Пользователь';
    $dateSent = $doc->created_at ? $doc->created_at->format('d.m.Y H:i') : date('d.m.Y H:i');
    $dateSigned = date('d.m.Y H:i');

    $qrText = __('Document') . ": {$doc->title}\n" .
    __('From') . ": {$senderName}\n" .
    __('Signer') . ": {$signerName}\n" .
    __('Date Sent') . ": {$dateSent}\n" .
    __('Date Signed') . ": {$dateSigned}";

    // URL для рендеринга картинки на фронтенде
    $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrText);
    @endphp

    <style>
        .edit-sig-page {
            --primary-color: {{ $themeColor }};
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .navbar-style-text, .theme-heading, label, button, .btn-update {
            font-weight: 700 !important;
            letter-spacing: -0.02em !important;
            text-transform: none;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.96) !important;
            backdrop-filter: blur(14px);
            border-radius: 2rem;
            border: 2px solid rgba(0, 0, 0, 0.12);
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .dark .form-card { background: #1e293b !important; border-color: rgba(255,255,255,0.12); }

        .pad-container {
            background-color: rgba(0,0,0,0.02) !important;
            border: 2px dashed {{ $themeColor }};
            transition: all .3s ease;
        }
        .dark .pad-container { background-color: rgba(255,255,255,0.02) !important; }

        .btn-update {
            background: {{ $themeColor }};
            color: #ffffff !important;
            font-weight: 900 !important;
            text-transform: uppercase;
            letter-spacing: .08em !important;
            border: 2px solid rgba(255,255,255,.08);
            box-shadow: 0 10px 25px rgba(0,0,0,.15);
            transition: .25s ease;
        }

        .btn-update:hover { transform: translateY(-2px); opacity: 0.9; }

        .old-sig-display {
            background: rgba(0,0,0,0.02);
            border: 2px solid rgba(0,0,0,0.05);
            border-radius: 1.25rem;
            padding: 1.4rem;
        }

        .format-badge {
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 900;
            color: white;
            text-transform: uppercase;
            margin-left: 10px;
        }
    </style>

    <div class="edit-sig-page">
        <div class="mb-7 flex items-center justify-between">
            <div>
                <a href="{{ route('signatures.show', $signature->id) }}"
                   class="text-[11px] font-black uppercase tracking-[0.18em] text-indigo-500 flex items-center gap-2 mb-2 hover:gap-3 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span data-i18n="backBtn">Назад</span>
                </a>

                <div class="flex items-center">
                    <h1 class="text-3xl navbar-style-text theme-heading" data-i18n="pageTitle">Обновление QR-защиты</h1>
                    <span class="format-badge {{ $badgeClass }}">
                                    {{ $extension }}
                                </span>
                </div>
            </div>
        </div>

        <div class="max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- ЛЕВАЯ КОЛОНКА (ФОРМА) --}}
            <div class="form-card">
                <form method="POST" action="{{ route('signatures.update', $signature->id) }}" id="signatureForm" class="p-7 space-y-7">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="qr_payload" id="qrPayloadInput" value="{{ $qrText }}">

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 block mb-2" data-i18n="labelDoc">Документ</label>
                        <div class="text-lg navbar-style-text text-slate-800 dark:text-white py-3 border-b-2 border-indigo-500/20 truncate">
                            {{ $signature->document->title }}
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-black uppercase tracking-widest text-indigo-500 block mb-3" data-i18n="labelNewSig">Новый QR-код Верификации</label>

                        <div class="pad-container rounded-[1.7rem] p-6 flex flex-col items-center justify-center min-h-52">
                            <div class="bg-white p-3 rounded-2xl shadow-md border border-slate-100 flex items-center justify-center">
                                <img src="{{ $qrUrl }}" alt="New QR Verification" class="w-[120px] h-[120px] object-contain block">
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400 mt-3 block text-center">Verified DocSign</span>
                        </div>
                    </div>

                    {{-- Компактная кнопка отправки --}}
                    <div class="flex justify-center pt-2">
                        <button type="submit" class="btn-update px-5 py-2.5 rounded-xl text-[12px] font-bold transition-all active:scale-95">
                            <span data-i18n="submitBtn">Обновить документ</span> ({{ strtoupper($extension) }})
                        </button>
                    </div>
                </form>
            </div>

            {{-- ПРАВАЯ КОЛОНКА (ИНФОРМАЦИЯ) --}}
            <div class="space-y-6">
                <div class="form-card p-7">
                    <label class="text-[11px] font-black uppercase tracking-widest text-slate-400 mb-5 block text-center" data-i18n="labelCurrent">Предыдущий QR Штамп</label>
                    <div class="relative old-sig-display flex items-center justify-center bg-slate-50 dark:bg-slate-900 rounded-2xl p-4">
                        @if($signature->signature && Storage::disk('public')->exists($signature->signature))
                        <img src="{{ asset('storage/' . $signature->signature) }}" class="max-h-32 object-contain rounded-xl" alt="Current QR Signature">
                        @else
                        <div class="text-center py-4 text-slate-400 text-xs font-semibold uppercase tracking-wider" data-i18n="noStamp">
                            Файл штампа не найден
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Блок "Внимание" с адаптивным текстом под формат --}}
                <div class="bg-red-600 rounded-[2.2rem] p-7 text-white shadow-2xl relative overflow-hidden border-[2px] border-white/15">
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-[10px] font-black uppercase tracking-[0.22em] text-red-100" data-i18n="infoTitle">Внимание</h4>
                        </div>
                        <p class="text-[12px] font-medium leading-relaxed opacity-90" id="infoTextContainer">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ЛОКАЛЬНЫЙ СЛОВАРЬ СТРАНИЦЫ РЕДАКТИРОВАНИЯ ПОДПИСИ
        // ============================================================
        const SIGN_EDIT_TRANSLATIONS = {
            ru: {
                backBtn: 'Назад',
                pageTitle: 'Обновление QR-защиты',
                labelDoc: 'Документ',
                labelNewSig: 'Новый QR-код Верификации',
                submitBtn: 'Обновить документ',
                labelCurrent: 'Предыдущий QR Штамп',
                noStamp: 'Файл штампа не найден',
                infoTitle: 'Внимание',
                infoTextPdf: 'При обновлении штампа система полностью перегенерирует документ. Текущий QR-код будет замещён актуальной версией, а старый файл удалён из системы для оптимизации памяти.',
                infoTextWord: 'Для документов Word (.docx) штамп защиты перезапишется в структуре файла. Убедитесь, что исходный макет не содержит конфликтов.',
                infoTextExcel: 'Для таблиц Excel (.xlsx) защитный QR-код будет обновлен непосредственно внутри структуры листа метаданных без потери ваших формул.',
                infoTextRtf: 'Для документов формата RTF структура разметки будет перекомпилирована с интеграцией нового бинарного контейнера штампа.'
            },
            tj: {
                backBtn: 'Бозгашт',
                pageTitle: 'Навсозии муҳофизати QR',
                labelDoc: 'Ҳуҷҷат',
                labelNewSig: 'QR-коди нави тасдиқкунанда',
                submitBtn: 'Навсозии ҳуҷҷат',
                labelCurrent: 'Муҳри QR-и қаблӣ',
                noStamp: 'Файли муҳр ёфт нашуд',
                infoTitle: 'Диққат',
                infoTextPdf: 'Ҳангоми навсозии муҳр система ҳуҷҷати комилан аз нав месозад. QR-коди ҷорӣ бо нусхаи нав иваз карда шуда, файли кӯҳна барои сарфаи хотира нест карда мешавад.',
                infoTextWord: 'Барои ҳуҷҷатҳои Word (.docx) муҳри муҳофизатӣ дар сохтори файл аз нав навишта мешавад. Боварӣ ҳосил кунед, ки формати файл дуруст аст.',
                infoTextExcel: 'Барои ҷадвалҳои Excel (.xlsx) коди муҳофизатии QR бевосита дар дохили сохтори варақ бидуни вайрон кардани формулаҳо нав карда мешавад.',
                infoTextRtf: 'Барои ҳуҷҷатҳои формати RTF сохтори маркап аз нав компилятсия шуда, контейнери бинарии нав ворид карда мешавад.'
            },
            en: {
                backBtn: 'Back',
                pageTitle: 'Update QR Protection',
                labelDoc: 'Document',
                labelNewSig: 'New QR Verification Code',
                submitBtn: 'Update Document',
                labelCurrent: 'Previous QR Stamp',
                noStamp: 'Stamp file not found',
                infoTitle: 'Attention',
                infoTextPdf: 'When updating the stamp, the system completely regenerates document. The current QR code will be replaced with the updated version, and the old file will be deleted to optimize storage.',
                infoTextWord: 'For Word documents (.docx), the protection stamp will be overwritten within the file structure. Please ensure the file format is valid.',
                infoTextExcel: 'For Excel spreadsheets (.xlsx), the secure QR code will be updated right inside the sheet structure without breaking formulas.',
                infoTextRtf: 'For RTF files, the layout markup will be recompiled to natively integrate the new stamp binary container.'
            }
        };

        // ============================================================
        // ФУНКЦИЯ ПРИМЕНЕНИЯ ПЕРЕВОДОВ
        // ============================================================
        function applySignatureEditTranslations(lang) {
            const dict = SIGN_EDIT_TRANSLATIONS[lang] || SIGN_EDIT_TRANSLATIONS.ru;

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

            // 4) Обновляем динамический infoTextContainer в зависимости от типа документа
            updateInfoText(dict);
        }

        // ============================================================
        // ФУНКЦИЯ ОБНОВЛЕНИЯ ИНФОРМАЦИОННОГО ТЕКСТА
        // ============================================================
        function updateInfoText(dict) {
            const ext = "{{ $extension }}";
            const infoContainer = document.getElementById('infoTextContainer');

            if (!infoContainer) return;

            if (['doc', 'docx'].includes(ext)) {
                infoContainer.textContent = dict.infoTextWord;
            } else if (['xls', 'xlsx'].includes(ext)) {
                infoContainer.textContent = dict.infoTextExcel;
            } else if (ext === 'rtf') {
                infoContainer.textContent = dict.infoTextRtf;
            } else {
                infoContainer.textContent = dict.infoTextPdf;
            }
        }

        // ============================================================
        // 1. Применяем сразу при загрузке
        // ============================================================
        const initialLang = localStorage.getItem('docsign_lang') || 'ru';
        applySignatureEditTranslations(initialLang);

        // ============================================================
        // 2. Слушаем событие смены языка от layouts/admin.blade.php
        // ============================================================
        window.addEventListener('docsign:lang-changed', (e) => {
            const lang = e.detail?.lang || 'ru';
            applySignatureEditTranslations(lang);
        });

        // ============================================================
        // 3. Синхронизация между вкладками браузера
        // ============================================================
        window.addEventListener('storage', (e) => {
            if (e.key === 'docsign_lang' && e.newValue) {
                applySignatureEditTranslations(e.newValue);
            }
        });
    });
</script>
@endsection
