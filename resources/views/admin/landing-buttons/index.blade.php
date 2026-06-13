@extends('layouts.admin')

@section('title', __('app.landing_buttons'))
@section('page-title', __('app.landing_buttons'))

@push('styles')
<style>
    .buttons-page { max-width: 900px; margin: 0 auto; }

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

    .buttons-card {
        background: white; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .card-header {
        padding: 20px 24px; background: linear-gradient(135deg, var(--primary), var(--primary));
        color: white;
    }
    .card-header h5 { margin: 0; font-weight: 600; }
    .card-header p { margin: 6px 0 0; opacity: 0.85; font-size: 0.85rem; }

    .buttons-list { padding: 16px; }

    .button-item {
        display: flex; align-items: center; gap: 16px;
        padding: 16px 20px; background: #f8fafc; border-radius: 12px;
        margin-bottom: 12px; transition: all 0.2s ease;
        border: 2px solid transparent;
    }
    .button-item:hover { border-color: var(--primary); }
    .button-item.dragging { opacity: 0.5; border-color: var(--primary); }
    .button-item.inactive { opacity: 0.5; }

    .drag-handle {
        cursor: grab; color: #94a3b8; font-size: 1.2rem;
        padding: 8px; margin: -8px;
    }
    .drag-handle:active { cursor: grabbing; }

    .button-icon {
        width: 50px; height: 50px; border-radius: 12px;
        background: var(--primary); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }

    .button-info { flex: 1; min-width: 0; }
    .button-info h6 { margin: 0 0 4px; font-weight: 600; color: #0f172a; font-size: 1rem; }
    .button-info p { margin: 0; color: #64748b; font-size: 0.85rem; }
    .button-info .type-badge {
        display: inline-block; padding: 2px 8px; border-radius: 6px;
        background: var(--primary-light); color: var(--primary);
        font-size: 0.75rem; font-weight: 600; margin-top: 4px;
    }

    .button-actions { display: flex; align-items: center; gap: 8px; }

    .toggle-btn {
        width: 50px; height: 28px; border-radius: 14px;
        background: #e2e8f0; border: none; cursor: pointer;
        position: relative; transition: all 0.2s ease;
    }
    .toggle-btn.active { background: #10b981; }
    .toggle-btn::after {
        content: ''; position: absolute; top: 3px; left: 3px;
        width: 22px; height: 22px; border-radius: 50%;
        background: white; transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .toggle-btn.active::after { left: 25px; }

    .edit-btn {
        width: 36px; height: 36px; border-radius: 8px;
        background: #f1f5f9; border: none; cursor: pointer;
        color: #64748b; transition: all 0.2s ease;
        display: flex; align-items: center; justify-content: center;
    }
    .edit-btn:hover { background: var(--primary); color: white; }

    /* Modal */
    .modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5); z-index: 1000;
        display: none; align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-overlay.show { display: flex; }

    .modal-content {
        background: white; border-radius: 16px; width: 100%;
        max-width: 500px; max-height: 90vh; overflow-y: auto;
    }

    .modal-header {
        padding: 20px 24px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-header h5 { margin: 0; font-weight: 600; }
    .modal-close {
        width: 36px; height: 36px; border-radius: 8px;
        background: #f1f5f9; border: none; cursor: pointer;
        color: #64748b; font-size: 1.2rem;
    }

    .modal-body { padding: 24px; }

    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 500; color: #374151; margin-bottom: 8px; display: block; }
    .form-control {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 10px;
        padding: 12px 16px; font-size: 0.95rem; transition: all 0.2s ease;
    }
    .form-control:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px var(--primary-light); }

    .modal-footer {
        padding: 16px 24px; border-top: 1px solid #f1f5f9;
        display: flex; justify-content: flex-end; gap: 12px;
    }

    .btn-cancel {
        padding: 10px 20px; border-radius: 8px; border: 1px solid #e2e8f0;
        background: white; color: #64748b; cursor: pointer; font-weight: 500;
    }
    .btn-save {
        padding: 10px 20px; border-radius: 8px; border: none;
        background: var(--primary); color: white; cursor: pointer; font-weight: 500;
    }

    /* Toast */
    .toast-notification {
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        padding: 16px 24px; border-radius: 12px; color: white;
        font-weight: 500; display: none;
    }
    .toast-notification.success { background: #10b981; }
    .toast-notification.show { display: flex; align-items: center; gap: 10px; }
</style>
@endpush

@section('content')
<div class="buttons-page">
    <div class="page-header">
        <div class="page-header-info">
            <h4><i class="fas fa-th-list"></i> {{ __('app.landing_buttons') }}</h4>
            <p>{{ __('app.landing_buttons_desc') }}</p>
        </div>
        <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> {{ __('app.back') }}
        </a>
    </div>

    <div class="buttons-card">
        <div class="card-header">
            <h5><i class="fas fa-sort"></i> {{ __('app.drag_to_reorder') }}</h5>
            <p>{{ __('app.drag_to_reorder_desc') }}</p>
        </div>

        <div class="buttons-list" id="buttons-list">
            @foreach($buttons as $button)
            <div class="button-item {{ !$button->is_active ? 'inactive' : '' }}" data-id="{{ $button->id }}">
                <div class="drag-handle">
                    <i class="fas fa-grip-vertical"></i>
                </div>

                <div class="button-icon">
                    <i class="fas fa-{{ $button->icon ?? 'link' }}"></i>
                </div>

                <div class="button-info">
                    <h6>{{ $button->title_ar }}</h6>
                    <p>{{ $button->title_en }}</p>
                    <span class="type-badge">{{ $button->type }}</span>
                </div>

                <div class="button-actions">
                    <form action="{{ route('admin.landing-buttons.toggle', [$restaurant, $button]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="toggle-btn {{ $button->is_active ? 'active' : '' }}" title="{{ $button->is_active ? __('app.deactivate') : __('app.activate') }}"></button>
                    </form>

                    <button type="button" class="edit-btn" onclick="openEditModal({{ $button->id }}, '{{ $button->title_ar }}', '{{ $button->title_en }}', '{{ $button->subtitle_ar }}', '{{ $button->subtitle_en }}', '{{ $button->icon }}')">
                        <i class="fas fa-pen"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="edit-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><i class="fas fa-edit"></i> {{ __('app.edit_button') }}</h5>
            <button type="button" class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">{{ __('app.title_ar') }}</label>
                    <input type="text" name="title_ar" id="edit-title-ar" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.title_en') }}</label>
                    <input type="text" name="title_en" id="edit-title-en" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.subtitle_ar') }}</label>
                    <input type="text" name="subtitle_ar" id="edit-subtitle-ar" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.subtitle_en') }}</label>
                    <input type="text" name="subtitle_en" id="edit-subtitle-en" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">{{ __('app.icon') }} (FontAwesome)</label>
                    <input type="text" name="icon" id="edit-icon" class="form-control" placeholder="e.g. utensils, phone, map-marker-alt">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">{{ __('app.cancel') }}</button>
                <button type="submit" class="btn-save">{{ __('app.save') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="toast-notification" id="toast"></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Sortable
    const buttonsList = document.getElementById('buttons-list');
    new Sortable(buttonsList, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'dragging',
        onEnd: function() {
            const buttons = Array.from(buttonsList.querySelectorAll('.button-item')).map(el => el.dataset.id);

            fetch('{{ route('admin.landing-buttons.reorder', $restaurant) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ buttons: buttons })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('{{ __("messages.order_updated") }}');
                }
            });
        }
    });

    // Edit Modal
    function openEditModal(id, titleAr, titleEn, subtitleAr, subtitleEn, icon) {
        document.getElementById('edit-form').action = '{{ url("admin/restaurants/" . $restaurant->id . "/landing-buttons") }}/' + id;
        document.getElementById('edit-title-ar').value = titleAr;
        document.getElementById('edit-title-en').value = titleEn;
        document.getElementById('edit-subtitle-ar').value = subtitleAr;
        document.getElementById('edit-subtitle-en').value = subtitleEn;
        document.getElementById('edit-icon').value = icon;
        document.getElementById('edit-modal').classList.add('show');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.remove('show');
    }

    // Close modal on outside click
    document.getElementById('edit-modal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // Toast
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.innerHTML = '<i class="fas fa-check-circle"></i> ' + message;
        toast.className = 'toast-notification success show';
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    @if(session('success'))
        showToast('{{ session("success") }}');
    @endif
</script>
@endpush
