<!DOCTYPE html>
<html lang="{{ $locale ?? 'ar' }}" dir="{{ ($locale ?? 'ar') === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->primary_color ?? '#FF6B35' }}">
    <title>{{ $restaurant->getName($locale) }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    @php
        $primaryColor = $restaurant->primary_color ?? '#FF6B35';
        $secondaryColor = $restaurant->secondary_color ?? '#1d3557';
        $locale = $locale ?? 'ar';
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
            font-family: {{ $locale === 'ar' ? "'Cairo'" : "'Inter'" }}, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            color: var(--text);
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
            padding: 30px 20px 40px;
            text-align: center;
            color: white;
            position: relative;
        }
        
        .header.has-cover {
            background: none;
            padding: 0;
        }
        
        .cover-wrapper {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        
        .cover-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.6) 100%);
        }
        
        .header-content {
            padding: 20px;
            text-align: center;
        }
        
        .header.has-cover .header-content {
            position: relative;
            margin-top: -60px;
            z-index: 10;
        }
        
        .lang-form {
            position: absolute;
            top: 15px;
            {{ $locale === 'ar' ? 'left' : 'right' }}: 15px;
            z-index: 100;
        }
        
        .lang-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .lang-btn:hover { background: rgba(255,255,255,0.3); }
        
        .logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin: 0 auto 15px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            display: block;
        }
        
        .logo-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 4px solid white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .logo-placeholder i { font-size: 2rem; color: var(--secondary); }
        
        .restaurant-name { 
            font-size: 1.5rem; 
            font-weight: 800; 
            margin-bottom: 8px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .restaurant-desc { 
            font-size: 0.9rem; 
            opacity: 0.9; 
            max-width: 300px; 
            margin: 0 auto;
            text-shadow: 0 1px 5px rgba(0,0,0,0.3);
        }
        
        /* لما يكون فيه غلاف، النص يكون أبيض */
        .header.has-cover .restaurant-name,
        .header.has-cover .restaurant-desc {
            color: white;
        }
        
        /* لما ما يكون فيه غلاف */
        .header:not(.has-cover) .restaurant-name {
            color: white;
        }
        
        .header:not(.has-cover) .restaurant-desc {
            color: rgba(255,255,255,0.85);
        }
        
        .logo-placeholder i { font-size: 2.5rem; color: white; }
        
        .restaurant-name { font-size: 1.6rem; font-weight: 800; margin-bottom: 5px; }
        .restaurant-desc { font-size: 0.9rem; opacity: 0.8; max-width: 300px; margin: 0 auto; }
        
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
            flex-shrink: 0;
            padding: 10px 18px;
            border-radius: 25px;
            background: var(--bg);
            color: var(--text);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
        }
        
        .cat-pill:hover, .cat-pill.active {
            background: var(--primary);
            color: white;
        }
        
        /* Menu Items */
        .menu-section { padding: 15px; }
        
        .category-block { margin-bottom: 25px; }
        
        .category-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .category-title i { color: var(--primary); }
        
        .category-title img {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            object-fit: cover;
        }
        
        .menu-item {
            display: flex;
            gap: 12px;
            padding: 15px;
            background: var(--card);
            border-radius: 16px;
            margin-bottom: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        }
        
        .item-img {
            width: 85px;
            height: 85px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }
        
        .item-placeholder {
            width: 85px;
            height: 85px;
            border-radius: 12px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .item-placeholder i { font-size: 1.5rem; color: #cbd5e1; }
        
        .item-info { flex: 1; min-width: 0; }
        
        .item-name {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .featured-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .featured-badge i { font-size: 0.6rem; color: white; }
        
        .item-desc {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .item-footer { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        
        .price-current {
            font-weight: 700;
            color: var(--primary);
            font-size: 1rem;
        }
        
        .price-old {
            font-size: 0.85rem;
            color: #999;
            text-decoration: line-through;
        }
        
        .discount-badge {
            background: #ff4757;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-state i { font-size: 4rem; color: #e2e8f0; margin-bottom: 15px; }
        .empty-state h3 { font-size: 1.2rem; margin-bottom: 10px; }
        .empty-state p { color: var(--text-light); }
        
        /* Item Tags */
        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin: 5px 0;
        }
        
        .item-tag {
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        
        /* Working Hours */
        .working-hours {
            margin-top: 15px;
            padding: 10px 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: inline-block;
        }
        
        .working-hours i { margin-left: 5px; }
        
        /* Back to Landing Button */
        .back-to-landing {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.2);
            border-radius: 25px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .back-to-landing:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        /* Menu Footer */
        .menu-footer {
            text-align: center;
            padding: 30px 20px;
            background: var(--card);
            margin-top: 20px;
        }
        
        .footer-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .footer-back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            color: white;
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <!-- Header -->
        <header class="header {{ $restaurant->getCoverUrl() ? 'has-cover' : '' }}">
            @if($restaurant->getCoverUrl())
            <div class="cover-wrapper">
                <img src="{{ $restaurant->getCoverUrl() }}" alt="{{ $restaurant->getName() }}" class="cover-image">
                <div class="cover-overlay"></div>
            </div>
            @endif
            
            {{-- زر تبديل اللغة - خارج header-content --}}
            <form action="{{ route('menu.language', $restaurant->slug) }}" method="POST" class="lang-form">
                @csrf
                <input type="hidden" name="locale" value="{{ $locale === 'ar' ? 'en' : 'ar' }}">
                <button type="submit" class="lang-btn">{{ $locale === 'ar' ? 'EN' : 'ع' }}</button>
            </form>
            
            <div class="header-content">
                @if($restaurant->getLogoUrl())
                    <img src="{{ $restaurant->getLogoUrl() }}" alt="{{ $restaurant->getName($locale) }}" class="logo">
                @else
                    <div class="logo-placeholder"><i class="fas fa-utensils"></i></div>
                @endif
                
                <h1 class="restaurant-name">{{ $restaurant->getName($locale) }}</h1>
                
                @if($restaurant->getDescription($locale))
                <p class="restaurant-desc">{{ $restaurant->getDescription($locale) }}</p>
                @endif
                
                {{-- زر الرجوع للـ Landing --}}
                <a href="{{ route('menu.landing', $restaurant->slug) }}" class="back-to-landing">
                    <i class="fas fa-arrow-{{ $locale === 'ar' ? 'right' : 'left' }}"></i>
                    {{ $locale === 'ar' ? 'الصفحة الرئيسية' : 'Back to Home' }}
                </a>
            </div><!-- /header-content -->
        </header>
        
        <!-- Featured Items -->
        @if(isset($featuredItems) && $featuredItems->count() > 0)
        <section class="featured-section">
            <h2 class="section-title">
                <i class="fas fa-fire"></i>
                {{ $locale === 'ar' ? 'الأكثر طلباً' : 'Most Popular' }}
            </h2>
            <div class="featured-scroll">
                @foreach($featuredItems as $item)
                <div class="featured-card">
                    @if($item->getImageUrl())
                        <img src="{{ $item->getImageUrl() }}" alt="{{ $item->getName() }}" loading="lazy">
                    @else
                        <div class="placeholder"><i class="fas fa-utensils"></i></div>
                    @endif
                    <h6>{{ $item->getName() }}</h6>
                    <span class="price">{{ $restaurant->getFormattedPrice($item->price) }}</span>
                </div>
                @endforeach
            </div>
        </section>
        @endif
        
        <!-- Categories Nav -->
        @if(isset($categories) && $categories->count() > 0)
        <nav class="categories-nav">
            @foreach($categories as $index => $category)
            <a href="#cat-{{ $category->id }}" class="cat-pill {{ $index === 0 ? 'active' : '' }}">
                @if($category->image)
                    <img src="{{ $category->getImageUrl() }}" style="width:20px;height:20px;border-radius:4px;margin-left:5px;">
                @elseif($category->icon)
                    <i class="{{ $category->icon }}" style="margin-left:5px;"></i>
                @endif
                {{ $category->getName() }}
            </a>
            @endforeach
        </nav>
        @endif
        
        <!-- Menu Items -->
        <main class="menu-section">
            @forelse($categories ?? [] as $category)
                @if($category->menuItems && $category->menuItems->count() > 0)
                <section id="cat-{{ $category->id }}" class="category-block">
                    <h3 class="category-title">
                        @if($category->image)
                            <img src="{{ $category->getImageUrl() }}" alt="{{ $category->getName() }}">
                        @elseif($category->icon)
                            <i class="{{ $category->icon }}"></i>
                        @endif
                        {{ $category->getName() }}
                    </h3>
                    
                    @foreach($category->menuItems as $item)
                    <article class="menu-item">
                        @if($item->getImageUrl())
                            <img src="{{ $item->getImageUrl() }}" alt="{{ $item->getName() }}" class="item-img" loading="lazy">
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
                            
                            {{-- Tags --}}
                            @if($item->tags && $item->tags->count() > 0)
                            <div class="item-tags">
                                @foreach($item->tags as $tag)
                                <span class="item-tag" style="background-color: {{ $tag->bg_color }}; color: {{ $tag->color }};">
                                    {{ $tag->icon }} {{ $locale === 'ar' ? $tag->name_ar : $tag->name_en }}
                                </span>
                                @endforeach
                            </div>
                            @endif
                            
                            @if($item->getDescription())
                            <p class="item-desc">{{ $item->getDescription() }}</p>
                            @endif
                            
                            <div class="item-footer">
                                <span class="price-current">{{ $restaurant->getFormattedPrice($item->price) }}</span>
                                @if($item->hasDiscount())
                                <span class="price-old">{{ $restaurant->getFormattedPrice($item->old_price) }}</span>
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
        
        {{-- Simple Footer with back link --}}
        <footer class="menu-footer">
            <a href="{{ route('menu.landing', $restaurant->slug) }}" class="footer-back-btn">
                <i class="fas fa-home"></i>
                {{ $locale === 'ar' ? 'الصفحة الرئيسية' : 'Back to Home' }}
            </a>
        </footer>
    </div>
    
    <script>
        // Category navigation
        document.querySelectorAll('.cat-pill').forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const nav = document.querySelector('.categories-nav');
                    window.scrollTo({ 
                        top: target.offsetTop - nav.offsetHeight - 10, 
                        behavior: 'smooth' 
                    });
                }
                document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Update active category on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.category-block');
            const nav = document.querySelector('.categories-nav');
            const navHeight = nav ? nav.offsetHeight : 0;
            
            sections.forEach(section => {
                const rect = section.getBoundingClientRect();
                if (rect.top <= navHeight + 50 && rect.bottom >= navHeight) {
                    const id = section.getAttribute('id');
                    document.querySelectorAll('.cat-pill').forEach(pill => {
                        pill.classList.remove('active');
                        if (pill.getAttribute('href') === '#' + id) {
                            pill.classList.add('active');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>