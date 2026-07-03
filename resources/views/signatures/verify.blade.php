<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Верификация документа - DocSign</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #06070b 0%, #24243e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #e2e8f0;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #4f8cff;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(34, 197, 94, 0.2);
            border: 2px solid #22c55e;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            color: #22c55e;
            margin-top: 15px;
        }

        .status-badge::before {
            content: "✓";
            font-size: 20px;
            font-weight: bold;
        }

        .info-section {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: rgba(79, 140, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 16px;
            color: #e2e8f0;
            font-weight: 500;
        }

        .document-title {
            font-size: 18px;
            font-weight: 600;
            color: #4f8cff;
            word-break: break-word;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-text {
            font-size: 14px;
            color: #94a3b8;
        }

        .verification-code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: #64748b;
            margin-top: 10px;
            word-break: break-all;
        }

        @media (max-width: 640px) {
            .card {
                padding: 25px;
            }

            .logo {
                font-size: 24px;
            }

            .info-row {
                gap: 10px;
            }

            .info-icon {
                width: 35px;
                height: 35px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <div class="logo">DOCSIGN</div>
            <div class="status-badge">Документ верифицирован</div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-icon">📄</div>
                <div class="info-content">
                    <div class="info-label">Название документа</div>
                    <div class="info-value document-title">{{ $document->title }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">👤</div>
                <div class="info-content">
                    <div class="info-label">Отправитель</div>
                    <div class="info-value">{{ $creator->name ?? 'Неизвестно' }}</div>
                    <div style="font-size: 14px; color: #94a3b8; margin-top: 3px;">
                        {{ $creator->email ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">✍️</div>
                <div class="info-content">
                    <div class="info-label">Подписант</div>
                    <div class="info-value">{{ $signer->name ?? 'Неизвестно' }}</div>
                    <div style="font-size: 14px; color: #94a3b8; margin-top: 3px;">
                        {{ $signer->email ?? '-' }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">📅</div>
                <div class="info-content">
                    <div class="info-label">Дата отправки</div>
                    <div class="info-value">
                        {{ $document->created_at ? $document->created_at->format('d.m.Y H:i') : 'Неизвестно' }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">✅</div>
                <div class="info-content">
                    <div class="info-label">Дата подписания</div>
                    <div class="info-value">
                        {{ $signature->signed_at ? $signature->signed_at->format('d.m.Y H:i:s') : 'Неизвестно' }}
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon">📊</div>
                <div class="info-content">
                    <div class="info-label">Статус документа</div>
                    <div class="info-value" style="color: #22c55e;">
                        @if($document->status === 'completed')
                        Завершен
                        @elseif($document->status === 'processing')
                        В процессе
                        @else
                        Ожидает подписи
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">
                Этот документ был подписан электронно через систему DocSign
            </div>
            <div class="verification-code">
                Код верификации: {{ $signature->verification_code }}
            </div>
        </div>
    </div>
</div>
</body>
</html>