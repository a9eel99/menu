@extends('layouts.admin')

@section('title', __('app.add_item') . ' - ' . (app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en))
@section('page-title', __('app.add_item'))

@section('content')
{{-- Breadcrumb --}}
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.restaurants.show', $restaurant) }}">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</a>
    <span>/</span>
    <span class="current">{{ __('app.add_item') }}</span>
</div>

<div class="create-page wide">
    <div class="form-card">
        <div class="form-card-header">
            <h5>
                <i class="fas fa-plus-circle"></i>
                {{ __('app.add_item') }}
            </h5>
        </div>
        
        <div class="form-card-body">
            <form action="{{ route('admin.restaurants.items.store', $restaurant) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- القسم --}}
                <div class="mb-4">
                    <label class="form-label">{{ __('app.category') }} <span class="required">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('app.select_category') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                {{-- الأسماء --}}
                <h6 class="section-title"><i class="fas fa-font"></i> {{ __('app.item_names') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_name_ar') }} <span class="required">*</span></label>
                        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" 
                               value="{{ old('name_ar') }}" placeholder="{{ __('app.enter_item_ar') }}" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_name_en') }} <span class="required">*</span></label>
                        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                               value="{{ old('name_en') }}" placeholder="{{ __('app.enter_item_en') }}" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                {{-- الوصف --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_description_ar') }}</label>
                        <textarea name="description_ar" class="form-control" rows="2" placeholder="{{ __('app.item_desc_placeholder') }}">{{ old('description_ar') }}</textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_description_en') }}</label>
                        <textarea name="description_en" class="form-control" rows="2" placeholder="{{ __('app.item_desc_placeholder_en') }}">{{ old('description_en') }}</textarea>
                    </div>
                </div>
                
                {{-- الأسعار والصورة --}}
                <h6 class="section-title"><i class="fas fa-tag"></i> {{ __('app.price_and_image') }}</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.price') }} <span class="required">*</span></label>
                        <div class="price-input-group">
                            <input type="number" name="price" step="0.01" min="0" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price') }}" placeholder="0.00" required>
                            <span class="currency-badge">{{ $restaurant->currency_symbol }}</span>
                        </div>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.old_price') }}</label>
                        <div class="price-input-group">
                            <input type="number" name="old_price" step="0.01" min="0" class="form-control" 
                                   value="{{ old('old_price') }}" placeholder="0.00">
                            <span class="currency-badge">{{ $restaurant->currency_symbol }}</span>
                        </div>
                        <small class="text-muted">{{ __('app.old_price_hint') }}</small>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.item_image') }}</label>
                        <div class="image-upload-area">
                            <input type="file" name="image" id="image-input" accept="image/*">
                            <i class="fas fa-camera"></i>
                            <p>{{ __('app.upload_image') }}</p>
                        </div>
                        <div class="image-preview" id="image-preview">
                            <img src="" alt="Preview">
                        </div>
                    </div>
                </div>
                
                {{-- العلامات --}}
                @if($tags->count() > 0)
                <h6 class="section-title"><i class="fas fa-tags"></i> {{ __('app.tags') }}</h6>
                <div class="tags-wrapper">
                    @foreach($tags as $tag)
                    <div>
                        <input type="checkbox" class="tag-checkbox" name="tags[]" 
                               value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                               {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                        <label class="tag-label" for="tag-{{ $tag->id }}" 
                               style="background: {{ $tag->bg_color ?? '#f0f0f0' }}; color: {{ $tag->color ?? '#333' }};">
                            {{ $tag->icon ?? '' }} {{ app()->getLocale() == 'ar' ? $tag->name_ar : $tag->name_en }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
                
                {{-- الخيارات --}}
                <div class="switches-grid">
                    <div class="switch-item">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" checked>
                        </div>
                        <label for="is_available">
                            {{ __('app.available') }}
                            <small>{{ __('app.available_hint') }}</small>
                        </label>
                    </div>
                    
                    <div class="switch-item">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1">
                        </div>
                        <label for="is_featured">
                            <i class="fas fa-star"></i> {{ __('app.featured') }}
                            <small>{{ __('app.featured_hint') }}</small>
                        </label>
                    </div>
                </div>
                
                {{-- الأزرار --}}
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i>
                        {{ __('app.save') }}
                    </button>
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}#items" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        {{ __('app.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection