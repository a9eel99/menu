<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->secondary_color ?? '#1a1a2e' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $restaurant->name_ar }} - قائمة الطعام</title>

    @if($restaurant->logo)
    <link rel="icon" href="{{ asset('storage/' . $restaurant->logo) }}" type="image/png">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @php
        $primaryColor = $restaurant->primary_color ?? '#FF6B35';
        $secondaryColor = $restaurant->secondary_color ?? '#1a1a2e';
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --bg-dark: #0a0a0f;
            --bg-card: #12121a;
            --text: #ffffff;
            --text-muted: #94a3b8;
            --border: rgba(255,255,255,0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            display: flex;
            flex-direction: column;
        }

        .header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: cover;
        }

        .brand-name {
            font-size: 0.95rem;
            font-weight: 700;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--primary);
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
        }

        .pdf-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pdf-frame {
            flex: 1;
            width: 100%;
            border: none;
            background: white;
        }

        .download-bar {
            background: var(--bg-card);
            padding: 12px 16px;
            display: flex;
            justify-content: center;
            gap: 12px;
            border-top: 1px solid var(--border);
        }

        .download-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 12px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .download-btn.secondary {
            background: rgba(255,255,255,0.1);
        }

        @media (max-width: 640px) {
            .header { padding: 10px 12px; }
            .back-btn, .action-btn { width: 36px; height: 36px; }
            .brand-name { font-size: 0.85rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-right">
            <a href="{{ route('menu.landing', $restaurant->slug) }}" class="back-btn">
                <i class="fas fa-arrow-right"></i>
            </a>
            <div class="brand">
                @if($restaurant->getLogoUrl())
                    <img src="{{ $restaurant->getLogoUrl() }}" alt="{{ $restaurant->name_ar }}" class="brand-logo">
                @endif
                <div>
                    <div class="brand-name">{{ $restaurant->name_ar }}</div>
                    <div class="brand-subtitle">قائمة الطعام</div>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ $restaurant->getMenuPdfUrl() }}" target="_blank" class="action-btn" title="فتح في نافذة جديدة">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    </header>

    <div class="pdf-container">
        <iframe src="{{ $restaurant->getMenuPdfUrl() }}" class="pdf-frame"></iframe>
    </div>

    <div class="download-bar">
        <a href="{{ $restaurant->getMenuPdfUrl() }}" download class="download-btn">
            <i class="fas fa-download"></i>
            تحميل القائمة
        </a>
        <a href="https://wa.me/?text={{ urlencode($restaurant->name_ar . ' - قائمة الطعام: ' . route('menu.show', $restaurant->slug)) }}" target="_blank" class="download-btn secondary">
            <i class="fab fa-whatsapp"></i>
            مشاركة
        </a>
    </div>
</body>
</html>
