<!DOCTYPE html>
<html lang="{{ session('locale', 'ar') }}" dir="{{ session('locale', 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->settings->primary_color ?? '#FF6B35' }}">
    <title>{{ $restaurant->getName() }} @if($branch) - {{ $branch->getName() }} @endif</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @php
        $settings = $restaurant->settings;
        $primaryColor = $settings->primary_color ?? '#FF6B35';
        $secondaryColor = $settings->secondary_color ?? '#1d3557';
        $locale = session('locale', 'ar');
    @endphp
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryColor }}15;
            --secondary: {{ $secondaryColor }};
            --text: #1a1a2e;
            --text-light: #64748b;
            --bg: #fafbfc;
            --card: #ffffff;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $locale === 'ar' ? "'Tajawal'" : "'Poppins'" }}, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: var(--text);
            padding-bottom: 90px;
        }
        
        .menu-container {
            max-width: 500px;
            margin: 0 auto;
        }
        
        @media (min-width: 768px) {
            body { padding: 20px; }
            .menu-container {
                border-radius: 30px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.1);
                background: var(--bg);
            }
        }
        
        /* Header */
        .header {
            background: linear-gradient(135deg, var(--secondary) 0%, #0d1b2a 100%);
            padding: 40px 20px 30px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .lang-btn {
            position: absolute;
            top: 15px;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .back-btn {
            position: absolute;
            top: 15px;
            {{ $locale === 'ar' ? 'right' : 'left' }}: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin-bottom: 15px;
            background: white;
        }
        
        .logo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 4px solid white;
        }
        
        .logo-placeholder i { font-size: 2.5rem; color: white; }
        
        .restaurant-name { font-size: 1.6rem; font-weight: 800; margin-bottom: 5px; }
        .branch-name { font-size: 1rem; opacity: 0.9; margin-bottom: 10px; }
        .restaurant-desc { font-size: 0.9rem; opacity: 0.8; max-width: 300px; margin: 0 auto; }
        
        /* Branch Info */
        .branch-info {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .info-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        
        .info-btn:hover { background: rgba(255,255,255,0.3); color: white; }
        
        /* Featured */
        .featured-section {
            background: var(--card);
            margin: -20px 15px 15px;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            position: relative;
            z-index: 5;
        }
        
        .section-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .section-title i { color: #FFD700; }
        
        .featured-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 5px;
        }
        
        .featured-scroll::-webkit-scrollbar { display: none; }
        
        .featured-card {
            flex: 0 0 130px;
            text-align: center;
        }
        
        .featured-card img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 8px;
        }
        
        .featured-card .placeholder {
            width: 100%;
            height: 90px;
            background: #f1f5f9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }
        
        .featured-card .placeholder i { font-size: 1.5rem; color: #cbd5e1; }
        .featured-card h6 { font-size: 0.8rem; margin-bottom: 4px; color: var(--text); }
        .featured-card .price { color: var(--primary); font-weight: 700; font-size: 0.85rem; }
        
        /* Categories Nav */
        .categories-nav {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 15px;
            background: var(--card);
            position: sticky;
            top: 0;
            z-index: 100;
            scrollbar-width: none;
        }
        
        .categories-nav::-webkit-scrollbar { display: none; }
        
        .cat-pill {
            flex: 0 0 auto;
            padding: 10px 18px;
            border-radius: 25px;
            background: #f1f5f9;
            color: var(--text);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.3s;
        }
        
        .cat-pill.active, .cat-pill:hover {
            background: var(--primary);
            color: white;
        }
        
        /* Menu Items */
        .menu-section { padding: 15px; }
        
        .category-block { margin-bottom: 25px; }
        
        .category-title {
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .category-title i { color: var(--primary); }
        
        .menu-item {
            display: flex;
            gap: 12px;
            background: var(--card);
            border-radius: 16px;
            padding: 12px;
            margin-bottom: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        
        .menu-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }
        
        .item-img {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .item-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .item-placeholder i { font-size: 1.5rem; color: #cbd5e1; }
        
        .item-info { flex: 1; min-width: 0; }
        .item-name { font-weight: 700; font-size: 0.95rem; margin-bottom: 4px; }
        .item-desc { font-size: 0.8rem; color: var(--text-light); margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .item-footer { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .price-current { font-weight: 800; color: var(--primary); font-size: 1rem; }
        .price-old { text-decoration: line-through; color: #94a3b8; font-size: 0.85rem; }
        .discount-badge { background: #ef4444; color: white; font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; font-weight: 600; }
        .featured-badge { background: #FFD700; color: #000; font-size: 0.65rem; padding: 2px 6px; border-radius: 8px; font-weight: 700; }
        
        /* Social Footer */
        .social-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--card);
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.08);
            z-index: 1000;
        }
        
        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .social-btn:hover { transform: scale(1.1); color: white; }
        .social-btn.instagram { background: linear-gradient(45deg, #f09433, #dc2743, #bc1888); }
        .social-btn.facebook { background: #1877F2; }
        .social-btn.whatsapp { background: #25D366; }
        .social-btn.tiktok { background: #000; }
        .social-btn.twitter { background: #1DA1F2; }
        .social-btn.snapchat { background: #FFFC00; color: #000; }
        .social-btn.youtube { background: #FF0000; }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i { font-size: 4rem; color: #e2e8f0; margin-bottom: 15px; }
        .empty-state h3 { font-size: 1.2rem; margin-bottom: 10px; }
        .empty-state p { color: var(--text-light); }
    </style>
</head>
<body>
    <div class="menu-container">
        <!-- Header -->
        <header class="header">
            @if($restaurant->branches->count() > 1)
            <a href="{{ route('menu.show', $restaurant->slug) }}" class="back-btn">
                <i class="fas fa-arrow-{{ $locale === 'ar' ? 'right' : 'left' }}"></i>
            </a>
            @endif
            
            <form action="{{ route('menu.language', $restaurant->slug) }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="locale" value="{{ $locale === 'ar' ? 'en' : 'ar' }}">
                <button type="submit" class="lang-btn">{{ $locale === 'ar' ? 'EN' : 'ع' }}</button>
            </form>
            
            @if($restaurant->logo_url)
                <img src="{{ $restaurant->logo_url }}" alt="{{ $restaurant->getName() }}" class="logo">
            @else
                <div class="logo-placeholder"><i class="fas fa-utensils"></i></div>
            @endif
            
            <h1 class="restaurant-name">{{ $restaurant->getName() }}</h1>
            
            @if($branch)
            <p class="branch-name"><i class="fas fa-map-marker-alt me-1"></i> {{ $branch->getName() }}</p>
            @endif
            
            @if($restaurant->getDescription())
            <p class="restaurant-desc">{{ $restaurant->getDescription() }}</p>
            @endif
            
            @if($branch)
            <div class="branch-info">
                @if($branch->phone)
                <a href="tel:{{ $branch->phone }}" class="info-btn">
                    <i class="fas fa-phone"></i>
                    {{ $locale === 'ar' ? 'اتصل' : 'Call' }}
                </a>
                @endif
                @if($branch->whatsapp)
                <a href="{{ $branch->getWhatsappUrl() }}" target="_blank" class="info-btn">
                    <i class="fab fa-whatsapp"></i>
                    {{ $locale === 'ar' ? 'واتساب' : 'WhatsApp' }}
                </a>
                @endif
                @if($branch->google_maps_url)
                <a href="{{ $branch->google_maps_url }}" target="_blank" class="info-btn">
                    <i class="fas fa-map-marker-alt"></i>
                    {{ $locale === 'ar' ? 'الموقع' : 'Location' }}
                </a>
                @endif
            </div>
            @endif
        </header>
        
        <!-- Featured Items -->
        @if($featuredItems && $featuredItems->count() > 0)
        <section class="featured-section">
            <h2 class="section-title">
                <i class="fas fa-fire"></i>
                {{ $locale === 'ar' ? 'الأكثر طلباً' : 'Most Popular' }}
            </h2>
            <div class="featured-scroll">
                @foreach($featuredItems as $item)
                <div class="featured-card">
                    @if($item->image_url)
                        <img src="{{ $item->image_url }}" alt="{{ $item->getName() }}" loading="lazy">
                    @else
                        <div class="placeholder"><i class="fas fa-utensils"></i></div>
                    @endif
                    <h6>{{ $item->getName() }}</h6>
                    <span class="price">{{ $item->getFormattedPrice() }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        
        <!-- Categories Nav -->
        @if($categories && $categories->count() > 0)
        <nav class="categories-nav">
            @foreach($categories as $index => $category)
            <a href="#cat-{{ $category->id }}" class="cat-pill {{ $index === 0 ? 'active' : '' }}">
                @if($category->icon)<i class="{{ $category->icon }} me-1"></i>@endif
                {{ $category->getName() }}
            </a>
            @endforeach
        </nav>
        @endif
        
        <!-- Menu Items -->
        <main class="menu-section">
            @forelse($categories as $category)
                @if($category->branchItems && $category->branchItems->count() > 0)
                <section id="cat-{{ $category->id }}" class="category-block">
                    <h3 class="category-title">
                        @if($category->icon)<i class="{{ $category->icon }}"></i>@endif
                        {{ $category->getName() }}
                    </h3>
                    
                    @foreach($category->branchItems as $item)
                    <article class="menu-item">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->getName() }}" class="item-img" loading="lazy">
                        @else
                            <div class="item-placeholder"><i class="fas fa-utensils"></i></div>
                        @endif
                        
                        <div class="item-info">
                            <h4 class="item-name">
                                {{ $item->getName() }}
                                @if($item->is_featured)
                                <span class="featured-badge"><i class="fas fa-star"></i></span>
                                @endif
                            </h4>
                            @if($item->getDescription())
                            <p class="item-desc">{{ $item->getDescription() }}</p>
                            @endif
                            
                            <div class="item-footer">
                                <span class="price-current">{{ $item->getFormattedPrice() }}</span>
                                @if($item->hasDiscount())
                                <span class="price-old">{{ number_format($item->old_price, 2) }}</span>
                                <span class="discount-badge">-{{ $item->getDiscountPercentage() }}%</span>
                                @endif
                            </div>
                        </div>
                    </article>
                    @endforeach
                </section>
                @endif
            @empty
            <div class="empty-state">
                <i class="fas fa-utensils"></i>
                <h3>{{ $locale === 'ar' ? 'قريباً...' : 'Coming Soon...' }}</h3>
                <p>{{ $locale === 'ar' ? 'نعمل على إضافة قائمتنا' : 'We are working on our menu' }}</p>
            </div>
            @endforelse
        </main>
        
        <!-- Social Footer -->
        @if($restaurant->socialLinks && $restaurant->socialLinks->count() > 0)
        <footer class="social-footer">
            @foreach($restaurant->socialLinks->where('is_active', true) as $link)
            <a href="{{ $link->getFormattedUrl() }}" target="_blank" class="social-btn {{ $link->platform }}">
                <i class="{{ $link->getIconClass() }}"></i>
            </a>
            @endforeach
        </footer>
        @endif
    </div>
    
    <script>
        document.querySelectorAll('.cat-pill').forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const nav = document.querySelector('.categories-nav');
                    window.scrollTo({ top: target.offsetTop - nav.offsetHeight - 10, behavior: 'smooth' });
                }
                document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
