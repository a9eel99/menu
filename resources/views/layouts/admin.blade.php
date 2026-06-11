<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
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
    
    <title>@yield('title', __('app.dashboard')) - {{ $siteName }}</title>
    
    @if($siteFavicon)
    <link rel="icon" href="{{ asset('storage/' . $siteFavicon) }}" type="image/png">
    @endif
    
    @if(app()->getLocale() === 'ar')
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
    @else
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fonts.css') }}">
    {{-- Admin Layout CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-layout.css') }}">
    {{-- Shared Components CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/css/components.css') }}">
    
    <style>
        :root {
            --primary: {{ $primaryColor }};
            --primary-light: {{ $primaryColor }}15;
            --primary-dark: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --secondary-light: #1e293b;
            --sidebar-width: 280px;
            --topbar-height: 70px;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #3b82f6;
        }
        
        * {
            font-family: {{ app()->getLocale() == 'ar' ? "'Cairo'" : "'Inter'" }}, sans-serif;
        }
        
        /* RTL/LTR Specific */
        .sidebar {
            {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
        }
        
        .topbar {
            {{ app()->getLocale() === 'ar' ? 'left: 0; right: var(--sidebar-width)' : 'left: var(--sidebar-width); right: 0' }};
        }
        
        .main-content {
            {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: var(--sidebar-width);
        }
        
        @media (max-width: 991px) {
            .sidebar {
                transform: translate{{ app()->getLocale() === 'ar' ? 'X(100%)' : 'X(-100%)' }};
            }
            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $user = Auth::user();
        $isAdmin = $user->isAdmin();
        $isStaff = $user->isStaff();
        
        if ($isAdmin) {
            $userRestaurants = \App\Models\Restaurant::whereNull('parent_id')
                ->with(['branches'])
                ->orderBy('name_ar')
                ->get();
        } elseif ($user->restaurant_id) {
            $restaurant = \App\Models\Restaurant::with('branches')->find($user->restaurant_id);
            if ($restaurant) {
                if ($restaurant->parent_id) {
                    $parent = $restaurant->parent()->with('branches')->first();
                    $userRestaurants = $parent ? collect([$parent]) : collect();
                } else {
                    $userRestaurants = collect([$restaurant]);
                }
            } else {
                $userRestaurants = collect();
            }
        } else {
            $userRestaurants = collect();
        }
        
        $currentRestaurant = null;
        if (request()->route('restaurant')) {
            $currentRestaurant = request()->route('restaurant');
        }
        
        $canAddRestaurant = $isAdmin;
        $canManageStaff = $isAdmin;
    @endphp

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}">
                @else
                    <i class="fas fa-utensils"></i>
                @endif
            </div>
            <span class="sidebar-brand">{{ $siteName }}</span>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">{{ __('app.main_menu') }}</div>
            
            <div class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('app.home') }}</span>
                </a>
            </div>
            
            <div class="nav-section">{{ __('app.restaurants') }}</div>
            
            @forelse($userRestaurants as $restaurant)
                <div class="restaurant-group">
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" 
                       class="restaurant-link {{ $currentRestaurant && $currentRestaurant->id == $restaurant->id ? 'active' : '' }}">
                        @if($restaurant->logo)
                            <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="">
                        @else
                            <div class="placeholder-img"><i class="fas fa-store"></i></div>
                        @endif
                        <span class="name">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</span>
                    </a>
                    
                    @foreach($restaurant->branches as $branch)
                        <a href="{{ route('admin.restaurants.show', $branch) }}" 
                           class="restaurant-link branch-link {{ $currentRestaurant && $currentRestaurant->id == $branch->id ? 'active' : '' }}">
                            <i class="fas fa-code-branch"></i>
                            <span class="name">{{ app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en }}</span>
                        </a>
                    @endforeach
                </div>
            @empty
                <div class="nav-item">
                    <span class="nav-link" style="opacity: 0.5; cursor: default;">
                        <i class="fas fa-info-circle"></i>
                        <span>{{ __('app.no_restaurants') }}</span>
                    </span>
                </div>
            @endforelse
            
            @if($canAddRestaurant)
                <div class="nav-item">
                    <a href="{{ route('admin.restaurants.create') }}" class="nav-link {{ request()->routeIs('admin.restaurants.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>{{ __('app.add_restaurant') }}</span>
                    </a>
                </div>
            @endif
            
            @if($canManageStaff)
                <div class="nav-section">{{ __('app.settings') }}</div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.staff.index') }}" class="nav-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i>
                        <span>{{ __('app.staff') }}</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>{{ __('app.system_settings') }}</span>
                    </a>
                </div>
            @endif
        </nav>
        
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>{{ __('app.logout') }}</span>
                </button>
            </form>
            
            <!-- Collapse Button -->
            <button type="button" class="collapse-btn" id="collapseBtn" onclick="toggleSidebar()">
                <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}" id="collapseIcon"></i>
                <span>{{ __('app.collapse_menu') }}</span>
            </button>
        </div>
    </aside>
    
    <div class="overlay" id="overlay"></div>
    
    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-title">
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1>@yield('page-title', __('app.dashboard'))</h1>
        </div>
        
        <div class="topbar-actions">
            @if(app()->getLocale() == 'ar')
                <a href="{{ route('language.switch', 'en') }}" class="lang-btn">
                    <i class="fas fa-globe"></i>
                    <span>English</span>
                </a>
            @else
                <a href="{{ route('language.switch', 'ar') }}" class="lang-btn">
                    <i class="fas fa-globe"></i>
                    <span>العربية</span>
                </a>
            @endif
            
            <div class="user-dropdown dropdown">
                <button class="user-btn dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user-avatar">
                        {{ mb_substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-info d-none d-md-block">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ $isAdmin ? __('app.admin') : __('app.staff_member') }}</div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                {{ __('app.logout') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Tooltip Element -->
    <div class="sidebar-tooltip" id="sidebarTooltip"></div>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- Shared Components JS --}}
    <script src="{{ asset('assets/admin/js/components.js') }}"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const collapseIcon = document.getElementById('collapseIcon');
        const tooltip = document.getElementById('sidebarTooltip');
        const isRtl = document.dir === 'rtl';
        
        // Check saved state
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            document.body.classList.add('sidebar-collapsed');
            updateCollapseIcon(true);
        }
        
        // Toggle sidebar collapse
        function toggleSidebar() {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-collapsed');
            
            // Save state
            localStorage.setItem('sidebarCollapsed', isCollapsed);
            
            // Update icon
            updateCollapseIcon(isCollapsed);
        }
        
        function updateCollapseIcon(isCollapsed) {
            if (collapseIcon) {
                if (isCollapsed) {
                    collapseIcon.className = isRtl ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
                } else {
                    collapseIcon.className = isRtl ? 'fas fa-chevron-left' : 'fas fa-chevron-right';
                }
            }
        }
        
        // ========== TOOLTIP SYSTEM ==========
        function showTooltip(element, text) {
            if (!sidebar.classList.contains('collapsed')) return;
            
            const rect = element.getBoundingClientRect();
            tooltip.textContent = text;
            tooltip.classList.add('show');
            
            // Position tooltip
            const top = rect.top + (rect.height / 2) - (tooltip.offsetHeight / 2);
            
            if (isRtl) {
                tooltip.style.right = (window.innerWidth - rect.left + 15) + 'px';
                tooltip.style.left = 'auto';
            } else {
                tooltip.style.left = (rect.right + 15) + 'px';
                tooltip.style.right = 'auto';
            }
            tooltip.style.top = top + 'px';
        }
        
        function hideTooltip() {
            tooltip.classList.remove('show');
        }
        
        // Add tooltip listeners to all sidebar items
        document.querySelectorAll('.sidebar .nav-link, .sidebar .restaurant-link, .sidebar .logout-btn, .sidebar .collapse-btn').forEach(item => {
            const text = item.querySelector('span')?.textContent || 
                        item.querySelector('.name')?.textContent || 
                        item.getAttribute('title') || '';
            
            item.addEventListener('mouseenter', () => showTooltip(item, text));
            item.addEventListener('mouseleave', hideTooltip);
        });
        
        // ========== MOBILE SIDEBAR ==========
        const mobileToggle = document.getElementById('mobileToggle');
        const overlay = document.getElementById('overlay');
        
        function toggleMobileSidebar() {
            sidebar.classList.toggle('show');
            overlay?.classList.toggle('show');
            document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
        }
        
        function closeMobileSidebar() {
            sidebar.classList.remove('show');
            overlay?.classList.remove('show');
            document.body.style.overflow = '';
        }
        
        mobileToggle?.addEventListener('click', toggleMobileSidebar);
        overlay?.addEventListener('click', closeMobileSidebar);
        
        // Close on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>