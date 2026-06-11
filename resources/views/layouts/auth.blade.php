<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @php
        $siteName = app()->getLocale() == 'ar' 
            ? \App\Models\Setting::get('site_name_ar', 'QR Menu') 
            : \App\Models\Setting::get('site_name_en', 'QR Menu');
        $siteLogo = \App\Models\Setting::get('site_logo');
        $siteFavicon = \App\Models\Setting::get('site_favicon');
        $primaryColor = \App\Models\Setting::get('primary_color', '#0d9488');
        $secondaryColor = \App\Models\Setting::get('secondary_color', '#0f172a');
    @endphp
    
    <title>@yield('title') - {{ $siteName }}</title>
    
    @if($siteFavicon)
    <link rel="icon" href="{{ asset('storage/' . $siteFavicon) }}" type="image/png">
    @endif
    
    @if(app()->getLocale() == 'ar')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryColor }}15;
            --secondary: {{ $secondaryColor }};
            --secondary-light: #1e293b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        * {
            font-family: {{ app()->getLocale() == 'ar' ? "'Cairo'" : "'Inter'" }}, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            min-height: 100vh;
            background: #f8fafc;
            overflow-x: hidden;
        }
        
        /* Auth Container - Split Screen */
        .auth-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Left Side - Branding */
        .auth-branding {
            flex: 1;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-light) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            overflow: hidden;
        }
        
        /* Animated Wave Background */
        .wave-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.3;
        }
        
        .wave-bg svg {
            position: absolute;
            width: 200%;
            height: 200%;
            animation: wave 20s ease-in-out infinite;
        }
        
        .wave-bg svg:nth-child(2) {
            animation-delay: -5s;
            opacity: 0.5;
        }
        
        .wave-bg svg:nth-child(3) {
            animation-delay: -10s;
            opacity: 0.3;
        }
        
        @keyframes wave {
            0%, 100% { transform: translateX(-25%) translateY(-25%) rotate(0deg); }
            50% { transform: translateX(-30%) translateY(-20%) rotate(5deg); }
        }
        
        .branding-content {
            position: relative;
            z-index: 2;
            color: white;
        }
        
        .branding-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .branding-content p {
            font-size: 1.1rem;
            opacity: 0.85;
            margin-bottom: 40px;
            line-height: 1.7;
        }
        
        .features-list {
            list-style: none;
            padding: 0;
        }
        
        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 1rem;
        }
        
        .feature-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 15px;
            flex-shrink: 0;
        }
        
        [dir="ltr"] .feature-icon {
            margin-left: 0;
            margin-right: 15px;
        }
        
        .feature-icon i {
            color: white;
            font-size: 1rem;
        }
        
        .version-badge {
            position: absolute;
            top: 30px;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 30px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 20px;
            color: white;
            font-size: 0.85rem;
        }
        
        .copyright {
            position: absolute;
            bottom: 30px;
            {{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 30px;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
        }
        
        /* Right Side - Form */
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .auth-form-container {
            width: 100%;
            max-width: 420px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            margin-bottom: 40px;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: var(--primary);
        }
        
        .back-link i {
            margin-left: 8px;
        }
        
        [dir="ltr"] .back-link i {
            margin-left: 0;
            margin-right: 8px;
        }
        
        .auth-logo {
            width: 50px;
            height: 50px;
            margin-bottom: 30px;
        }
        
        .auth-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .auth-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 8px;
        }
        
        .auth-subtitle {
            color: #64748b;
            margin-bottom: 35px;
            font-size: 0.95rem;
        }
        
        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f8fafc;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px var(--primary-light);
        }
        
        .form-control::placeholder {
            color: #94a3b8;
        }
        
        .input-icon-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            top: 50%;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 16px;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }
        
        .input-icon-wrapper .form-control {
            padding-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 48px;
        }
        
        .password-toggle {
            position: absolute;
            top: 50%;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 16px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .form-check {
            display: flex;
            align-items: center;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            margin-left: 10px;
            cursor: pointer;
            accent-color: var(--primary);
        }
        
        [dir="ltr"] .form-check-input {
            margin-left: 0;
            margin-right: 10px;
        }
        
        .form-check-label {
            color: #64748b;
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        /* Button */
        .btn-primary {
            width: 100%;
            padding: 14px 24px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-primary:hover {
            background: var(--primary);
            filter: brightness(1.1);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(13, 148, 136, 0.3);
        }
        
        .btn-primary i {
            margin-left: 10px;
        }
        
        [dir="ltr"] .btn-primary i {
            margin-left: 0;
            margin-right: 10px;
        }
        
        /* Bottom Link */
        .auth-footer {
            text-align: center;
            margin-top: 30px;
            color: #64748b;
            font-size: 0.95rem;
        }
        
        .auth-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        /* Alert */
        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            font-size: 0.9rem;
        }
        
        .alert i {
            margin-left: 12px;
            font-size: 1.1rem;
        }
        
        [dir="ltr"] .alert i {
            margin-left: 0;
            margin-right: 12px;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        /* Language Switcher - Simple Button */
        .lang-switcher {
            position: fixed;
            top: 20px;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 20px;
            z-index: 1000;
        }
        
        .lang-btn {
            display: inline-flex;
            align-items: center;
            background: white;
            border: 1.5px solid #e2e8f0;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s;
            gap: 8px;
        }
        
        .lang-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-light);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .auth-branding {
                display: none;
            }
            
            .auth-form-side {
                flex: 1;
            }
        }
        
        @media (max-width: 576px) {
            .auth-form-side {
                padding: 30px 20px;
            }
            
            .auth-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    {{-- Language Switcher - Simple Button --}}
    <div class="lang-switcher">
        @if(app()->getLocale() == 'ar')
            <a href="{{ route('language.switch', 'en') }}" class="lang-btn">
                <i class="fas fa-globe"></i>
                English
            </a>
        @else
            <a href="{{ route('language.switch', 'ar') }}" class="lang-btn">
                <i class="fas fa-globe"></i>
                العربية
            </a>
        @endif
    </div>

    <div class="auth-wrapper">
        {{-- Left Side - Branding --}}
        <div class="auth-branding">
            {{-- Animated Waves --}}
            <div class="wave-bg">
                <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 0 500 Q 250 400 500 500 Q 750 600 1000 500 L 1000 1000 L 0 1000 Z" fill="url(#grad1)"/>
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:{{ $primaryColor }};stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:{{ $primaryColor }};stop-opacity:0.1" />
                        </linearGradient>
                    </defs>
                </svg>
                <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 0 600 Q 250 500 500 600 Q 750 700 1000 600 L 1000 1000 L 0 1000 Z" fill="url(#grad2)"/>
                    <defs>
                        <linearGradient id="grad2" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.2" />
                            <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.05" />
                        </linearGradient>
                    </defs>
                </svg>
                <svg viewBox="0 0 1000 1000" xmlns="http://www.w3.org/2000/svg">
                    <path d="M 0 700 Q 250 600 500 700 Q 750 800 1000 700 L 1000 1000 L 0 1000 Z" fill="url(#grad3)"/>
                    <defs>
                        <linearGradient id="grad3" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.1" />
                            <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0.02" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            
            <div class="version-badge">
                <i class="fas fa-code-branch me-1"></i> {{ __('app.version') }} 1.0
            </div>
            
            <div class="branding-content">
                <h1>{{ __('app.branding_title') }}</h1>
                <p>{{ __('app.branding_description') }}</p>
                
                <ul class="features-list">
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        {{ __('app.feature_qr') }}
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        {{ __('app.feature_reports') }}
                    </li>
                    <li>
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        {{ __('app.feature_security') }}
                    </li>
                </ul>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} {{ $siteName }}. {{ __('app.all_rights_reserved') }}
            </div>
        </div>
        
        {{-- Right Side - Form --}}
        <div class="auth-form-side">
            <div class="auth-form-container">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>