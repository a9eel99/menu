@extends('layouts.admin')

@section('title', __('app.dashboard'))
@section('page-title', __('app.dashboard'))

@push('styles')
<style>
    /* Dashboard Header */
    .dashboard-header {
        margin-bottom: 30px;
    }
    
    .dashboard-header h2 {
        font-size: 1.75rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }
    
    .dashboard-header p {
        color: #64748b;
        margin: 0;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
    
    /* Stat Card */
    .stat-card-new {
        background: white;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    
    .stat-card-new:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.08);
    }
    
    .stat-card-new .stat-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .stat-card-new .stat-icon-box i {
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-card-new .stat-icon-box.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .stat-card-new .stat-icon-box.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .stat-card-new .stat-icon-box.green { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-card-new .stat-icon-box.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    
    .stat-card-new .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }
    
    .stat-card-new .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
    }
    
    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
    }
    
    .section-title i { color: #64748b; }
    
    /* Restaurant Card */
    .restaurant-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    
    .restaurant-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    
    .restaurant-main {
        display: flex;
        align-items: center;
        padding: 20px 24px;
        gap: 16px;
    }
    
    .restaurant-logo {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .restaurant-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .restaurant-logo i {
        font-size: 1.5rem;
        color: #94a3b8;
    }
    
    .restaurant-info { flex: 1; min-width: 0; }
    
    .restaurant-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 4px;
    }
    
    .restaurant-name-en {
        font-size: 0.85rem;
        color: #64748b;
    }
    
    .restaurant-stats {
        display: flex;
        gap: 24px;
    }
    
    .restaurant-stat { text-align: center; }
    
    .restaurant-stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }
    
    .restaurant-stat-label {
        font-size: 0.75rem;
        color: #94a3b8;
        text-transform: uppercase;
    }
    
    .restaurant-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .restaurant-status.active {
        background: #ecfdf5;
        color: #059669;
    }
    
    .restaurant-status.inactive {
        background: #f1f5f9;
        color: #64748b;
    }
    
    .restaurant-actions {
        display: flex;
        gap: 8px;
    }
    
    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid #e2e8f0;
        background: white;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    
    .action-btn.primary {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    
    .action-btn.primary:hover {
        background: var(--primary);
        border-color: var(--primary);
        filter: brightness(1.1);
        color: white;
    }
    
    /* Branch Row */
    .branch-row {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        padding-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: 100px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        gap: 16px;
    }
    
    .branch-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 0.8rem;
    }
    
    .branch-info { flex: 1; }
    
    .branch-name {
        font-weight: 600;
        color: #334155;
        font-size: 0.95rem;
    }
    
    .branch-name-en {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    
    .empty-state-icon i {
        font-size: 2.5rem;
        color: #94a3b8;
    }
    
    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        color: #64748b;
        margin-bottom: 24px;
    }
    
    /* Info Alert */
    .info-alert {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
        background: #eff6ff;
        border-radius: 14px;
        margin-top: 24px;
        border: 1px solid #bfdbfe;
    }
    
    .info-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }
    
    .info-alert-content strong { color: #1e40af; }
    .info-alert-content span { color: #3b82f6; }
    
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
        background: var(--primary);
        filter: brightness(1.1);
        color: white;
        transform: translateY(-2px);
    }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 30px;
    }
    
    @media (max-width: 992px) {
        .quick-actions { grid-template-columns: repeat(2, 1fr); }
    }
    
    .quick-action-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    
    .quick-action-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    
    .quick-action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .quick-action-icon.primary { background: var(--primary-light); color: var(--primary); }
    .quick-action-icon.teal { background: #f0fdfa; color: #0d9488; }
    .quick-action-icon.blue { background: #eff6ff; color: #3b82f6; }
    .quick-action-icon.orange { background: #fff7ed; color: #f59e0b; }
    .quick-action-icon.purple { background: #faf5ff; color: #8b5cf6; }
    
    .quick-action-text {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }
    
    @media (max-width: 768px) {
        .restaurant-main { flex-wrap: wrap; }
        .restaurant-stats {
            width: 100%;
            justify-content: space-around;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            margin-top: 16px;
        }
        .restaurant-actions {
            width: 100%;
            justify-content: center;
            padding-top: 16px;
        }
    }
</style>
@endpush

@section('content')
{{-- Dashboard Header --}}
<div class="dashboard-header">
    <h2>{{ __('app.welcome_dashboard') }} 👋</h2>
    <p>{{ __('app.dashboard_subtitle') }}</p>
</div>

{{-- Stats Cards --}}
<div class="stats-grid">
    <div class="stat-card-new">
        <div class="stat-icon-box red">
            <i class="fas fa-store"></i>
        </div>
        <div class="stat-value">{{ $stats['restaurants_count'] }}</div>
        <div class="stat-label">{{ __('app.restaurants') }}</div>
    </div>
    
    <div class="stat-card-new">
        <div class="stat-icon-box blue">
            <i class="fas fa-code-branch"></i>
        </div>
        <div class="stat-value">{{ $stats['branches_count'] }}</div>
        <div class="stat-label">{{ __('app.branches') }}</div>
    </div>
    
    <div class="stat-card-new">
        <div class="stat-icon-box green">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="stat-value">{{ $stats['categories_count'] }}</div>
        <div class="stat-label">{{ __('app.categories') }}</div>
    </div>
    
    <div class="stat-card-new">
        <div class="stat-icon-box orange">
            <i class="fas fa-utensils"></i>
        </div>
        <div class="stat-value">{{ $stats['items_count'] }}</div>
        <div class="stat-label">{{ __('app.items') }}</div>
    </div>
</div>

{{-- Restaurants List --}}
<div class="section-header">
    <div class="section-title">
        <i class="fas fa-store"></i>
        {{ __('app.my_restaurants') }}
    </div>
    @if(auth()->user()->hasRole('admin'))
    <a href="{{ route('admin.restaurants.create') }}" class="btn-add">
        <i class="fas fa-plus"></i>
        {{ __('app.add_restaurant') }}
    </a>
    @endif
</div>

@if($restaurants->count() > 0)
    @foreach($restaurants as $restaurant)
    <div class="restaurant-card">
        <div class="restaurant-main">
            <div class="restaurant-logo">
                @if($restaurant->logo)
                    <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name_ar }}">
                @else
                    <i class="fas fa-store"></i>
                @endif
            </div>
            
            <div class="restaurant-info">
                <div class="restaurant-name">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</div>
                <div class="restaurant-name-en">{{ app()->getLocale() == 'ar' ? $restaurant->name_en : $restaurant->name_ar }}</div>
            </div>
            
            <div class="restaurant-stats">
                <div class="restaurant-stat">
                    <div class="restaurant-stat-value">{{ $restaurant->branches_count }}</div>
                    <div class="restaurant-stat-label">{{ __('app.branches') }}</div>
                </div>
                <div class="restaurant-stat">
                    <div class="restaurant-stat-value">{{ $restaurant->categories_count }}</div>
                    <div class="restaurant-stat-label">{{ __('app.categories') }}</div>
                </div>
                <div class="restaurant-stat">
                    <div class="restaurant-stat-value">{{ $restaurant->menu_items_count }}</div>
                    <div class="restaurant-stat-label">{{ __('app.items') }}</div>
                </div>
            </div>
            
            <span class="restaurant-status {{ $restaurant->is_active ? 'active' : 'inactive' }}">
                {{ $restaurant->is_active ? __('app.active') : __('app.inactive') }}
            </span>
            
            <div class="restaurant-actions">
                <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="action-btn primary" title="{{ __('app.enter') }}">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                </a>
                <a href="{{ route('admin.restaurants.qrcode', $restaurant) }}" class="action-btn" title="{{ __('app.qr_code') }}">
                    <i class="fas fa-qrcode"></i>
                </a>
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="action-btn" title="{{ __('app.edit') }}">
                    <i class="fas fa-pen"></i>
                </a>
                @endif
                <a href="{{ $restaurant->getMenuUrl() }}" target="_blank" class="action-btn" title="{{ __('app.view_menu') }}">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
        
        @foreach($restaurant->branches as $branch)
        <div class="branch-row">
            <div class="branch-icon">
                <i class="fas fa-code-branch"></i>
            </div>
            <div class="branch-info">
                <div class="branch-name">{{ app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en }}</div>
                <div class="branch-name-en">{{ app()->getLocale() == 'ar' ? $branch->name_en : $branch->name_ar }}</div>
            </div>
            
            <div class="restaurant-stats">
                <div class="restaurant-stat">
                    <div class="restaurant-stat-value">{{ $branch->categories()->count() }}</div>
                    <div class="restaurant-stat-label">{{ __('app.categories') }}</div>
                </div>
                <div class="restaurant-stat">
                    <div class="restaurant-stat-value">{{ $branch->menuItems()->count() }}</div>
                    <div class="restaurant-stat-label">{{ __('app.items') }}</div>
                </div>
            </div>
            
            <span class="restaurant-status {{ $branch->is_active ? 'active' : 'inactive' }}">
                {{ $branch->is_active ? __('app.active') : __('app.inactive') }}
            </span>
            
            <div class="restaurant-actions">
                <a href="{{ route('admin.restaurants.show', $branch) }}" class="action-btn primary" title="{{ __('app.enter') }}">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i>
                </a>
                <a href="{{ route('admin.restaurants.qrcode', $branch) }}" class="action-btn" title="{{ __('app.qr_code') }}">
                    <i class="fas fa-qrcode"></i>
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
        </div>
        @endforeach
    </div>
    @endforeach
    
    <div class="info-alert">
        <div class="info-alert-icon">
            <i class="fas fa-info"></i>
        </div>
        <div class="info-alert-content">
            <strong>{{ __('app.note') }}:</strong>
            <span>{{ __('app.manage_note') }}</span>
        </div>
    </div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-store"></i>
            </div>
            <h4>{{ __('app.no_restaurants') }}</h4>
            @if(auth()->user()->hasRole('admin'))
            <p>{{ __('app.add_first_restaurant') }}</p>
            <a href="{{ route('admin.restaurants.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                {{ __('app.add_restaurant') }}
            </a>
            @else
            <p>{{ __('app.not_assigned') }}</p>
            @endif
        </div>
    </div>
@endif
@endsection