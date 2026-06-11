@extends('layouts.admin')

@section('title', __('app.copy_menu'))
@section('page-title', __('app.copy_menu'))

@push('styles')
<style>
    .copy-page { max-width: 600px; margin: 0 auto; }
    
    .copy-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9; }
    .copy-card-header { padding: 24px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary) 100%); color: white; text-align: center; }
    .copy-card-header .icon { width: 70px; height: 70px; border-radius: 50%; background: rgba(255,255,255,0.2); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; }
    .copy-card-header .icon i { font-size: 1.8rem; }
    .copy-card-header h5 { margin: 0 0 8px; font-weight: 600; }
    .copy-card-header p { margin: 0; opacity: 0.85; font-size: 0.9rem; }
    .copy-card-body { padding: 24px; }
    
    .info-box-copy { display: flex; align-items: flex-start; gap: 12px; padding: 16px; background: #f0f9ff; border-radius: 12px; border: 1px solid #bae6fd; margin-bottom: 24px; }
    .info-box-copy i { color: #0284c7; font-size: 1.2rem; margin-top: 2px; }
    .info-box-copy h6 { color: #0369a1; margin: 0 0 4px; font-weight: 600; font-size: 0.9rem; }
    .info-box-copy p { color: #0369a1; margin: 0; font-size: 0.85rem; opacity: 0.85; }
    
    .restaurant-display { padding: 16px; background: #f8fafc; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 14px; }
    .restaurant-icon { width: 50px; height: 50px; border-radius: 10px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; }
    .restaurant-icon i { color: var(--primary); font-size: 1.2rem; }
    .restaurant-info label { display: block; font-size: 0.75rem; color: #94a3b8; margin-bottom: 2px; }
    .restaurant-info strong { color: #0f172a; font-size: 1rem; }
    
    .copy-arrow { display: flex; justify-content: center; margin: 16px 0; }
    .copy-arrow i { font-size: 1.5rem; color: var(--primary); animation: bounce 1s infinite; }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(5px); } }
    
    .warning-box { display: flex; align-items: flex-start; gap: 12px; padding: 16px; background: #fffbeb; border-radius: 12px; border: 1px solid #fde68a; margin-bottom: 24px; }
    .warning-box i { color: #d97706; font-size: 1.2rem; margin-top: 2px; }
    .warning-box p { color: #92400e; margin: 0; font-size: 0.9rem; }
    
    .btn-copy { display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: var(--primary); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; transition: all 0.2s ease; }
    .btn-copy:hover { filter: brightness(1.1); transform: translateY(-2px); }
    .btn-copy:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
    
    .source-preview { display: none; margin-top: 16px; padding: 16px; background: #ecfdf5; border-radius: 12px; border: 1px solid #a7f3d0; }
    .source-preview.show { display: block; }
    .source-preview h6 { color: #047857; margin: 0 0 4px; font-size: 0.85rem; }
    .source-preview p { color: #047857; margin: 0; font-size: 0.9rem; }
</style>
@endpush

@section('content')
<div class="breadcrumb-modern">
    <a href="{{ route('admin.dashboard') }}">{{ __('app.home') }}</a>
    <span>/</span>
    <a href="{{ route('admin.restaurants.show', $restaurant) }}">{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</a>
    <span>/</span>
    <span class="current">{{ __('app.copy_menu') }}</span>
</div>

<div class="copy-page">
    <div class="copy-card">
        <div class="copy-card-header">
            <div class="icon"><i class="fas fa-copy"></i></div>
            <h5>{{ __('app.copy_menu') }}</h5>
            <p>{{ __('app.copy_menu_desc') }}</p>
        </div>
        
        <div class="copy-card-body">
            <div class="info-box-copy">
                <i class="fas fa-info-circle"></i>
                <div>
                    <h6>{{ __('app.how_it_works') }}</h6>
                    <p>{{ __('app.copy_info') }}</p>
                </div>
            </div>
            
            <form action="{{ route('admin.restaurants.copy.do', $restaurant) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label">{{ __('app.copy_from') }} <span class="required">*</span></label>
                    <select name="source_id" id="source-select" class="form-select @error('source_id') is-invalid @enderror" required>
                        <option value="">-- {{ __('app.select_source') }} --</option>
                        @foreach($otherRestaurants as $r)
                            @if($r->categories_count > 0)
                            <option value="{{ $r->id }}" data-categories="{{ $r->categories_count }}" data-items="{{ $r->items_count ?? 0 }}">
                                {{ app()->getLocale() == 'ar' ? ($r->display_name ?? $r->name_ar) : ($r->name_en ?? $r->name_ar) }} 
                                ({{ $r->categories_count }} {{ __('app.category') }})
                            </option>
                            @endif
                        @endforeach
                        
                        @if($otherRestaurants->where('categories_count', '>', 0)->isEmpty())
                        <option disabled>-- {{ __('app.no_restaurants_with_menu') }} --</option>
                        @endif
                    </select>
                    @error('source_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    
                    <div class="source-preview" id="source-preview">
                        <h6><i class="fas fa-check-circle me-1"></i> {{ __('app.will_copy') }}</h6>
                        <p id="preview-text"></p>
                    </div>
                </div>
                
                <div class="copy-arrow"><i class="fas fa-arrow-down"></i></div>
                
                <div class="restaurant-display">
                    <div class="restaurant-icon"><i class="fas fa-store"></i></div>
                    <div class="restaurant-info">
                        <label>{{ __('app.copy_to') }}</label>
                        <strong>{{ app()->getLocale() == 'ar' ? $restaurant->name_ar : $restaurant->name_en }}</strong>
                    </div>
                </div>
                
                @if($otherRestaurants->where('categories_count', '>', 0)->isEmpty())
                <div class="warning-box">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>{{ __('app.no_source_available') }}</p>
                </div>
                @endif
                
                <div class="form-actions spread">
                    <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="btn-copy" {{ $otherRestaurants->where('categories_count', '>', 0)->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-copy"></i>
                        {{ __('app.start_copy') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('source-select').addEventListener('change', function() {
    const preview = document.getElementById('source-preview');
    const previewText = document.getElementById('preview-text');
    const option = this.options[this.selectedIndex];
    
    if (this.value) {
        const categories = option.dataset.categories;
        const items = option.dataset.items || '0';
        previewText.textContent = `${categories} {{ __('app.categories') }} {{ __('app.and') }} ${items} {{ __('app.items') }}`;
        preview.classList.add('show');
    } else {
        preview.classList.remove('show');
    }
});
</script>
@endpush