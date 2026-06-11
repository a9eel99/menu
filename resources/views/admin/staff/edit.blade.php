@extends('layouts.admin')

@section('title', __('app.edit_staff') . ' - ' . $staff->name)
@section('page-title', __('app.edit_staff'))

@push('styles')
<style>
    .header-avatar { width: 50px; height: 50px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.3); object-fit: cover; margin-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}: auto; }
    .role-selection { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    @media (max-width: 576px) { .role-selection { grid-template-columns: 1fr; } }
    .role-option { position: relative; cursor: pointer; }
    .role-option input { position: absolute; opacity: 0; }
    .role-option-content { display: flex; align-items: center; gap: 14px; padding: 16px; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px; transition: all 0.2s ease; }
    .role-option input:checked + .role-option-content { border-color: var(--primary); background: var(--primary-light); }
    .role-option:hover .role-option-content { border-color: var(--primary); }
    .role-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .role-icon.admin { background: #fef2f2; color: #dc2626; }
    .role-icon.employee { background: #eff6ff; color: #3b82f6; }
    .role-info h6 { font-weight: 600; color: #0f172a; margin-bottom: 2px; }
    .role-info p { font-size: 0.8rem; color: #64748b; margin: 0; }
    .status-switch-wrapper { display: flex; align-items: center; gap: 12px; padding: 16px 20px; background: #f8fafc; border-radius: 12px; margin-top: 20px; }
    .status-info label { font-weight: 500; color: #374151; margin: 0; display: block; cursor: pointer; }
    .status-info small { color: #94a3b8; font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.staff.index') }}">{{ __('app.staff') }}</a>
    <span>/</span>
    <span class="current">{{ __('app.edit') }}: {{ $staff->name }}</span>
</div>

<div class="create-page">
    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fas fa-user-edit"></i> {{ __('app.edit_staff') }}</h5>
            <img src="{{ $staff->avatar_url }}" alt="{{ $staff->name }}" class="header-avatar">
        </div>
        
        <div class="form-card-body">
            <form action="{{ route('admin.staff.update', $staff) }}" method="POST">
                @csrf
                @method('PUT')
                
                {{-- المعلومات الشخصية --}}
                <h6 class="section-title"><i class="fas fa-user"></i> {{ __('app.personal_info') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $staff->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $staff->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">{{ __('app.email') }} <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $staff->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- كلمة المرور --}}
                <h6 class="section-title"><i class="fas fa-lock"></i> {{ __('app.change_password') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.new_password') }}</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-hint">{{ __('app.leave_empty_password') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.confirm_password') }}</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                
                {{-- الصلاحيات --}}
                <h6 class="section-title"><i class="fas fa-shield-alt"></i> {{ __('app.role_and_permissions') }}</h6>
                <div class="mb-4">
                    <label class="form-label">{{ __('app.role') }} <span class="required">*</span></label>
                    <div class="role-selection">
                        @foreach($roles as $role)
                        <label class="role-option">
                            <input type="radio" name="role_id" value="{{ $role->id }}" 
                                   {{ old('role_id', $currentRole?->id) == $role->id ? 'checked' : '' }} required>
                            <div class="role-option-content">
                                <div class="role-icon {{ $role->name === 'admin' ? 'admin' : 'employee' }}">
                                    <i class="fas fa-{{ $role->name === 'admin' ? 'crown' : 'user' }}"></i>
                                </div>
                                <div class="role-info">
                                    <h6>{{ $role->name === 'admin' ? __('app.admin') : __('app.employee') }}</h6>
                                    <p>{{ $role->name === 'admin' ? __('app.admin_role_desc') : __('app.employee_role_desc') }}</p>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('role_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">{{ __('app.assigned_restaurant') }}</label>
                    <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror">
                        <option value="">-- {{ __('app.select_restaurant') }} --</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}" {{ old('restaurant_id', $staff->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}
                                @if($restaurant->parent_id) ({{ __('app.branch') }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- الحالة --}}
                <div class="status-switch-wrapper">
                    <div class="form-check form-switch mb-0">
                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" 
                               value="1" {{ $staff->is_active ? 'checked' : '' }}>
                    </div>
                    <div class="status-info">
                        <label for="is_active">{{ __('app.account_active') }}</label>
                        <small>{{ __('app.account_active_hint') }}</small>
                    </div>
                </div>
                
                {{-- معلومات إضافية --}}
                <div class="meta-card">
                    <div class="meta-item">
                        <i class="fas fa-calendar-plus"></i>
                        <span>{{ __('app.created_at') }}: {{ $staff->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('app.last_login') }}: {{ $staff->last_login_at ? $staff->last_login_at->format('Y-m-d H:i') : __('app.never') }}</span>
                    </div>
                </div>
                
                {{-- الأزرار --}}
                <div class="form-actions spread">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            {{ __('app.save_changes') }}
                        </button>
                        <a href="{{ route('admin.staff.index') }}" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            {{ __('app.cancel') }}
                        </a>
                    </div>
                    
                    <button type="button" class="btn-delete" data-delete-confirm="{{ __('app.confirm_delete_staff') }}" data-form-id="delete-form">
                        <i class="fas fa-trash"></i>
                        {{ __('app.delete') }}
                    </button>
                </div>
            </form>
            
            <form id="delete-form" action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection