<!DOCTYPE html>
<html lang="{{ $locale ?? 'ar' }}" dir="{{ ($locale ?? 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="#1a1a2e">
    <title>{{ $locale === 'ar' ? 'اختر المطعم' : 'Select Restaurant' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .selector-container {
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .header {
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            opacity: 0.7;
            font-size: 1rem;
        }

        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .restaurant-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 30px 20px;
            text-decoration: none;
            color: white;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .restaurant-card:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .restaurant-card:active {
            transform: scale(0.98);
        }

        .restaurant-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: white;
            margin: 0 auto 16px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .restaurant-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .restaurant-logo i {
            font-size: 2rem;
            color: #64748b;
        }

        .restaurant-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .restaurant-desc {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        .lang-switch {
            position: fixed;
            top: 20px;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 20px;
        }

        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 8px 14px;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .lang-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .restaurant-card {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }

        .restaurant-card:nth-child(1) { animation-delay: 0.1s; }
        .restaurant-card:nth-child(2) { animation-delay: 0.2s; }
        .restaurant-card:nth-child(3) { animation-delay: 0.3s; }
        .restaurant-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <div class="lang-switch">
        <form action="{{ route('menu.language', $restaurant->slug) }}" method="POST">
            @csrf
            <input type="hidden" name="locale" value="{{ $locale === 'ar' ? 'en' : 'ar' }}">
            <button type="submit" class="lang-btn">
                <i class="fas fa-globe"></i>
                {{ $locale === 'ar' ? 'English' : 'العربية' }}
            </button>
        </form>
    </div>

    <div class="selector-container">
        <div class="header">
            <h1>{{ $locale === 'ar' ? 'اختر المطعم' : 'Select Restaurant' }}</h1>
            <p>{{ $locale === 'ar' ? 'اضغط على المطعم لعرض قائمة الطعام' : 'Tap on a restaurant to view its menu' }}</p>
        </div>

        <div class="restaurants-grid">
            @foreach($linkedRestaurants as $linked)
            <a href="{{ route('menu.landing', $linked->slug) }}?direct=1" class="restaurant-card">
                <div class="restaurant-logo">
                    @if($linked->logo)
                        <img src="{{ asset('storage/' . $linked->logo) }}" alt="{{ $linked->getName($locale) }}">
                    @else
                        <i class="fas fa-utensils"></i>
                    @endif
                </div>
                <div class="restaurant-name">{{ $linked->getName($locale) }}</div>
                @if($linked->getDescription($locale))
                <div class="restaurant-desc">{{ Str::limit($linked->getDescription($locale), 30) }}</div>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</body>
</html>
