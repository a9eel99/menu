@extends('layouts.admin')

@section('title', __('app.restaurants_and_branches'))
@section('page-title', __('app.restaurants_and_branches'))

@push('styles')
<style>
    /* ========== RESTAURANTS INDEX STYLES ========== */
    
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .page-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .page-header-title .icon-box {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
    }
    
    .page-header-title h5 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    
    .page-header-title p {
        font-size: 0.875rem;
        color: #64748b;
        margin: 0;
    }
    
    .btn-add-new {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }
    
    .btn-add-new:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Restaurant Card */
    .restaurant-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }
    
    .restaurant-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    
    /* Card Header - يستخدم لون النظام */
    .restaurant-card-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        padding: 24px;
        color: white;
    }
    
    .restaurant-header-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .restaurant-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .restaurant-logo {
        width: 70px;
        height: 70px;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid rgba(255,255,255,0.3);
        flex-shrink: 0;
    }
    
    .restaurant-logo.has-image {
        background: white;
        padding: 6px;
    }
    
    .restaurant-logo.no-image {
        background: rgba(255,255,255,0.15);
    }
    
    .restaurant-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .restaurant-logo i {
        font-size: 1.75rem;
        color: rgba(255,255,255,0.8);
    }
    
    .restaurant-details h4 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 4px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .restaurant-details h4 .main-badge {
        font-size: 0.7rem;
        padding: 4px 12px;
        background: rgba(255,255,255,0.2);
        border-radius: 20px;
        font-weight: 600;
    }
    
    .restaurant-details .subtitle {
        font-size: 0.9rem;
        opacity: 0.8;
        margin: 0;
    }
    
    .restaurant-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.15);
        color: white;
    }
    
    .action-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: white;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        padding: 24px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }
    
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    
    .stat-card:hover {
        border-color: var(--primary-light);
        transform: translateY(-2px);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 6px;
        color: var(--primary);
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .stat-card.status {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .status-badge.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .status-badge.inactive {
        background: #e2e8f0;
        color: #64748b;
    }
    
    .status-badge i {
        font-size: 0.5rem;
    }
    
    /* Branches Section */
    .branches-section {
        padding: 24px;
    }
    
    .branches-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .branches-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
    }
    
    .branches-title i {
        color: var(--primary);
    }
    
    .branches-count {
        background: var(--primary-light);
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    /* Branches Table */
    .branches-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .branches-table thead th {
        background: #f8fafc;
        padding: 14px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .branches-table thead th:first-child {
        border-radius: 10px 0 0 0;
    }
    
    .branches-table thead th:last-child {
        border-radius: 0 10px 0 0;
    }
    
    .branches-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .branches-table tbody tr:hover {
        background: #f8fafc;
    }
    
    .branches-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .branch-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .branch-logo {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        overflow: hidden;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .branch-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .branch-logo i {
        font-size: 1rem;
        color: #94a3b8;
    }
    
    .branch-name {
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }
    
    .branch-name-secondary {
        font-size: 0.8rem;
        color: #94a3b8;
    }
    
    .count-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        background: var(--primary-light);
        color: var(--primary);
    }
    
    .branch-actions {
        display: flex;
        gap: 6px;
    }
    
    .branch-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .branch-action-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }
    
    .branch-action-btn.menu:hover {
        border-color: #10b981;
        color: #10b981;
        background: #ecfdf5;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 40px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    
    .empty-state-icon {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, var(--primary-light) 0%, #f1f5f9 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
    }
    
    .empty-state-icon i {
        font-size: 2.5rem;
        color: var(--primary);
    }
    
    .empty-state h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        color: #64748b;
        margin-bottom: 24px;
    }
    
    /* No Branches Yet */
    .no-branches {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    
    .no-branches i {
        font-size: 2rem;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    .no-branches p {
        font-size: 0.9rem;
        margin: 0;
    }
</style>
@endpush

@section('content')
{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-title">
        <div class="icon-box">
            <i class="fas fa-store"></i>
        </div>
        <div>
            <h5>{{ __('app.my_restaurants') }}</h5>
            <p>{{ app()->getLocale() == 'ar' ? 'إدارة جميع مطاعمك وفروعك من مكان واحد' : 'Manage all your restaurants and branches in one place' }}</p>
        </div>
    </div>
    <a href="{{ route('admin.restaurants.create') }}" class="btn-add-new">
        <i class="fas fa-plus"></i>
        {{ __('app.add_restaurant_branch') }}
    </a>
</div>

@if($restaurants->count() > 0)
    @foreach($restaurants as $restaurant)
    <div class="restaurant-card">
        {{-- Card Header --}}
        <div class="restaurant-card-header">
            <div class="restaurant-header-content">
                <div class="restaurant-info">
                    <div class="restaurant-logo {{ $restaurant->logo ? 'has-image' : 'no-image' }}">
                        @if($restaurant->logo)
                            <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name_ar }}">
                        @else
                            <i class="fas fa-store"></i>
                        @endif
                    </div>
                    <div class="restaurant-details">
                        <h4>
                            {{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}
                            <span class="main-badge">{{ __('app.main_restaurant') }}</span>
                        </h4>
                        <p class="subtitle">{{ app()->getLocale() == 'ar' ? $restaurant->name_en : $restaurant->name_ar }}</p>
                    </div>
                </div>
                <div class="restaurant-actions">
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="action-btn">
                        <i class="fas fa-eye"></i>
                        {{ __('app.view') }}
                    </a>
                    <a href="{{ route('admin.restaurants.edit', $restaurant) }}" class="action-btn">
                        <i class="fas fa-edit"></i>
                        {{ __('app.edit') }}
                    </a>
                    <a href="{{ $restaurant->getMenuUrl() }}" target="_blank" class="action-btn">
                        <i class="fas fa-external-link-alt"></i>
                        {{ __('app.menu') }}
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Stats Grid --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value">{{ $restaurant->categories_count }}</div>
                <div class="stat-label">{{ __('app.category') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $restaurant->menu_items_count }}</div>
                <div class="stat-label">{{ __('app.item') }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $restaurant->branches_count }}</div>
                <div class="stat-label">{{ __('app.branch') }}</div>
            </div>
            <div class="stat-card status">
                <span class="status-badge {{ $restaurant->is_active ? 'active' : 'inactive' }}">
                    <i class="fas fa-circle"></i>
                    {{ $restaurant->is_active ? __('app.active') : __('app.inactive') }}
                </span>
            </div>
        </div>
        
        {{-- Branches Section --}}
        @if($restaurant->branches->count() > 0)
        <div class="branches-section">
            <div class="branches-header">
                <div class="branches-title">
                    <i class="fas fa-code-branch"></i>
                    {{ __('app.branches') }}
                </div>
                <span class="branches-count">{{ $restaurant->branches->count() }}</span>
            </div>
            
            <table class="branches-table">
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
                            <div class="branch-info">
                                <div class="branch-logo">
                                    @if($branch->logo)
                                        <img src="{{ asset('storage/' . $branch->logo) }}" alt="{{ $branch->name_ar }}">
                                    @else
                                        <i class="fas fa-store-alt"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="branch-name">{{ app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en }}</div>
                                    <div class="branch-name-secondary">{{ app()->getLocale() == 'ar' ? $branch->name_en : $branch->name_ar }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="count-badge">{{ $branch->categories_count }}</span>
                        </td>
                        <td>
                            <span class="count-badge">{{ $branch->menu_items_count }}</span>
                        </td>
                        <td>
                            <span class="status-badge {{ $branch->is_active ? 'active' : 'inactive' }}">
                                <i class="fas fa-circle"></i>
                                {{ $branch->is_active ? __('app.active') : __('app.inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="branch-actions">
                                <a href="{{ route('admin.restaurants.show', $branch) }}" class="branch-action-btn" title="{{ __('app.view') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.restaurants.edit', $branch) }}" class="branch-action-btn" title="{{ __('app.edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ $branch->getMenuUrl() }}" target="_blank" class="branch-action-btn menu" title="{{ __('app.menu') }}">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="no-branches">
            <i class="fas fa-code-branch"></i>
            <p>{{ app()->getLocale() == 'ar' ? 'لا توجد فروع حالياً' : 'No branches yet' }}</p>
        </div>
        @endif
    </div>
    @endforeach
@else
{{-- Empty State --}}
<div class="empty-state">
    <div class="empty-state-icon">
        <i class="fas fa-store"></i>
    </div>
    <h4>{{ __('app.no_restaurants') }}</h4>
    <p>{{ app()->getLocale() == 'ar' ? 'أضف مطعمك الأول للبدء في إنشاء المنيو الرقمي' : 'Add your first restaurant to start creating your digital menu' }}</p>
    <a href="{{ route('admin.restaurants.create') }}" class="btn-add-new">
        <i class="fas fa-plus"></i>
        {{ __('app.add_restaurant') }}
    </a>
</div>
@endif
@endsection