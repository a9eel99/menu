<!DOCTYPE html>
<html lang="{{ $locale ?? 'ar' }}" dir="{{ ($locale ?? 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->primary_color ?? '#8B5CF6' }}">
    <meta name="description" content="{{ $restaurant->getDescription($locale) }}">
    <title>{{ $restaurant->getName($locale) }}</title>
    
    @if($restaurant->logo)
    <link rel="icon" href="{{ asset('storage/' . $restaurant->logo) }}" type="image/png">
    @endif
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @php
        $primaryColor = $restaurant->primary_color ?? '#8B5CF6';
        $secondaryColor = $restaurant->secondary_color ?? '#1E1B4B';
        $locale = $locale ?? 'ar';
    @endphp
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryColor }}20;
            --primary-dark: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --text: #1f2937;
            --text-light: #6b7280;
            --bg: #f8fafc;
            --card-bg: rgba(255, 255, 255, 0.95);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: var(--text);
        }

        /* Container */
        .landing-container {
            max-width: 450px;
            margin: 0 auto;
            min-height: 100vh;
            position: relative;
            @if($restaurant->cover_image)
            background: url('{{ asset('storage/' . $restaurant->cover_image) }}');
            background-size: cover;
            background-position: center top;
            background-attachment: scroll;
            @endif
        }

        @media (min-width: 768px) {
            body { padding: 20px; background: #f1f5f9; }
            .landing-container {
                @if($restaurant->cover_image)
                background: url('{{ asset('storage/' . $restaurant->cover_image) }}');
                background-size: cover;
                background-position: center top;
                @else
                background: var(--bg);
                @endif
                border-radius: 40px;
                overflow: hidden;
                box-shadow: 0 25px 80px rgba(0,0,0,0.15);
                min-height: auto;
                padding-bottom: 40px;
            }
        }

        /* Header Section */
        .header-section {
            text-align: center;
            padding: 50px 20px 30px;
            position: relative;
        }

        /* Logo */
        .logo-wrapper {
            position: relative;
            padding-top: 80px;
            z-index: 10;
            display: flex;
            justify-content: center;
        }
        
        .logo-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            padding: 6px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .logo-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
        }
        
        /* Restaurant Info */
        .restaurant-info {
            padding: 20px 25px;
            text-align: center;
        }
        
        .restaurant-name {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .restaurant-tagline {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            margin-bottom: 16px;
            line-height: 1.6;
            position: relative;
            display: inline-block;
        }
        .restaurant-tagline::before {
            content: '――\00a0\00a0';
            color: var(--primary);
            font-size: 0.8rem;
            vertical-align: middle;
        }
        .restaurant-tagline::after {
            content: '\00a0\00a0――';
            color: var(--primary);
            font-size: 0.8rem;
            vertical-align: middle;
        }
        
        /* Rating */
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 30px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .rating-badge i { color: var(--primary); }
        
        /* Links Section */
        .links-section {
            padding: 10px 20px 30px;
        }
        
        .link-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 10px;
            text-decoration: none;
            color: var(--text);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(255,255,255,0.8);
        }

        .link-card:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        .link-card:active {
            transform: scale(0.98);
        }

        .link-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .link-icon.menu { background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}cc); color: white; }
        .link-icon.location { background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}cc); color: white; }
        .link-icon.phone { background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}cc); color: white; }
        .link-icon.reviews { background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}cc); color: white; }
        .link-icon.branches { background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}cc); color: white; }
        
        .link-content {
            flex: 1;
        }
        
        .link-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 2px;
            color: var(--text);
        }

        .link-subtitle {
            font-size: 0.75rem;
            color: var(--text-light);
        }
        
        .link-arrow {
            color: #cbd5e1;
            font-size: 1rem;
            transition: transform 0.3s ease;
        }
        
        [dir="rtl"] .link-arrow { transform: rotate(180deg); }
        
        .link-card:hover .link-arrow {
            transform: translateX(5px);
            color: var(--primary);
        }
        
        [dir="rtl"] .link-card:hover .link-arrow {
            transform: translateX(-5px) rotate(180deg);
        }
        
        /* Menu Link - Special Style */
        .link-card.menu-link {
            background: linear-gradient(135deg, var(--primary), {{ $primaryColor }}dd);
            color: white;
            border: none;
            padding: 20px 22px;
            border-radius: 50px;
            position: relative;
            min-height: 85px;
        }

        .link-card.menu-link .link-icon {
            width: 55px;
            height: 55px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .link-card.menu-link .link-icon svg {
            width: 28px;
            height: 28px;
        }

        .link-card.menu-link .link-title {
            color: white;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .link-card.menu-link .link-subtitle {
            color: white;
            opacity: 0.85;
            font-size: 0.8rem;
        }

        .link-card.menu-link .link-arrow {
            width: 34px;
            height: 34px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 0.85rem;
        }

        .link-card.menu-link:hover .link-arrow {
            color: var(--primary);
            transform: scale(1.1);
        }

        [dir="rtl"] .link-card.menu-link .link-arrow {
            transform: rotate(180deg);
        }

        [dir="rtl"] .link-card.menu-link:hover .link-arrow {
            transform: rotate(180deg) scale(1.1);
        }
        
        /* Social Links */
        .social-section {
            padding: 20px;
            text-align: center;
        }
        
        .social-title {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85);
            margin-bottom: 16px;
            font-weight: 700;
            display: inline-block;
        }
        .social-title::before {
            content: '――●\00a0\00a0';
            color: var(--primary);
            font-size: 0.7rem;
            vertical-align: middle;
        }
        .social-title::after {
            content: '\00a0\00a0●――';
            color: var(--primary);
            font-size: 0.7rem;
            vertical-align: middle;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .social-link {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border: none;
        }

        .social-link.facebook { background: #1877F2; }
        .social-link.instagram { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
        .social-link.twitter, .social-link.x { background: #000000; }
        .social-link.tiktok { background: #000000; }
        .social-link.youtube { background: #FF0000; }
        .social-link.snapchat { background: #FFFC00; color: #000; }
        .social-link.linkedin { background: #0A66C2; }
        .social-link.telegram { background: #0088cc; }
        
        /* Working Hours */
        .hours-section {
            padding: 10px 20px 30px;
            text-align: center;
        }
        
        .hours-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(255,255,255,0.8);
        }
        
        .hours-title {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 10px;
        }
        
        .hours-title i { color: var(--primary); }
        
        .hours-text {
            color: var(--text-light);
            font-size: 0.95rem;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 25px 20px;
            color: rgba(255,255,255,0.7);
            font-size: 0.85rem;
        }

        .footer-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .footer-icon {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        /* Language Switcher */
        .lang-switch {
            position: absolute;
            top: 20px;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 20px;
            z-index: 100;
        }
        
        .lang-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            padding: 8px 14px;
            border-radius: 30px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .lang-btn:hover {
            background: rgba(255,255,255,0.15);
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .link-card {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }
        
        .link-card:nth-child(1) { animation-delay: 0.1s; }
        .link-card:nth-child(2) { animation-delay: 0.2s; }
        .link-card:nth-child(3) { animation-delay: 0.3s; }
        .link-card:nth-child(4) { animation-delay: 0.4s; }
        .link-card:nth-child(5) { animation-delay: 0.5s; }
        .link-card:nth-child(6) { animation-delay: 0.6s; }
        
        /* Branch Badge */
        .branch-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.9);
            margin-bottom: 12px;
        }
        
        .branch-badge i { font-size: 0.7rem; }
    </style>
</head>
<body>
    <div class="landing-container">
        {{-- Language Switcher --}}
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
        
        {{-- Logo --}}
        <div class="logo-wrapper">
            <div class="logo-container">
                @if($restaurant->logo)
                    <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->getName($locale) }}">
                @else
                    <div class="logo-placeholder">
                        <i class="fas fa-utensils"></i>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- Restaurant Info --}}
        <div class="restaurant-info">
            {{-- Branch Badge --}}
            @if($restaurant->parent_id)
            <div class="branch-badge">
                <i class="fas fa-code-branch"></i>
                {{ __('app.branch') }}
            </div>
            @endif
            
            <h1 class="restaurant-name">{{ $restaurant->getName($locale) }}</h1>
            
            @if($restaurant->getDescription($locale))
            <p class="restaurant-tagline">{{ $restaurant->getDescription($locale) }}</p>
            @endif
        </div>
        
        {{-- Links Section --}}
        <div class="links-section">
            @foreach($landingButtons as $button)
                @php
                    $showButton = false;
                    $buttonUrl = '#';
                    $buttonTarget = '';
                    $isMenuButton = false;

                    switch($button->type) {
                        case 'menu':
                            $showButton = true;
                            $buttonUrl = route('menu.show', $restaurant->slug);
                            $isMenuButton = true;
                            break;
                        case 'branches':
                            $showButton = $restaurant->isMain() && $restaurant->branches->count() > 0;
                            $buttonUrl = route('menu.branches', $restaurant->slug);
                            break;
                        case 'phone':
                            $showButton = !empty($restaurant->phone);
                            $buttonUrl = 'tel:' . $restaurant->phone;
                            break;
                        case 'location':
                            $showButton = !empty($restaurant->google_maps_url);
                            $buttonUrl = $restaurant->google_maps_url;
                            $buttonTarget = '_blank';
                            break;
                        case 'reviews':
                            $showButton = !empty($restaurant->google_reviews_url);
                            $buttonUrl = $restaurant->google_reviews_url;
                            $buttonTarget = '_blank';
                            break;
                    }
                @endphp

                @if($showButton)
                    @if($isMenuButton)
                    <a href="{{ $buttonUrl }}" class="link-card menu-link" {{ $buttonTarget ? "target=$buttonTarget" : '' }}>
                        <div class="link-icon menu">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="currentColor">
                                <path d="M32 8c-1.1 0-2 .9-2 2v4.1C17.2 15.5 7 26.5 7 40h50c0-13.5-10.2-24.5-23-25.9V10c0-1.1-.9-2-2-2z"/>
                                <ellipse cx="32" cy="40" rx="3" ry="2"/>
                                <path d="M32 32c-1.7 0-3 1.3-3 3v2c0 .6.4 1 1 1h4c.6 0 1-.4 1-1v-2c0-1.7-1.3-3-3-3z"/>
                                <path d="M5 44c0 2.2 1.8 4 4 4h46c2.2 0 4-1.8 4-4v-2H5v2z"/>
                            </svg>
                        </div>
                        <div class="link-content">
                            <div class="link-title">{{ $button->getTitle($locale) }}</div>
                            <div class="link-subtitle">{{ $button->getSubtitle($locale) }}</div>
                        </div>
                        <div class="link-arrow">
                            <i class="fas fa-chevron-{{ $locale === 'ar' ? 'left' : 'right' }}"></i>
                        </div>
                    </a>
                    @else
                    <a href="{{ $buttonUrl }}" class="link-card" {{ $buttonTarget ? "target=$buttonTarget" : '' }}>
                        <div class="link-icon {{ $button->type }}">
                            <i class="fas fa-{{ $button->icon ?? 'link' }}"></i>
                        </div>
                        <div class="link-content">
                            <div class="link-title">{{ $button->getTitle($locale) }}</div>
                            <div class="link-subtitle">
                                @if($button->type === 'phone')
                                    <span style="direction: ltr; display: inline-block;">{{ $restaurant->phone }}</span>
                                @else
                                    {{ $button->getSubtitle($locale) ?: ($button->type === 'location' ? $restaurant->getAddress($locale) : '') }}
                                @endif
                            </div>
                        </div>
                        <i class="fas fa-chevron-right link-arrow"></i>
                    </a>
                    @endif
                @endif
            @endforeach
        </div>
        
        {{-- Social Links --}}
        @if($restaurant->socialLinks && $restaurant->socialLinks->count() > 0)
        <div class="social-section">
            <div class="social-title">{{ $locale === 'ar' ? 'تابعنا' : 'Follow us' }}</div>
            <div class="social-links">
                @foreach($restaurant->socialLinks as $social)
                    @php
                        $platform = strtolower($social->platform);
                        if($platform === 'whatsapp') continue;
                        $iconClass = match($platform) {
                            'facebook' => 'fab fa-facebook-f',
                            'instagram' => 'fab fa-instagram',
                            'twitter', 'x' => 'fab fa-x-twitter',
                            'tiktok' => 'fab fa-tiktok',
                            'youtube' => 'fab fa-youtube',
                            'snapchat' => 'fab fa-snapchat-ghost',
                            'linkedin' => 'fab fa-linkedin-in',
                            'telegram' => 'fab fa-telegram-plane',
                            default => $social->icon ?? 'fas fa-link'
                        };
                    @endphp
                    <a href="{{ $social->url }}" target="_blank" class="social-link {{ $platform }}" title="{{ $social->platform }}">
                        <i class="{{ $iconClass }}"></i>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Working Hours --}}
        @if($restaurant->getWorkingHours($locale))
        <div class="hours-section">
            <div class="hours-card">
                <div class="hours-title">
                    <i class="fas fa-clock"></i>
                    {{ $locale === 'ar' ? 'ساعات العمل' : 'Working Hours' }}
                </div>
                <div class="hours-text">{{ $restaurant->getWorkingHours($locale) }}</div>
            </div>
        </div>
        @endif

    </div>
</body>
</html>