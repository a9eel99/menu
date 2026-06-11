@extends('layouts.admin')

@section('title', __('app.edit_category') . ' - ' . (app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en))
@section('page-title', __('app.edit_category'))

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.restaurants.show', $restaurant) }}">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</a>
    <span>/</span>
    <span class="current">{{ __('app.edit') }}: {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}</span>
</div>

<div class="create-page">
    <div class="form-card">
        <div class="form-card-header">
            <h5>
                <i class="fas fa-edit"></i>
                {{ __('app.edit_category') }}
            </h5>
            <small>{{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}</small>
        </div>
        
        <div class="form-card-body">
            <form action="{{ route('admin.restaurants.categories.update', [$restaurant, $category]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                {{-- الأسماء --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.category_name_ar') }} <span class="required">*</span></label>
                        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                               value="{{ old('name_ar', $category->name_ar) }}" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.category_name_en') }} <span class="required">*</span></label>
                        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                               value="{{ old('name_en', $category->name_en) }}" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                {{-- الأيقونة --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('app.category_icon') }}</label>
                    <select name="icon" class="form-select @error('icon') is-invalid @enderror" id="icon-select">
                        <option value="">-- {{ __('app.select_icon') }} --</option>
                        
                        <optgroup label="🍽️ {{ __('app.food') }}">
                            <option value="fas fa-utensils" {{ old('icon', $category->icon) == 'fas fa-utensils' ? 'selected' : '' }}>🍴 {{ __('app.icon_utensils') }}</option>
                            <option value="fas fa-burger" {{ old('icon', $category->icon) == 'fas fa-burger' ? 'selected' : '' }}>🍔 {{ __('app.icon_burger') }}</option>
                            <option value="fas fa-pizza-slice" {{ old('icon', $category->icon) == 'fas fa-pizza-slice' ? 'selected' : '' }}>🍕 {{ __('app.icon_pizza') }}</option>
                            <option value="fas fa-drumstick-bite" {{ old('icon', $category->icon) == 'fas fa-drumstick-bite' ? 'selected' : '' }}>🍗 {{ __('app.icon_chicken') }}</option>
                            <option value="fas fa-fish" {{ old('icon', $category->icon) == 'fas fa-fish' ? 'selected' : '' }}>🐟 {{ __('app.icon_fish') }}</option>
                            <option value="fas fa-bowl-rice" {{ old('icon', $category->icon) == 'fas fa-bowl-rice' ? 'selected' : '' }}>🍚 {{ __('app.icon_rice') }}</option>
                            <option value="fas fa-bowl-food" {{ old('icon', $category->icon) == 'fas fa-bowl-food' ? 'selected' : '' }}>🥗 {{ __('app.icon_salad') }}</option>
                            <option value="fas fa-plate-wheat" {{ old('icon', $category->icon) == 'fas fa-plate-wheat' ? 'selected' : '' }}>🍽️ {{ __('app.icon_main_dish') }}</option>
                        </optgroup>
                        
                        <optgroup label="🥤 {{ __('app.drinks') }}">
                            <option value="fas fa-mug-hot" {{ old('icon', $category->icon) == 'fas fa-mug-hot' ? 'selected' : '' }}>☕ {{ __('app.icon_hot_drink') }}</option>
                            <option value="fas fa-coffee" {{ old('icon', $category->icon) == 'fas fa-coffee' ? 'selected' : '' }}>☕ {{ __('app.icon_coffee') }}</option>
                            <option value="fas fa-glass-water" {{ old('icon', $category->icon) == 'fas fa-glass-water' ? 'selected' : '' }}>💧 {{ __('app.icon_water') }}</option>
                            <option value="fas fa-wine-glass" {{ old('icon', $category->icon) == 'fas fa-wine-glass' ? 'selected' : '' }}>🥤 {{ __('app.icon_juice') }}</option>
                            <option value="fas fa-martini-glass-citrus" {{ old('icon', $category->icon) == 'fas fa-martini-glass-citrus' ? 'selected' : '' }}>🍹 {{ __('app.icon_cocktail') }}</option>
                        </optgroup>
                        
                        <optgroup label="🍰 {{ __('app.desserts') }}">
                            <option value="fas fa-cake-candles" {{ old('icon', $category->icon) == 'fas fa-cake-candles' ? 'selected' : '' }}>🎂 {{ __('app.icon_cake') }}</option>
                            <option value="fas fa-ice-cream" {{ old('icon', $category->icon) == 'fas fa-ice-cream' ? 'selected' : '' }}>🍦 {{ __('app.icon_ice_cream') }}</option>
                            <option value="fas fa-cookie" {{ old('icon', $category->icon) == 'fas fa-cookie' ? 'selected' : '' }}>🍪 {{ __('app.icon_cookies') }}</option>
                        </optgroup>
                        
                        <optgroup label="🔥 {{ __('app.other') }}">
                            <option value="fas fa-fire" {{ old('icon', $category->icon) == 'fas fa-fire' ? 'selected' : '' }}>🔥 {{ __('app.icon_grill') }}</option>
                            <option value="fas fa-star" {{ old('icon', $category->icon) == 'fas fa-star' ? 'selected' : '' }}>⭐ {{ __('app.icon_special') }}</option>
                            <option value="fas fa-gift" {{ old('icon', $category->icon) == 'fas fa-gift' ? 'selected' : '' }}>🎁 {{ __('app.icon_offers') }}</option>
                            <option value="fas fa-child" {{ old('icon', $category->icon) == 'fas fa-child' ? 'selected' : '' }}>👶 {{ __('app.icon_kids') }}</option>
                        </optgroup>
                    </select>
                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    
                    <div class="icon-preview-box">
                        <i id="icon-preview" class="{{ $category->icon ?: 'fas fa-folder' }}"></i>
                        <span>{{ __('app.icon_preview') }}</span>
                    </div>
                </div>
                
                {{-- الصورة --}}
                <div class="mb-3">
                    <label class="form-label">{{ __('app.category_image') }}</label>
                    
                    @if($category->image)
                    <div class="current-image">
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name_ar }}">
                        <label class="remove-image">
                            <input type="checkbox" name="remove_image" value="1">
                            <i class="fas fa-trash"></i> {{ __('app.remove_image') }}
                        </label>
                    </div>
                    @endif
                    
                    <div class="image-upload-area">
                        <input type="file" name="image" id="image-input" accept="image/*" class="@error('image') is-invalid @enderror">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>{{ $category->image ? __('app.change_image') : __('app.drag_or_click') }}</p>
                    </div>
                    @error('image')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    
                    <div class="image-preview" id="image-preview">
                        <img src="" alt="Preview">
                    </div>
                </div>
                
                {{-- الحالة --}}
                <div class="switch-wrapper">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    </div>
                    <label for="is_active">{{ __('app.category_active') }}</label>
                </div>
                
                {{-- الأزرار --}}
                <div class="form-actions spread">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            {{ __('app.save_changes') }}
                        </button>
                        <a href="{{ route('admin.restaurants.show', $restaurant) }}#categories" class="btn-cancel">
                            <i class="fas fa-times"></i>
                            {{ __('app.cancel') }}
                        </a>
                    </div>
                    
                    <button type="button" class="btn-delete" data-delete-confirm="{{ __('app.confirm_delete') }}" data-form-id="delete-form">
                        <i class="fas fa-trash"></i>
                        {{ __('app.delete') }}
                    </button>
                </div>
            </form>
            
            <form id="delete-form" action="{{ route('admin.restaurants.categories.destroy', [$restaurant, $category]) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection