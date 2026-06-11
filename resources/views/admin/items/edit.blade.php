@extends('layouts.admin')

@section('title', __('app.edit_item') . ' - ' . (app()->getLocale() == 'ar' ? $item->name_ar : $item->name_en))
@section('page-title', __('app.edit_item'))

@section('content')
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.restaurants.show', $restaurant) }}">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</a>
    <span>/</span>
    <span class="current">{{ __('app.edit') }}: {{ app()->getLocale() == 'ar' ? $item->name_ar : $item->name_en }}</span>
</div>

<div class="create-page wide">
    <div class="form-card">
        <div class="form-card-header">
            <h5><i class="fas fa-edit"></i> {{ __('app.edit_item') }}</h5>
        </div>
        
        <div class="form-card-body">
            <form action="{{ route('admin.restaurants.items.update', [$restaurant, $item]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label">{{ __('app.category') }} <span class="required">*</span></label>
                    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('app.select_category') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <h6 class="section-title"><i class="fas fa-font"></i> {{ __('app.item_names') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_name_ar') }} <span class="required">*</span></label>
                        <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ old('name_ar', $item->name_ar) }}" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_name_en') }} <span class="required">*</span></label>
                        <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $item->name_en) }}" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_description_ar') }}</label>
                        <textarea name="description_ar" class="form-control" rows="2">{{ old('description_ar', $item->description_ar) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('app.item_description_en') }}</label>
                        <textarea name="description_en" class="form-control" rows="2">{{ old('description_en', $item->description_en) }}</textarea>
                    </div>
                </div>
                
                <h6 class="section-title"><i class="fas fa-tag"></i> {{ __('app.price_and_image') }}</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.price') }} <span class="required">*</span></label>
                        <div class="price-input-group">
                            <input type="number" name="price" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $item->price) }}" required>
                            <span class="currency-badge">{{ $restaurant->currency_symbol }}</span>
                        </div>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.old_price') }}</label>
                        <div class="price-input-group">
                            <input type="number" name="old_price" step="0.01" min="0" class="form-control" value="{{ old('old_price', $item->old_price) }}">
                            <span class="currency-badge">{{ $restaurant->currency_symbol }}</span>
                        </div>
                        <small class="text-muted">{{ __('app.old_price_hint') }}</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ __('app.item_image') }}</label>
                        @if($item->image)
                        <div class="current-image">
                            <img src="{{ asset('storage/' . $item->image) }}" alt="">
                            <label class="remove-image">
                                <input type="checkbox" name="remove_image" value="1">
                                <i class="fas fa-trash"></i> {{ __('app.remove') }}
                            </label>
                        </div>
                        @endif
                        <div class="image-upload-area">
                            <input type="file" name="image" id="image-input" accept="image/*">
                            <i class="fas fa-camera"></i>
                            <p>{{ $item->image ? __('app.change_image') : __('app.upload_image') }}</p>
                        </div>
                        <div class="image-preview" id="image-preview"><img src="" alt=""></div>
                    </div>
                </div>
                
                @if($tags->count() > 0)
                <h6 class="section-title"><i class="fas fa-tags"></i> {{ __('app.tags') }}</h6>
                <div class="tags-wrapper">
                    @foreach($tags as $tag)
                    <div>
                        <input type="checkbox" class="tag-checkbox" name="tags[]" value="{{ $tag->id }}" id="tag-{{ $tag->id }}" {{ in_array($tag->id, old('tags', $selectedTags ?? [])) ? 'checked' : '' }}>
                        <label class="tag-label" for="tag-{{ $tag->id }}" style="background: {{ $tag->bg_color ?? '#f0f0f0' }}; color: {{ $tag->color ?? '#333' }};">
                            {{ $tag->icon ?? '' }} {{ app()->getLocale() == 'ar' ? $tag->name_ar : $tag->name_en }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="switches-grid">
                    <div class="switch-item">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $item->is_available) ? 'checked' : '' }}>
                        </div>
                        <label for="is_available">{{ __('app.available') }}<small>{{ __('app.available_hint') }}</small></label>
                    </div>
                    <div class="switch-item">
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                        </div>
                        <label for="is_featured"><i class="fas fa-star"></i> {{ __('app.featured') }}<small>{{ __('app.featured_hint') }}</small></label>
                    </div>
                </div>
                
                <div class="form-actions spread">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-save"><i class="fas fa-save"></i> {{ __('app.save_changes') }}</button>
                        <a href="{{ route('admin.restaurants.show', $restaurant) }}#items" class="btn-cancel"><i class="fas fa-times"></i> {{ __('app.cancel') }}</a>
                    </div>
                    <button type="button" class="btn-delete" data-delete-confirm="{{ __('app.confirm_delete') }}" data-form-id="delete-form">
                        <i class="fas fa-trash"></i> {{ __('app.delete') }}
                    </button>
                </div>
            </form>
            
            <form id="delete-form" action="{{ route('admin.restaurants.items.destroy', [$restaurant, $item]) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection