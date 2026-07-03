<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Документ не найден - DocSign</title>
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
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            color: #4f8cff;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .error-title {
            font-size: 24px;
            font-weight: 600;
            color: #ef4444;
            margin-bottom: 15px;
        }

        .error-text {
            font-size: 16px;
            color: #94a3b8;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="logo">DOCSIGN</div>
        <div class="error-icon">❌</div>
        <div class="error-title">Документ не найден</div>
        <div class="error-text">
            Документ с таким кодом верификации не существует или был удален.
            <br><br>
            Пожалуйста, проверьте правильность QR-кода.
        </div>
    </div>
</div>
</body>
</html>