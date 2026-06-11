@extends('layouts.admin')

@section('title', __('app.staff'))
@section('page-title', __('app.staff'))

@push('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-header-info h4 { font-weight: 700; color: #0f172a; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; }
    .page-header-info h4 i { color: var(--primary); }
    .page-header-info p { color: #64748b; margin: 0; font-size: 0.9rem; }
    
    .btn-add { display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: var(--primary); color: white; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; transition: all 0.2s ease; }
    .btn-add:hover { filter: brightness(1.1); transform: translateY(-2px); color: white; }
    
    .staff-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
    
    .stats-bar { display: flex; gap: 24px; padding: 20px 24px; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
    .stat-item { display: flex; align-items: center; gap: 12px; }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .stat-icon.total { background: #eff6ff; color: #3b82f6; }
    .stat-icon.active { background: #ecfdf5; color: #10b981; }
    .stat-icon.inactive { background: #fef2f2; color: #ef4444; }
    .stat-info strong { display: block; font-size: 1.25rem; color: #0f172a; }
    .stat-info span { font-size: 0.8rem; color: #64748b; }
    
    .staff-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .staff-table thead th { padding: 14px 20px; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: #64748b; background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
    .staff-table tbody tr { transition: all 0.2s ease; }
    .staff-table tbody tr:hover { background: #f8fafc; }
    .staff-table tbody td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .staff-table tbody tr:last-child td { border-bottom: none; }
    
    .staff-info { display: flex; align-items: center; gap: 14px; }
    .staff-avatar { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
    .staff-details strong { display: block; color: #0f172a; font-weight: 600; }
    .staff-details small { color: #64748b; font-size: 0.8rem; }
    
    .role-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
    .role-badge.admin { background: #fef2f2; color: #dc2626; }
    .role-badge.employee { background: #eff6ff; color: #3b82f6; }
    
    .restaurant-cell { display: flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.85rem; }
    .restaurant-cell i { color: var(--primary); }
    
    .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 500; }
    .status-badge.active { background: #ecfdf5; color: #059669; }
    .status-badge.inactive { background: #f1f5f9; color: #64748b; }
    
    .last-login { font-size: 0.85rem; color: #64748b; }
    
    .action-btns { display: flex; gap: 8px; }
    .action-btn { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #e2e8f0; background: white; color: #64748b; text-decoration: none; transition: all 0.2s ease; cursor: pointer; }
    .action-btn:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-light); }
    .action-btn.success:hover { border-color: #10b981; color: #10b981; background: #ecfdf5; }
    .action-btn.warning:hover { border-color: #f59e0b; color: #f59e0b; background: #fffbeb; }
    .action-btn.danger:hover { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
    
    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state-icon { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; }
    .empty-state-icon i { font-size: 2.5rem; color: #94a3b8; }
    .empty-state h5 { font-weight: 600; color: #0f172a; margin-bottom: 8px; }
    .empty-state p { color: #64748b; margin-bottom: 24px; max-width: 300px; margin-left: auto; margin-right: auto; }
    
    @media (max-width: 768px) {
        .stats-bar { flex-wrap: wrap; gap: 16px; }
        .staff-table thead { display: none; }
        .staff-table tbody tr { display: block; padding: 16px; border-bottom: 1px solid #f1f5f9; }
        .staff-table tbody td { display: flex; justify-content: space-between; padding: 8px 0; border: none; }
        .staff-table tbody td::before { content: attr(data-label); font-weight: 600; color: #64748b; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="page-header-info">
        <h4><i class="fas fa-users-cog"></i> {{ __('app.staff') }}</h4>
        <p>{{ __('app.staff_management_desc') }}</p>
    </div>
    <a href="{{ route('admin.staff.create') }}" class="btn-add">
        <i class="fas fa-user-plus"></i>
        {{ __('app.add_staff') }}
    </a>
</div>

<div class="staff-card">
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon total"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <strong>{{ $staff->count() }}</strong>
                <span>{{ __('app.total_staff') }}</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon active"><i class="fas fa-user-check"></i></div>
            <div class="stat-info">
                <strong>{{ $staff->where('is_active', true)->count() }}</strong>
                <span>{{ __('app.active_staff') }}</span>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon inactive"><i class="fas fa-user-slash"></i></div>
            <div class="stat-info">
                <strong>{{ $staff->where('is_active', false)->count() }}</strong>
                <span>{{ __('app.inactive_staff') }}</span>
            </div>
        </div>
    </div>
    
    @if($staff->count() > 0)
    <table class="staff-table">
        <thead>
            <tr>
                <th>{{ __('app.staff_member') }}</th>
                <th>{{ __('app.email') }}</th>
                <th>{{ __('app.role') }}</th>
                <th>{{ __('app.restaurant') }}</th>
                <th>{{ __('app.status') }}</th>
                <th>{{ __('app.last_login') }}</th>
                <th>{{ __('app.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $member)
            @php
                $memberRole = $member->roles->first();
                $restaurant = $member->restaurant ?? $member->ownedRestaurants()->first();
            @endphp
            <tr>
                <td data-label="{{ __('app.staff_member') }}">
                    <div class="staff-info">
                        <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="staff-avatar">
                        <div class="staff-details">
                            <strong>{{ $member->name }}</strong>
                            @if($member->phone)<small>{{ $member->phone }}</small>@endif
                        </div>
                    </div>
                </td>
                <td data-label="{{ __('app.email') }}">{{ $member->email }}</td>
                <td data-label="{{ __('app.role') }}">
                    @if($memberRole)
                        <span class="role-badge {{ $memberRole->name === 'admin' ? 'admin' : 'employee' }}">
                            <i class="fas fa-{{ $memberRole->name === 'admin' ? 'crown' : 'user' }}"></i>
                            {{ $memberRole->name === 'admin' ? __('app.admin') : __('app.employee') }}
                        </span>
                    @else
                        <span class="role-badge employee">-</span>
                    @endif
                </td>
                <td data-label="{{ __('app.restaurant') }}">
                    @if($restaurant)
                        <span class="restaurant-cell">
                            <i class="fas fa-store"></i>
                            {{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td data-label="{{ __('app.status') }}">
                    <span class="status-badge {{ $member->is_active ? 'active' : 'inactive' }}">
                        {{ $member->is_active ? __('app.active') : __('app.inactive') }}
                    </span>
                </td>
                <td data-label="{{ __('app.last_login') }}">
                    <span class="last-login">
                        {{ $member->last_login_at ? $member->last_login_at->diffForHumans() : __('app.never') }}
                    </span>
                </td>
                <td data-label="{{ __('app.actions') }}">
                    <div class="action-btns">
                        <a href="{{ route('admin.staff.edit', $member) }}" class="action-btn" title="{{ __('app.edit') }}">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.staff.toggle', $member) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="action-btn {{ $member->is_active ? 'warning' : 'success' }}" title="{{ $member->is_active ? __('app.deactivate') : __('app.activate') }}">
                                <i class="fas fa-{{ $member->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                        <button type="button" class="action-btn danger" data-delete-confirm="{{ __('app.confirm_delete') }}" data-form-id="delete-form-{{ $member->id }}" title="{{ __('app.delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $member->id }}" action="{{ route('admin.staff.destroy', $member) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <div class="empty-state-icon"><i class="fas fa-users"></i></div>
        <h5>{{ __('app.no_staff') }}</h5>
        <p>{{ __('app.no_staff_desc') }}</p>
        <a href="{{ route('admin.staff.create') }}" class="btn-add">
            <i class="fas fa-user-plus"></i>
            {{ __('app.add_staff') }}
        </a>
    </div>
    @endif
</div>
@endsection