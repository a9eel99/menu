@extends('layouts.admin')

@section('title', app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en)
@section('page-title', app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en)

@push('styles')
<style>
    /* ========== RESTAURANT SHOW STYLES ========== */
    
    /* Restaurant Header */
    .restaurant-hero {
        background: linear-gradient(135deg, var(--secondary, #1e293b) 0%, #0f172a 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
    }
    
    .hero-content {
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }
    
    .hero-logo {
        width: 90px;
        height: 90px;
        border-radius: 16px;
        overflow: hidden;
        background: rgba(255,255,255,0.1);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255,255,255,0.2);
    }
    
    .hero-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .hero-logo i {
        font-size: 2rem;
        color: rgba(255,255,255,0.7);
    }
    
    .hero-info {
        flex: 1;
        min-width: 200px;
    }
    
    .hero-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .hero-title .branch-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        background: rgba(59, 130, 246, 0.3);
        border-radius: 20px;
        font-weight: 500;
    }
    
    .hero-subtitle {
        opacity: 0.7;
        font-size: 0.95rem;
        margin-bottom: 4px;
    }
    
    .hero-parent {
        font-size: 0.8rem;
        opacity: 0.5;
    }
    
    .hero-stats {
        display: flex;
        gap: 16px;
    }
    
    .hero-stat {
        text-align: center;
        padding: 12px 20px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        min-width: 80px;
    }
    
    .hero-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 4px;
    }
    
    .hero-stat-label {
        font-size: 0.75rem;
        opacity: 0.7;
        text-transform: uppercase;
    }
    
    .hero-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .hero-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .hero-btn.primary {
        background: var(--primary);
        color: white;
    }
    
    .hero-btn.primary:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        color: white;
    }
    
    .hero-btn.light {
        background: rgba(255,255,255,0.15);
        color: white;
    }
    
    .hero-btn.light:hover {
        background: rgba(255,255,255,0.25);
        color: white;
    }
    
    /* Navigation Tabs */
    .nav-tabs-modern {
        display: flex;
        gap: 8px;
        padding: 4px;
        background: white;
        border-radius: 14px;
        margin-bottom: 24px;
        overflow-x: auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }
    
    .nav-tabs-modern::-webkit-scrollbar {
        display: none;
    }
    
    .nav-tab-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        white-space: nowrap;
        transition: all 0.2s ease;
        border: none;
        background: transparent;
        cursor: pointer;
    }
    
    .nav-tab-item:hover {
        color: var(--primary);
        background: var(--primary-light);
    }
    
    .nav-tab-item.active {
        background: var(--primary);
        color: white;
    }
    
    .nav-tab-item i {
        font-size: 1rem;
    }
    
    /* Content Card */
    .content-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
    }
    
    .content-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .content-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        font-size: 1rem;
        color: #0f172a;
    }
    
    .content-card-title i {
        color: var(--primary);
    }
    
    .content-card-body {
        padding: 24px;
    }
    
    /* Add Button */
    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-add:hover {
        filter: brightness(1.1);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Info Tip */
    .info-tip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #f0f9ff;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 0.85rem;
        color: #0369a1;
    }
    
    .info-tip i {
        color: #0ea5e9;
    }
    
    /* Table Modern */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-modern thead th {
        padding: 14px 16px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #64748b;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table-modern tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }
    
    .table-modern tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Category Row */
    .category-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .category-image {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .category-image i {
        font-size: 1.1rem;
        color: #94a3b8;
    }
    
    .category-info strong {
        display: block;
        color: #0f172a;
        font-size: 0.95rem;
    }
    
    .category-info small {
        color: #94a3b8;
        font-size: 0.8rem;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.active {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-badge.inactive {
        background: #f1f5f9;
        color: #64748b;
    }
    
    /* Count Badge */
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        padding: 0 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .count-badge.primary {
        background: #eff6ff;
        color: #3b82f6;
    }
    
    .count-badge.success {
        background: #ecfdf5;
        color: #059669;
    }
    
    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #e2e8f0;
        background: white;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    
    .action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    
    .action-btn.danger:hover {
        border-color: #ef4444;
        color: #ef4444;
        background: #fef2f2;
    }
    
    /* Sortable Handle */
    .sortable-handle {
        cursor: grab;
        padding: 8px;
        color: #cbd5e1;
        transition: color 0.2s;
    }
    
    .sortable-handle:hover {
        color: #64748b;
    }
    
    .sortable-handle:active {
        cursor: grabbing;
    }
    
    .sortable-ghost {
        background: #eff6ff !important;
        opacity: 0.8;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .empty-state-icon i {
        font-size: 2rem;
        color: #94a3b8;
    }
    
    .empty-state h5 {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        color: #64748b;
        margin-bottom: 20px;
    }
    
    /* Item Card */
    .item-card-modern {
        display: flex;
        gap: 16px;
        padding: 16px;
        background: white;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    
    .item-card-modern:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    
    .item-card-modern.unavailable {
        opacity: 0.6;
    }
    
    .item-image {
        width: 70px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        background: #f1f5f9;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .item-image i {
        font-size: 1.5rem;
        color: #cbd5e1;
    }
    
    .item-content {
        flex: 1;
        min-width: 0;
    }
    
    .item-name {
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .item-name .unavailable-badge {
        font-size: 0.7rem;
        padding: 2px 8px;
        background: #fef2f2;
        color: #ef4444;
        border-radius: 10px;
    }
    
    .item-name .featured-star {
        color: #f59e0b;
    }
    
    .item-price {
        font-weight: 700;
        color: var(--primary);
    }
    
    .item-actions {
        display: flex;
        align-items: center;
    }
    
    /* Category Section */
    .category-section {
        background: #f8fafc;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .category-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .category-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #0f172a;
    }
    
    .category-section-title .count {
        font-size: 0.8rem;
        padding: 4px 10px;
        background: #e2e8f0;
        border-radius: 20px;
        color: #64748b;
    }
    
    /* Social Links Form */
    .social-input-group {
        margin-bottom: 20px;
    }
    
    .social-input-group label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .social-input-group label i.fa-instagram { color: #E4405F; }
    .social-input-group label i.fa-facebook { color: #1877F2; }
    .social-input-group label i.fa-x-twitter { color: #000; }
    .social-input-group label i.fa-tiktok { color: #000; }
    .social-input-group label i.fa-snapchat { color: #FFFC00; }
    .social-input-group label i.fa-youtube { color: #FF0000; }
    
    /* Settings Cards */
    .settings-card {
        background: white;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
    }
    
    .settings-card-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .settings-card-header i {
        color: var(--primary);
    }
    
    .settings-card-body {
        padding: 20px;
    }
    
    .settings-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .settings-row:last-child {
        border-bottom: none;
    }
    
    .settings-label {
        width: 140px;
        color: #64748b;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .settings-value {
        flex: 1;
        color: #0f172a;
    }
    
    .color-preview {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .color-preview span {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: inline-block;
    }
    
    /* Danger Zone */
    .danger-card {
        background: white;
        border-radius: 14px;
        border: 1.5px solid #fecaca;
        overflow: hidden;
    }
    
    .danger-card-header {
        padding: 16px 20px;
        background: #fef2f2;
        border-bottom: 1px solid #fecaca;
        font-weight: 600;
        color: #dc2626;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .danger-card-body {
        padding: 20px;
    }
    
    .danger-card p {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 16px;
    }
    
    .btn-danger-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 1.5px solid #fecaca;
        border-radius: 10px;
        color: #ef4444;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-danger-outline:hover {
        background: #fef2f2;
        border-color: #ef4444;
    }
    
    /* Toast */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: white;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 9999;
    }
    
    .toast-notification.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    
    .toast-notification.success i { color: #10b981; }
    .toast-notification.error { background: #ef4444; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-content {
            flex-direction: column;
            text-align: center;
        }
        
        .hero-stats {
            width: 100%;
            justify-content: center;
        }
        
        .hero-actions {
            width: 100%;
            justify-content: center;
        }
        
        .nav-tabs-modern {
            flex-wrap: nowrap;
            overflow-x: auto;
        }
    }
</style>
@endpush

@section('content')
{{-- Restaurant Hero Header --}}
<div class="restaurant-hero">
    <div class="hero-content">
        <div class="hero-logo">
            @if($restaurant->logo)
                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name_ar }}">
            @else
                <i class="fas fa-store"></i>
            @endif
        </div>
        
        <div class="hero-info">
            <h1 class="hero-title">
                {{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}
                @if($restaurant->isBranch())
                    <span class="branch-badge">{{ __('app.branch') }}</span>
                @endif
            </h1>
            <p class="hero-subtitle">{{ app()->getLocale() == 'ar' ? $restaurant->name_en : $restaurant->name_ar }}</p>
            @if($restaurant->isBranch() && $restaurant->parent)
                <p class="hero-parent">{{ __('app.parent_restaurant') }}: {{ app()->getLocale() == 'ar' ? $restaurant->parent->name_ar : $restaurant->parent->name_en }}</p>
            @endif
        </div>
        
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $restaurant->categories_count ?? $restaurant->categories()->count() }}</div>
                <div class="hero-stat-label">{{ __('app.categories') }}</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $restaurant->menu_items_count ?? $restaurant->menuItems()->count() }}</div>
                <div class="hero-stat-label">{{ __('app.items') }}</div>
            </div>
            @if($restaurant->isMain())
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $restaurant->branches_count ?? $restaurant->branches()->count() }}</div>
                <div class="hero-stat-label">{{ __('app.branches') }}</div>
            </div>
            @endif
        </div>
        
        <div class="hero-actions">
            <a href="{{ route('admin.restaurants.copy', $restaurant) }}" class="hero-btn primary">
                <i class="fas fa-copy"></i>
                {{ __('app.copy_menu') }}
            </a>
            <a href="{{ $restaurant->getMenuUrl() }}" target="_blank" class="hero-btn light">
                <i class="fas fa-external-link-alt"></i>
                {{ __('app.view_menu') }}
            </a>
            <a href="{{ route('admin.restaurants.qrcode', $restaurant) }}" class="hero-btn light">
                <i class="fas fa-qrcode"></i>
            </a>
        </div>
    </div>
</div>

{{-- Navigation Tabs --}}
<nav class="nav-tabs-modern" id="restaurantTabs" role="tablist">
    <button class="nav-tab-item active" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">
        <i class="fas fa-folder"></i>
        {{ __('app.categories') }}
    </button>
    <button class="nav-tab-item" id="items-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab">
        <i class="fas fa-utensils"></i>
        {{ __('app.items') }}
    </button>
    @if($restaurant->isMain())
    <button class="nav-tab-item" id="branches-tab" data-bs-toggle="tab" data-bs-target="#branches" type="button" role="tab">
        <i class="fas fa-code-branch"></i>
        {{ __('app.branches') }}
    </button>
    @endif
    <button class="nav-tab-item" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
        <i class="fas fa-share-alt"></i>
        {{ __('app.social_links') }}
    </button>
    <button class="nav-tab-item" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
        <i class="fas fa-cog"></i>
        {{ __('app.settings') }}
    </button>
</nav>

{{-- Tab Content --}}
<div class="tab-content" id="restaurantTabsContent">
    {{-- Categories Tab --}}
    <div class="tab-pane fade show active" id="categories" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-folder"></i>
                    {{ __('app.menu_categories') }}
                </div>
                <a href="{{ route('admin.restaurants.categories.create', $restaurant) }}" class="btn-add">
                    <i class="fas fa-plus"></i>
                    {{ __('app.add_category') }}
                </a>
            </div>
            <div class="content-card-body">
                @if($restaurant->categories->count() > 0)
                    <div class="info-tip">
                        <i class="fas fa-info-circle"></i>
                        {{ __('app.drag_to_reorder') }}
                    </div>
                    
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>{{ __('app.category') }}</th>
                                <th>{{ __('app.items') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody id="categories-sortable">
                            @foreach($restaurant->categories()->orderBy('sort_order')->get() as $category)
                            <tr data-id="{{ $category->id }}">
                                <td>
                                    <span class="sortable-handle" title="{{ __('app.drag_to_reorder') }}">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                </td>
                                <td>
                                    <div class="category-cell">
                                        <div class="category-image">
                                            @if($category->image)
                                                <img src="{{ asset('storage/' . $category->image) }}" alt="">
                                            @elseif($category->icon)
                                                <i class="{{ $category->icon }}"></i>
                                            @else
                                                <i class="fas fa-folder"></i>
                                            @endif
                                        </div>
                                        <div class="category-info">
                                            <strong>{{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}</strong>
                                            <small>{{ app()->getLocale() == 'ar' ? $category->name_en : $category->name_ar }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="count-badge primary">{{ $category->menuItems()->count() }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $category->is_active ? 'active' : 'inactive' }}">
                                        {{ $category->is_active ? __('app.active') : __('app.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.restaurants.categories.edit', [$restaurant, $category]) }}" class="action-btn" title="{{ __('app.edit') }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form action="{{ route('admin.restaurants.categories.destroy', [$restaurant, $category]) }}" method="POST" class="d-inline" data-delete-modal data-delete-message="{{ __('app.confirm_delete') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn danger" title="{{ __('app.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        <h5>{{ __('app.no_categories') }}</h5>
                        <p>{{ __('app.add_first_category') }}</p>
                        <a href="{{ route('admin.restaurants.categories.create', $restaurant) }}" class="btn-add">
                            <i class="fas fa-plus"></i>
                            {{ __('app.add_category') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Items Tab --}}
    <div class="tab-pane fade" id="items" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-utensils"></i>
                    {{ __('app.menu_items') }}
                </div>
                <a href="{{ route('admin.restaurants.items.create', $restaurant) }}" class="btn-add">
                    <i class="fas fa-plus"></i>
                    {{ __('app.add_item') }}
                </a>
            </div>
            <div class="content-card-body">
                @if($restaurant->menuItems->count() > 0)
                    <div class="info-tip">
                        <i class="fas fa-info-circle"></i>
                        {{ __('app.drag_items_to_reorder') }}
                    </div>
                    
                    @foreach($restaurant->categories as $category)
                        @if($category->menuItems->count() > 0)
                        <div class="category-section">
                            <div class="category-section-header">
                                <div class="category-section-title">
                                    @if($category->icon)<i class="{{ $category->icon }}"></i>@endif
                                    {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                                    <span class="count">{{ $category->menuItems->count() }}</span>
                                </div>
                            </div>
                            
                            <div class="items-sortable" data-category="{{ $category->id }}">
                                @foreach($category->menuItems()->orderBy('sort_order')->get() as $item)
                                <div class="item-card-modern {{ !$item->is_available ? 'unavailable' : '' }}" data-id="{{ $item->id }}">
                                    <span class="sortable-handle">
                                        <i class="fas fa-grip-vertical"></i>
                                    </span>
                                    
                                    <div class="item-image">
                                        @if($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="">
                                        @else
                                            <i class="fas fa-utensils"></i>
                                        @endif
                                    </div>
                                    
                                    <div class="item-content">
                                        <div class="item-name">
                                            {{ app()->getLocale() == 'ar' ? $item->name_ar : $item->name_en }}
                                            @if(!$item->is_available)
                                                <span class="unavailable-badge">{{ __('app.unavailable') }}</span>
                                            @endif
                                            @if($item->is_featured)
                                                <i class="fas fa-star featured-star"></i>
                                            @endif
                                        </div>
                                        <div class="item-price">{{ number_format($item->price, 2) }} {{ $restaurant->currency_symbol }}</div>
                                    </div>
                                    
                                    <div class="item-actions">
                                        <a href="{{ route('admin.restaurants.items.edit', [$restaurant, $item]) }}" class="action-btn" title="{{ __('app.edit') }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h5>{{ __('app.no_items') }}</h5>
                        <p>{{ __('app.add_items_to_menu') }}</p>
                        <a href="{{ route('admin.restaurants.items.create', $restaurant) }}" class="btn-add">
                            <i class="fas fa-plus"></i>
                            {{ __('app.add_item') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    {{-- Branches Tab --}}
    @if($restaurant->isMain())
    <div class="tab-pane fade" id="branches" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-code-branch"></i>
                    {{ __('app.restaurant_branches') }}
                </div>
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.restaurants.create') }}?parent_id={{ $restaurant->id }}" class="btn-add">
                    <i class="fas fa-plus"></i>
                    {{ __('app.add_branch') }}
                </a>
                @endif
            </div>
            <div class="content-card-body">
                @if($restaurant->branches->count() > 0)
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('app.branch') }}</th>
                                <th>{{ __('app.categories') }}</th>
                                <th>{{ __('app.items') }}</th>
                                <th>{{ __('app.status') }}</th>
                                <th>{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($restaurant->branches as $branch)
                            <tr>
                                <td>
                                    <div class="category-info">
                                        <strong>{{ app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en }}</strong>
                                        <small>{{ app()->getLocale() == 'ar' ? $branch->name_en : $branch->name_ar }}</small>
                                    </div>
                                </td>
                                <td><span class="count-badge primary">{{ $branch->categories()->count() }}</span></td>
                                <td><span class="count-badge success">{{ $branch->menuItems()->count() }}</span></td>
                                <td>
                                    <span class="status-badge {{ $branch->is_active ? 'active' : 'inactive' }}">
                                        {{ $branch->is_active ? __('app.active') : __('app.inactive') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <a href="{{ route('admin.restaurants.show', $branch) }}" class="action-btn" title="{{ __('app.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.restaurants.edit', $branch) }}" class="action-btn" title="{{ __('app.edit') }}">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        @endif
                                        <a href="{{ $branch->getMenuUrl() }}" target="_blank" class="action-btn" title="{{ __('app.view_menu') }}">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-code-branch"></i>
                        </div>
                        <h5>{{ __('app.no_branches') }}</h5>
                        @if(auth()->user()->hasRole('admin'))
                        <p>{{ __('app.add_branches_desc') }}</p>
                        <a href="{{ route('admin.restaurants.create') }}?parent_id={{ $restaurant->id }}" class="btn-add">
                            <i class="fas fa-plus"></i>
                            {{ __('app.add_branch') }}
                        </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
    
    {{-- Social Links Tab --}}
    <div class="tab-pane fade" id="social" role="tabpanel">
        <div class="content-card">
            <div class="content-card-header">
                <div class="content-card-title">
                    <i class="fas fa-share-alt"></i>
                    {{ __('app.social_links') }}
                </div>
            </div>
            <div class="content-card-body">
                <form action="{{ route('admin.restaurants.social.update', $restaurant) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php $socialLinks = $restaurant->socialLinks->keyBy('platform'); @endphp
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-instagram"></i> Instagram</label>
                                <input type="url" name="social[instagram]" class="form-control" value="{{ $socialLinks->get('instagram')->url ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-facebook"></i> Facebook</label>
                                <input type="url" name="social[facebook]" class="form-control" value="{{ $socialLinks->get('facebook')->url ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-x-twitter"></i> X (Twitter)</label>
                                <input type="url" name="social[twitter]" class="form-control" value="{{ $socialLinks->get('twitter')->url ?? '' }}" placeholder="https://x.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-tiktok"></i> TikTok</label>
                                <input type="url" name="social[tiktok]" class="form-control" value="{{ $socialLinks->get('tiktok')->url ?? '' }}" placeholder="https://tiktok.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-snapchat"></i> Snapchat</label>
                                <input type="url" name="social[snapchat]" class="form-control" value="{{ $socialLinks->get('snapchat')->url ?? '' }}" placeholder="https://snapchat.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="social-input-group">
                                <label><i class="fab fa-youtube"></i> YouTube</label>
                                <input type="url" name="social[youtube]" class="form-control" value="{{ $socialLinks->get('youtube')->url ?? '' }}" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-add">
                        <i class="fas fa-save"></i>
                        {{ __('app.save') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Settings Tab --}}
    <div class="tab-pane fade" id="settings" role="tabpanel">
        <div class="row">
            <div class="col-md-6">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="fas fa-info-circle"></i>
                        {{ __('app.restaurant_info') }}
                    </div>
                    <div class="settings-card-body">
                        @if($restaurant->phone)
                        <div class="settings-row">
                            <div class="settings-label"><i class="fas fa-phone"></i> {{ __('app.phone') }}</div>
                            <div class="settings-value">{{ $restaurant->phone }}</div>
                        </div>
                        @endif
                        @if($restaurant->getAddress())
                        <div class="settings-row">
                            <div class="settings-label"><i class="fas fa-map-marker-alt text-danger"></i> {{ __('app.address') }}</div>
                            <div class="settings-value">{{ $restaurant->getAddress() }}</div>
                        </div>
                        @endif
                        <div class="settings-row">
                            <div class="settings-label"><i class="fas fa-link"></i> {{ __('app.menu_link') }}</div>
                            <div class="settings-value"><code style="font-size: 0.8rem;">{{ $restaurant->getMenuUrl() }}</code></div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="btn-add">
                                <i class="fas fa-edit"></i>
                                {{ __('app.edit_info') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <i class="fas fa-palette"></i>
                        {{ __('app.appearance') }}
                    </div>
                    <div class="settings-card-body">
                        <div class="settings-row">
                            <div class="settings-label">{{ __('app.menu_type') }}</div>
                            <div class="settings-value">
                                @if($restaurant->menu_type == 'pdf')
                                    <span class="status-badge" style="background: #fef2f2; color: #dc2626;">
                                        <i class="fas fa-file-pdf"></i> {{ __('app.pdf_menu') }}
                                    </span>
                                    @if($restaurant->menu_pdf)
                                        <a href="{{ $restaurant->getMenuPdfUrl() }}" target="_blank" class="ms-2" style="font-size: 0.8rem;">{{ __('app.view_current_pdf') }}</a>
                                    @endif
                                @else
                                    <span class="status-badge active">
                                        <i class="fas fa-list-alt"></i> {{ __('app.digital_menu') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-label">{{ __('app.currency') }}</div>
                            <div class="settings-value">{{ $restaurant->currency }} ({{ $restaurant->currency_symbol }})</div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-label">{{ __('app.primary_color') }}</div>
                            <div class="settings-value">
                                <span class="color-preview">
                                    <span style="background: {{ $restaurant->primary_color }}"></span>
                                    {{ $restaurant->primary_color }}
                                </span>
                            </div>
                        </div>
                        <div class="settings-row">
                            <div class="settings-label">{{ __('app.secondary_color') }}</div>
                            <div class="settings-value">
                                <span class="color-preview">
                                    <span style="background: {{ $restaurant->secondary_color }}"></span>
                                    {{ $restaurant->secondary_color }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="danger-card">
                    <div class="danger-card-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ __('app.danger_zone') }}
                    </div>
                    <div class="danger-card-body">
                        <p>{{ __('app.delete_restaurant_warning') }}</p>
                        <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" method="POST" data-delete-modal data-delete-message="{{ __('app.confirm_delete_restaurant') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-danger-outline">
                                <i class="fas fa-trash"></i>
                                {{ __('app.delete_restaurant') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification --}}
<div class="toast-notification" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Tab persistence
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (tab) new bootstrap.Tab(tab).show();
    }
    
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function (e) {
            history.replaceState(null, null, e.target.dataset.bsTarget);
        });
    });
    
    // Toast function
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        toastMessage.textContent = message;
        toast.className = 'toast-notification show ' + type;
        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }
    
    // Categories sortable
    const categoriesSortable = document.getElementById('categories-sortable');
    if (categoriesSortable) {
        new Sortable(categoriesSortable, {
            handle: '.sortable-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const rows = categoriesSortable.querySelectorAll('tr[data-id]');
                const categories = [];
                rows.forEach((row) => categories.push(parseInt(row.dataset.id)));
                
                fetch('{{ route("admin.restaurants.categories.reorder", $restaurant) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ categories: categories })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) showToast('{{ __("app.order_saved") }}');
                    else showToast('{{ __("app.error") }}', 'error');
                })
                .catch(() => showToast('{{ __("app.connection_error") }}', 'error'));
            }
        });
    }
    
    // Items sortable
    document.querySelectorAll('.items-sortable').forEach(container => {
        new Sortable(container, {
            handle: '.sortable-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: function(evt) {
                const items = [];
                container.querySelectorAll('.item-card-modern[data-id]').forEach((item) => items.push(parseInt(item.dataset.id)));
                
                fetch('{{ route("admin.restaurants.items.reorder", $restaurant) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ items: items })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) showToast('{{ __("app.order_saved") }}');
                    else showToast('{{ __("app.error") }}', 'error');
                })
                .catch(() => showToast('{{ __("app.connection_error") }}', 'error'));
            }
        });
    });
</script>
@endpush