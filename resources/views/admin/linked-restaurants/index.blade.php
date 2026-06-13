@extends('layouts.admin')

@section('title', __('app.linked_restaurants'))
@section('page-title', __('app.linked_restaurants'))

@push('styles')
<style>
    .linked-page { max-width: 900px; margin: 0 auto; }

    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-header-info h4 {
        font-weight: 700; color: #0f172a; margin-bottom: 4px;
        display: flex; align-items: center; gap: 10px;
    }
    .page-header-info h4 i { color: var(--primary); }
    .page-header-info p { color: #64748b; margin: 0; font-size: 0.9rem; }

    .info-card {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        border-radius: 16px; padding: 20px; margin-bottom: 24px; color: white;
    }
    .info-card h5 { margin: 0 0 8px; font-size: 1rem; display: flex; align-items: center; gap: 8px; }
    .info-card p { margin: 0; opacity: 0.9; font-size: 0.9rem; line-height: 1.6; }

    .section-card {
        background: white; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        margin-bottom: 24px; overflow: hidden;
    }

    .section-header {
        padding: 20px 24px; background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex; justify-content: space-between; align-items: center;
    }
    .section-header h5 { margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .section-header h5 i { color: var(--primary); }

    .section-body { padding: 24px; }

    .toggle-switch {
        display: flex; align-items: center; gap: 12px;
        padding: 16px 20px; background: #f8fafc; border-radius: 12px;
    }
    .toggle-switch input[type="checkbox"] {
        width: 48px; height: 26px; cursor: pointer;
        appearance: none; background: #e2e8f0; border-radius: 13px;
        position: relative; transition: all 0.3s;
    }
    .toggle-switch input[type="checkbox"]:checked { background: var(--primary); }
    .toggle-switch input[type="checkbox"]::after {
        content: ''; position: absolute; top: 3px; left: 3px;
        width: 20px; height: 20px; background: white; border-radius: 50%;
        transition: all 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-switch input[type="checkbox"]:checked::after { left: 25px; }
    .toggle-info label { font-weight: 500; color: #374151; margin: 0; display: block; cursor: pointer; }
    .toggle-info small { color: #94a3b8; font-size: 0.8rem; }

    .restaurant-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .restaurant-item {
        display: flex; flex-direction: column; align-items: center;
        padding: 20px; background: #f8fafc; border-radius: 12px;
        text-align: center; position: relative;
        border: 2px solid transparent; transition: all 0.2s;
    }
    .restaurant-item.linked { border-color: var(--primary); background: var(--primary-light); }
    .restaurant-item.selectable { cursor: pointer; }
    .restaurant-item.selectable:hover { border-color: var(--primary); }
    .restaurant-item.selected { border-color: var(--primary); background: var(--primary-light); }

    .restaurant-item .logo {
        width: 70px; height: 70px; border-radius: 50%;
        background: white; margin-bottom: 12px; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .restaurant-item .logo img { width: 100%; height: 100%; object-fit: cover; }
    .restaurant-item .logo i { font-size: 1.5rem; color: #94a3b8; }

    .restaurant-item h6 { margin: 0 0 4px; font-weight: 600; font-size: 0.95rem; }
    .restaurant-item p { margin: 0; color: #64748b; font-size: 0.8rem; }

    .restaurant-item .unlink-btn {
        position: absolute; top: 8px; right: 8px;
        width: 28px; height: 28px; border-radius: 50%;
        background: #ef4444; color: white; border: none;
        cursor: pointer; font-size: 0.8rem;
        display: flex; align-items: center; justify-content: center;
        opacity: 0; transition: opacity 0.2s;
    }
    .restaurant-item:hover .unlink-btn { opacity: 1; }

    .restaurant-item .check-icon {
        position: absolute; top: 8px; right: 8px;
        width: 24px; height: 24px; border-radius: 50%;
        background: var(--primary); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.7rem;
    }

    .empty-state {
        text-align: center; padding: 40px 20px; color: #64748b;
    }
    .empty-state i { font-size: 3rem; color: #e2e8f0; margin-bottom: 12px; }
    .empty-state h6 { margin: 0 0 8px; color: #374151; }
    .empty-state p { margin: 0; font-size: 0.9rem; }

    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 24px; background: var(--primary); color: white;
        border: none; border-radius: 10px; font-weight: 600;
        cursor: pointer; transition: all 0.2s;
    }
    .btn-primary:hover { filter: brightness(1.1); }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; background: white; color: #64748b;
        border: 1px solid #e2e8f0; border-radius: 10px; font-weight: 500;
        cursor: pointer; transition: all 0.2s; text-decoration: none;
    }
    .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
</style>
@endpush

@section('content')
<div class="linked-page">
    <div class="page-header">
        <div class="page-header-info">
            <h4><i class="fas fa-link"></i> {{ __('app.linked_restaurants') }}</h4>
            <p>{{ __('app.linked_restaurants_desc') }}</p>
        </div>
        <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="btn-outline">
            <i class="fas fa-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>

    <div class="info-card">
        <h5><i class="fas fa-info-circle"></i> {{ __('app.how_linked_works') }}</h5>
        <p>{{ __('app.how_linked_works_desc') }}</p>
    </div>

    {{-- Toggle Selector --}}
    @if($restaurant->hasLinkedRestaurants())
    <div class="section-card">
        <div class="section-body">
            <form action="{{ route('admin.linked-restaurants.toggle', $restaurant) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="toggle-switch">
                    <input type="checkbox" id="show_selector" name="show_linked_selector"
                           {{ $restaurant->show_linked_selector ? 'checked' : '' }}
                           onchange="this.form.submit()">
                    <div class="toggle-info">
                        <label for="show_selector">{{ __('app.show_selector_page') }}</label>
                        <small>{{ __('app.show_selector_page_hint') }}</small>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Current Linked Restaurants --}}
    <div class="section-card">
        <div class="section-header">
            <h5><i class="fas fa-link"></i> {{ __('app.current_linked') }}</h5>
        </div>
        <div class="section-body">
            @if($linkedRestaurants->count() > 1)
            <div class="restaurant-grid">
                @foreach($linkedRestaurants as $linked)
                <div class="restaurant-item linked">
                    <div class="logo">
                        @if($linked->logo)
                            <img src="{{ asset('storage/' . $linked->logo) }}" alt="{{ $linked->name_ar }}">
                        @else
                            <i class="fas fa-utensils"></i>
                        @endif
                    </div>
                    <h6>{{ $linked->name_ar }}</h6>
                    <p>{{ $linked->name_en }}</p>

                    @if($linked->id !== $restaurant->id)
                    <form action="{{ route('admin.linked-restaurants.unlink', [$restaurant, $linked]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="unlink-btn" title="{{ __('app.unlink') }}" onclick="return confirm('{{ __('app.confirm_unlink') }}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-unlink"></i>
                <h6>{{ __('app.no_linked_restaurants') }}</h6>
                <p>{{ __('app.no_linked_restaurants_hint') }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Add Linked Restaurants --}}
    @if($availableRestaurants->count() > 0)
    <div class="section-card">
        <div class="section-header">
            <h5><i class="fas fa-plus-circle"></i> {{ __('app.add_linked') }}</h5>
        </div>
        <div class="section-body">
            <form action="{{ route('admin.linked-restaurants.link', $restaurant) }}" method="POST" id="link-form">
                @csrf
                <div class="restaurant-grid" style="margin-bottom: 20px;">
                    @foreach($availableRestaurants as $available)
                    <label class="restaurant-item selectable" data-id="{{ $available->id }}">
                        <input type="checkbox" name="restaurant_ids[]" value="{{ $available->id }}" style="display:none;">
                        <div class="check-icon" style="display:none;"><i class="fas fa-check"></i></div>
                        <div class="logo">
                            @if($available->logo)
                                <img src="{{ asset('storage/' . $available->logo) }}" alt="{{ $available->name_ar }}">
                            @else
                                <i class="fas fa-utensils"></i>
                            @endif
                        </div>
                        <h6>{{ $available->name_ar }}</h6>
                        <p>{{ $available->name_en }}</p>
                    </label>
                    @endforeach
                </div>
                <button type="submit" class="btn-primary" id="link-btn" disabled>
                    <i class="fas fa-link"></i>
                    {{ __('app.link_selected') }}
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.restaurant-item.selectable').forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            const checkIcon = this.querySelector('.check-icon');
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('selected', checkbox.checked);
            checkIcon.style.display = checkbox.checked ? 'flex' : 'none';

            const anyChecked = document.querySelectorAll('.restaurant-item.selectable input:checked').length > 0;
            document.getElementById('link-btn').disabled = !anyChecked;
        });
    });

    @if(session('success'))
        alert('{{ session("success") }}');
    @endif
</script>
@endpush
