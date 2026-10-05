<!DOCTYPE html>
<html lang="{{ session('locale', 'ar') }}" dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->settings->primary_color ?? '#FF6B35' }}">
    <title>{{ $restaurant->getName() }} - {{ session('locale', 'ar') === 'ar' ? 'اختر الفرع' : 'Select Branch' }}</title>
    
    @include('menu.partials.tajawal', ['weights' => [400, 500, 700, 800]])
    @if($locale !== 'ar')
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    
    @php
        $settings = $restaurant->settings;
        $primaryColor = $settings->primary_color ?? '#FF6B35';
        $locale = session('locale', 'ar');
    @endphp
    
    <style>
        svg.icon { display: inline-block; height: 1em; vertical-align: -0.125em; overflow: visible; }

        :root {
            --primary: {{ $primaryColor }};
            --text: #1a1a2e;
            --text-light: #64748b;
            --bg: #fafbfc;
            --card: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: {{ $locale === 'ar' ? "'Tajawal'" : "'Poppins'" }}, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }
        
        .container {
            max-width: 500px;
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .logo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #ff8f6b);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        .logo-placeholder i, .logo-placeholder svg {
            font-size: 2.5rem;
            color: white;
        }
        
        .restaurant-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 10px;
        }
        
        .select-text {
            color: var(--text-light);
            font-size: 1rem;
        }
        
        .branches-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .branch-card {
            display: block;
            background: var(--card);
            border-radius: 20px;
            padding: 20px;
            text-decoration: none;
            color: var(--text);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .branch-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }
        
        .branch-card.main {
            border-color: var(--primary);
            background: linear-gradient(135deg, rgba(255,107,53,0.05), rgba(255,107,53,0.02));
        }
        
        .branch-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        
        .branch-name {
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .main-badge {
            background: var(--primary);
            color: white;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .branch-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .branch-info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: var(--text-light);
        }
        
        .branch-info-item i, .branch-info-item svg {
            width: 20px;
            color: var(--primary);
        }
        
        .arrow-icon {
            position: absolute;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            opacity: 0;
            transition: all 0.3s;
        }
        
        .branch-card {
            position: relative;
        }
        
        .branch-card:hover .arrow-icon {
            opacity: 1;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 15px;
        }
        
        .lang-switch {
            position: fixed;
            top: 20px;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 20px;
        }
        
        .lang-btn {
            background: white;
            border: none;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .lang-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <form action="{{ route('menu.language', $restaurant->slug) }}" method="POST" class="lang-switch">
        @csrf
        <input type="hidden" name="locale" value="{{ $locale === 'ar' ? 'en' : 'ar' }}">
        <button type="submit" class="lang-btn">
            {{ $locale === 'ar' ? 'EN' : 'عربي' }}
        </button>
    </form>
    
    <div class="container">
        <div class="header">
            @if($restaurant->getLogoUrl())
                <img src="{{ $restaurant->getLogoUrl() }}" alt="{{ $restaurant->getName($locale) }}" class="logo">
            @else
                <div class="logo-placeholder">
                    <x-icon name="utensils" />
                </div>
            @endif
            
            <h1 class="restaurant-name">{{ $restaurant->getName($locale) }}</h1>
            <p class="select-text">
                {{ $locale === 'ar' ? 'اختر الفرع' : 'Select a Branch' }}
            </p>
        </div>
        
        <div class="branches-list">
            @foreach($restaurant->activeBranches as $branch)
                <a href="{{ route('menu.landing', $branch->slug) }}" class="branch-card {{ $branch->is_main ? 'main' : '' }}">
                    <div class="branch-header">
                        <span class="branch-name">{{ $branch->getName($locale) }}</span>
                        @if($branch->is_main)
                            <span class="main-badge">
                                {{ $locale === 'ar' ? 'الرئيسي' : 'Main' }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="branch-info">
                        @if($branch->getAddress($locale))
                            <div class="branch-info-item">
                                <x-icon name="map-marker-alt" />
                                <span>{{ $branch->getAddress($locale) }}</span>
                            </div>
                        @endif
                        
                        @if($branch->phone)
                            <div class="branch-info-item">
                                <x-icon name="phone" />
                                <span>{{ $branch->phone }}</span>
                            </div>
                        @endif
                        
                        @if($branch->getWorkingHours($locale))
                            <div class="branch-info-item">
                                <x-icon name="clock" />
                                <span>{{ $branch->getWorkingHours($locale) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <x-icon :name="'arrow-' . ($locale === 'ar' ? 'left' : 'right')" class="arrow-icon" />
                </a>
            @endforeach
        </div>
    </div>
</body>
</html>
