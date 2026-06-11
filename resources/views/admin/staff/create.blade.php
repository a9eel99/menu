@extends('layouts.admin')

@section('title', __('app.add_staff'))
@section('page-title', __('app.add_staff'))

@push('styles')
<style>
    .role-selection { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    @media (max-width: 576px) { .role-selection { grid-template-columns: 1fr; } }
    .role-option { position: relative; cursor: pointer; }
    .role-option input { position: absolute; opacity: 0; }
    .role-option-content {
        display: flex; align-items: center; gap: 14px; padding: 16px;
        background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 12px;
        transition: all 0.2s ease;
    }
    .role-option input:checked + .role-option-content { border-color: var(--primary); background: var(--primary-light); }
    .role-option:hover .role-option-content { border-color: var(--primary); }
    .role-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .role-icon.admin { background: #fef2f2; color: #dc2626; }
    .role-icon.employee { background: #eff6ff; color: #3b82f6; }
    .role-info h6 { font-weight: 600; color: #0f172a; margin-bottom: 2px; }
    .role-info p { font-size: 0.8rem; color: #64748b; margin: 0; }
</style>
@endpush

@section('content')
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.staff.index') }}">{{ __('app.staff') }}</a>
    <span>/</span>
    <span class="current">{{ __('app.add_staff') }}</span>
</div>

<div class="create-page">
    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fas fa-user-plus"></i> {{ __('app.add_staff') }}</h5>
        </div>
        
        <div class="form-card-body">
            <form action="{{ route('admin.staff.store') }}" method="POST">
                @csrf
                
                {{-- المعلومات الشخصية --}}
                <h6 class="section-title"><i class="fas fa-user"></i> {{ __('app.personal_info') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.name') }} <span class="required">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="{{ __('app.enter_name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.phone') }}</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone') }}" placeholder="+962 7x xxx xxxx">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">{{ __('app.email') }} <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" placeholder="email@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- كلمة المرور --}}
                <h6 class="section-title"><i class="fas fa-lock"></i> {{ __('app.password_section') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.password') }} <span class="required">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-hint">{{ __('app.min_password') }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.confirm_password') }} <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                
                {{-- الصلاحيات --}}
                <h6 class="section-title"><i class="fas fa-shield-alt"></i> {{ __('app.role_and_permissions') }}</h6>
                <div class="mb-4">
                    <label class="form-label">{{ __('app.role') }} <span class="required">*</span></label>
                    <div class="role-selection">
                        @foreach($roles as $role)
                        <label class="role-option">
                            <input type="radio" name="role_id" value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'checked' : '' }} required>
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
                    <label class="form-label">{{ __('app.assigned_restaurant') }} <span class="required">*</span></label>
                    <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('app.select_restaurant') }} --</option>
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}" {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}
                                @if($restaurant->parent_id) ({{ __('app.branch') }}) @endif
                            </option>
                        @endforeach
                    </select>
                    @error('restaurant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- معلومة --}}
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p>{{ __('app.staff_login_note') }}</p>
                </div>
                
                {{-- الأزرار --}}
                <div class="form-actions spread">
                    <a href="{{ route('admin.staff.index') }}" class="btn-back">
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                        {{ __('app.back') }}
                    </a>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        {{ __('app.create_staff') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection