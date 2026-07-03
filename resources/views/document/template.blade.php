<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Документ' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20mm 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .header h1 {
            font-size: 18pt;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 10pt;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .meta-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            font-size: 10pt;
        }

        .meta-row {
            display: table-row;
        }

        .meta-label {
            display: table-cell;
            width: 30%;
            padding: 5px 10px 5px 0;
            font-weight: bold;
            color: #555;
        }

        .meta-value {
            display: table-cell;
            padding: 5px 0;
        }

        .document-number {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
        }

        .content {
            text-align: justify;
            margin-bottom: 30px;
        }

        .content p {
            margin-bottom: 12px;
            text-indent: 20px;
        }

        .content h2 {
            font-size: 13pt;
            color: #2c3e50;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }

        .content h3 {
            font-size: 12pt;
            color: #34495e;
            margin: 15px 0 8px 0;
            font-weight: bold;
        }

        .content ol, .content ul {
            margin: 10px 0 10px 30px;
        }

        .content li {
            margin-bottom: 8px;
        }

        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .signatures {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .signature-block {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .signature-party {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .signature-details {
            font-size: 9pt;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 9pt;
            color: #666;
        }

        .signature-line-content {
            display: table;
            width: 100%;
        }

        .signature-line-left,
        .signature-line-right {
            display: table-cell;
            width: 50%;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #999;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .highlight {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 3px solid #3498db;
            margin: 15px 0;
        }

        .table-wrapper {
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }

        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<!-- Заголовок документа -->
<div class="header">
    <h1>{{ $documentType ?? 'Документ' }}</h1>
    @if(isset($documentNumber))
    <div class="document-number">№ {{ $documentNumber }}</div>
    @endif
    @if(isset($subtitle))
    <div class="subtitle">{{ $subtitle }}</div>
    @endif
</div>

<!-- Мета-информация -->
<div class="meta-info">
    @if(isset($date))
    <div class="meta-row">
        <div class="meta-label">Дата составления:</div>
        <div class="meta-value">{{ $date }}</div>
    </div>
    @endif

    @if(isset($recipient))
    <div class="meta-row">
        <div class="meta-label">Контрагент:</div>
        <div class="meta-value">{{ $recipient }}</div>
    </div>
    @endif

    @if(isset($details) && is_array($details))
    @foreach($details as $key => $value)
    <div class="meta-row">
        <div class="meta-label">{{ $key }}:</div>
        <div class="meta-value">{{ $value }}</div>
    </div>
    @endforeach
    @endif
</div>

<!-- Основное содержимое документа -->
<div class="content">
    {!! $content !!}
</div>

<!-- Подписи сторон -->
<div class="signature-section">
    <div class="signature-title">Подписи сторон</div>

    <div class="signatures">
        <!-- Исполнитель -->
        <div class="signature-block">
            <div class="signature-party">Исполнитель:</div>
            <div class="signature-details">
                ООО "Ваша Компания"<br>
                ИНН: 1234567890<br>
                КПП: 123456789<br>
                Адрес: г. Москва, ул. Примерная, д. 1
            </div>
            <div class="signature-line">
                <div class="signature-line-content">
                    <div class="signature-line-left">_________________</div>
                    <div class="signature-line-right">/ ________________ /</div>
                </div>
                <div style="margin-top: 5px; font-size: 8pt; color: #999;">
                    <span style="display: inline-block; width: 48%;">подпись</span>
                    <span style="display: inline-block; width: 48%; text-align: center;">расшифровка</span>
                </div>
            </div>
        </div>

        <!-- Заказчик -->
        <div class="signature-block">
            <div class="signature-party">Заказчик:</div>
            <div class="signature-details">
                {{ $recipient ?? '_________________________' }}<br>
                ИНН: _______________<br>
                КПП: _______________<br>
                Адрес: _______________
            </div>
            <div class="signature-line">
                <div class="signature-line-content">
                    <div class="signature-line-left">_________________</div>
                    <div class="signature-line-right">/ ________________ /</div>
                </div>
                <div style="margin-top: 5px; font-size: 8pt; color: #999;">
                    <span style="display: inline-block; width: 48%;">подпись</span>
                    <span style="display: inline-block; width: 48%; text-align: center;">расшифровка</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подвал документа -->
<div class="footer">
    Документ сгенерирован автоматически | {{ now()->format('d.m.Y H:i') }} | DocSign System
</div>
</body>
</html><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Документ' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
            padding: 20mm 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c3e50;
        }

        .header h1 {
            font-size: 18pt;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 10pt;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .meta-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            font-size: 10pt;
        }

        .meta-row {
            display: table-row;
        }

        .meta-label {
            display: table-cell;
            width: 30%;
            padding: 5px 10px 5px 0;
            font-weight: bold;
            color: #555;
        }

        .meta-value {
            display: table-cell;
            padding: 5px 0;
        }

        .document-number {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
        }

        .content {
            text-align: justify;
            margin-bottom: 30px;
        }

        .content p {
            margin-bottom: 12px;
            text-indent: 20px;
        }

        .content h2 {
            font-size: 13pt;
            color: #2c3e50;
            margin: 20px 0 10px 0;
            font-weight: bold;
        }

        .content h3 {
            font-size: 12pt;
            color: #34495e;
            margin: 15px 0 8px 0;
            font-weight: bold;
        }

        .content ol, .content ul {
            margin: 10px 0 10px 30px;
        }

        .content li {
            margin-bottom: 8px;
        }

        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .signature-title {
            font-size: 12pt;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .signatures {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .signature-block {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .signature-party {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .signature-details {
            font-size: 9pt;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 9pt;
            color: #666;
        }

        .signature-line-content {
            display: table;
            width: 100%;
        }

        .signature-line-left,
        .signature-line-right {
            display: table-cell;
            width: 50%;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #999;
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }

        .highlight {
            background-color: #f8f9fa;
            padding: 10px;
            border-left: 3px solid #3498db;
            margin: 15px 0;
        }

        .table-wrapper {
            margin: 15px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        table th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
        }

        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<!-- Заголовок документа -->
<div class="header">
    <h1>{{ $documentType ?? 'Документ' }}</h1>
    @if(isset($documentNumber))
    <div class="document-number">№ {{ $documentNumber }}</div>
    @endif
    @if(isset($subtitle))
    <div class="subtitle">{{ $subtitle }}</div>
    @endif
</div>

<!-- Мета-информация -->
<div class="meta-info">
    @if(isset($date))
    <div class="meta-row">
        <div class="meta-label">Дата составления:</div>
        <div class="meta-value">{{ $date }}</div>
    </div>
    @endif

    @if(isset($recipient))
    <div class="meta-row">
        <div class="meta-label">Контрагент:</div>
        <div class="meta-value">{{ $recipient }}</div>
    </div>
    @endif

    @if(isset($details) && is_array($details))
    @foreach($details as $key => $value)
    <div class="meta-row">
        <div class="meta-label">{{ $key }}:</div>
        <div class="meta-value">{{ $value }}</div>
    </div>
    @endforeach
    @endif
</div>

<!-- Основное содержимое документа -->
<div class="content">
    {!! $content !!}
</div>

<!-- Подписи сторон -->
<div class="signature-section">
    <div class="signature-title">Подписи сторон</div>

    <div class="signatures">
        <!-- Исполнитель -->
        <div class="signature-block">
            <div class="signature-party">Исполнитель:</div>
            <div class="signature-details">
                ООО "Ваша Компания"<br>
                ИНН: 1234567890<br>
                КПП: 123456789<br>
                Адрес: г. Москва, ул. Примерная, д. 1
            </div>
            <div class="signature-line">
                <div class="signature-line-content">
                    <div class="signature-line-left">_________________</div>
                    <div class="signature-line-right">/ ________________ /</div>
                </div>
                <div style="margin-top: 5px; font-size: 8pt; color: #999;">
                    <span style="display: inline-block; width: 48%;">подпись</span>
                    <span style="display: inline-block; width: 48%; text-align: center;">расшифровка</span>
                </div>
            </div>
        </div>

        <!-- Заказчик -->
        <div class="signature-block">
            <div class="signature-party">Заказчик:</div>
            <div class="signature-details">
                {{ $recipient ?? '_________________________' }}<br>
                ИНН: _______________<br>
                КПП: _______________<br>
                Адрес: _______________
            </div>
            <div class="signature-line">
                <div class="signature-line-content">
                    <div class="signature-line-left">_________________</div>
                    <div class="signature-line-right">/ ________________ /</div>
                </div>
                <div style="margin-top: 5px; font-size: 8pt; color: #999;">
                    <span style="display: inline-block; width: 48%;">подпись</span>
                    <span style="display: inline-block; width: 48%; text-align: center;">расшифровка</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Подвал документа -->
<div class="footer">
    Документ сгенерирован автоматически | {{ now()->format('d.m.Y H:i') }} | DocSign System
</div>
</body>
</html>