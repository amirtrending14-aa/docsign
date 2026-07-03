<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['title'] ?? 'Документ' }}</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #2D3748;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F8CFF;
            padding-bottom: 15px;
        }

        .title {
            font-size: 20pt;
            font-weight: bold;
            color: #1A365D;
            margin-bottom: 10px;
        }

        .meta {
            font-size: 9pt;
            color: #718096;
            font-style: italic;
        }

        .content {
            text-align: justify;
            line-height: 1.7;
        }

        .content p {
            margin-bottom: 12px;
            text-indent: 1.5em;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2D3748;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #CBD5E0;
            text-align: center;
            font-size: 8pt;
            color: #A0AEC0;
            font-style: italic;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="title">{{ $data['title'] ?? 'Документ' }}</div>
    <div class="meta">
        Номер: {{ $data['number'] ?? 'Б/Н' }} |
        Тип: {{ $data['type'] ?? 'Документ' }} |
        Дата: {{ date('d.m.Y') }}
    </div>
</div>

<div class="content">
    @php
    $content = $data['content'] ?? '';
    // Разбиваем на абзацы по двойному переносу
    $paragraphs = preg_split('/\n\s*\n/', $content);
    @endphp

    @foreach($paragraphs as $paragraph)
    @php
    $paragraph = trim($paragraph);
    if (empty($paragraph)) continue;

    // Определяем это заголовок раздела
    $isSectionTitle = preg_match('/^(Глава|Раздел|Пункт|Статья|Chapter|Section|Article)\s+\d/i', $paragraph) ||
    preg_match('/^[IVX]+\.\s+/i', $paragraph) ||
    preg_match('/^\d+\.\s+[А-ЯA-ZА-Я]/u', $paragraph);
    @endphp

    @if($isSectionTitle)
    <div class="section-title">{{ $paragraph }}</div>
    @else
    <p>{{ $paragraph }}</p>
    @endif
    @endforeach
</div>

<div class="footer">
    Документ сгенерирован с помощью ИИ в системе DocSign | {{ date('d.m.Y H:i:s') }}
</div>
</body>
</html>